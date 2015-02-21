<?php
require_once 'config.inc.php';
require_once 'class.SesAdm.php';

$sess=SesAdm::check(SesAdm::PMS_PARTC|SesAdm::PMS_GM);
if(!$sess) Config::redirect('admin.php','you are forbidden.');

require_once 'class.Team.php';
require_once 'class.Member.php';
require_once 'class.UploadImage.php';

require_once 'admin_team_list.view.php';
require_once 'class.SKAjax.php';

$ajax=new SKAjax();

$db=$config->PDO();

if(Config::isPost()){
	$ajax->result=false;
	if($sess->checkPMS(SesAdm::PMS_PARTC)){
		try{
			$db->beginTransaction();
			switch($_POST['act']){
				case 'del':
					$t=new Team($db);
					$p=new Participant($db);
					$o=new Observer($db);
					$i=new UploadImage();
					
					$t->del($_POST['del']);
					$p->del(array(), $_POST['del']);
					$o->del(array(), $_POST['del']);
					$i->deleteFolder($_POST['del']);
					
					$ajax->result=true;
					$ajax->msgID='divTeamList';
					$ajax->message=fullList($db,'Delete Participants\' teams success');
					break;
				case 'team':
					$t=Config::assocToObjProp(Config::trimArray($_POST),new Team($db));
					
					if($t->id==0) $t->add();
					$t->update();
					
					$i=new UploadImage();
					$i->id=$t->id;
				//	if($_POST['delete']) $i->deletePartStudentCard(;
					$ajax->result=true;
					$ajax->message='Update team\'s info success';
					break;
				case 'part':
					$m=Config::assocToObjProp(Config::trimArray($_POST),$_POST['part_no']>0?new Participant($db):new Observer($db));
					if($m->team_id==0) break;
					if($m->id==0) $m->add();
					$m->update(true);
					
					$i=new UploadImage();
					$i->id=$m->team_id;
					if($_POST['delete']) $i->deletePartStudentCard($m->part_no);
					
					$ajax->result=true;
					$ajax->message='Update participant/observer\'s info success';
					break;
			}
			$db->commit();
		}catch(Exception $e){
			$db->rollBack();
			$ajax->message=Config::e($e);
			$ajax->result=false;
		}
	}else{
		$ajax->message='You don\'t have permission to do this action.';
	}
	switch($_POST['act']){
		case 'del':
			$ajax->msgID='divTeamList';
			$ajax->message=fullList($db,$ajax->message);
			break;
		case 'team':
		case 'part':
			if(Config::isAjax()){
				$ajax->msgID='teamInfoMsg';
			}else{
				$ajax->msgID='divTeamInfo';
				$ajax->message=teamInfo($db, $sess->checkPMS(SesAdm::PMS_ADMIN), $ajax->message);
			}
			break;
	}
}elseif(isset($_GET['id'])){
	$ajax->msgID='divTeamInfo';
	$ajax->message=teamInfo($_GET['id'], $sess->checkPMS(SesAdm::PMS_ADMIN));
}else{
	$ajax->msgID='divTeamList';
	$ajax->message=fullList($db);
}

if(Config::isAjax()) Config::JSON($ajax,true);
?>
