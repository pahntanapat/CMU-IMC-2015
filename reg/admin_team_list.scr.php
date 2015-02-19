<?php
require_once 'config.inc.php';
require_once 'class.SesAdm.php';

$sess=SesAdm::check(SesAdm::PMS_PARTC|SesAdm::PMS_GM);
if(!$sess) Config::redirect('admin.php','you are forbidden.');

require_once 'class.Team.php';
require_once 'admin_team_list.view.php';
require_once 'class.SKAjax.php';
$ajax=new SKAjax();

$db=$config->PDO();
$t=new Team($db);

if(Config::isPost()){
	$ajax->result=false;
	
	if($sess->checkPMS(SesAdm::PMS_PARTC)){
		try{
			$db->beginTransaction();
			
			require_once 'class.Participant.php';
			require_once 'class.UploadImage.php';
			
			$p=new Participant($db);
			$o=new Observer($db);
			$i=new UploadImage();
			
			$t->del($_POST['del']);
			$p->del(array(), $_POST['del']);
			$o->del(array(), $_POST['del']);
			$i->deleteFolder($_POST['del']);
			
			$ajax->result=true;
			$ajax->message='Delete Participants\' teams success';
			$db->commit();
		}catch(Exception $e){
			$db->rollBack();
			$ajax->message=Config::e($e);
			$ajax->result=false;
		}
		unset($p,$o,$i);
	}else{
		$ajax->message='You don\'t have permission to delete participant\'s teams.';
	}
	$ajax->message=teamList($t,'',$ajax->message);
}elseif(isset($_GET['id'])){
	$ajax->msgID='divTeamInfo';
	$ajax->message='';
}else{
	$ajax->msgID='divTeamList';
	$ajax->message=teamList($t);
}
?>
