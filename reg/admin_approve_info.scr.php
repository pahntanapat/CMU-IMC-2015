<?php
require_once 'config.inc.php';
require_once 'class.SesAdm.php';

$sess=SesAdm::check();
if(!$sess) Config::redirect('admin.php','you are not log in.');
if(!$sess->checkPMS(SesAdm::PMS_PARTC)) Config::redirect('home.php','you don\'t have permission here.');

require_once 'admin_approve_info.view.php';
$db=$config->PDO();

require_once 'class.SKAjax.php';
$ajax=new SKAjax();

if(Config::isPost()){ // Submit
	try{
		require_once 'class.Message.php';
		require_once 'class.Team.php';
		require_once 'class.State.php';

		$db->beginTransaction();
		$msg=Config::assocToObjProp(Config::trimArray($_POST), new Message($db));
		$msg->admin_id=$sess->id;
		$msg->update();
		switch($msg->show_page){
			case Message::PAGE_INFO_TEAM:
				$t=new Team($db);
				$t->id=$msg->team_id;
				$t->team_state=$_POST['approve'];
				$t->setState(Team::ROW_TEAM_STATE);
				
				$ajax->message='Approve Team\'s info success';
				break;
			case Message::PAGE_INFO_OBSERVER:
				require_once 'class.Member.php';
				$m=new Observer($db);
				$m->team_id=$msg->team_id;
				
				list($m->id)=json_decode($_POST['add_info']);
				$m->info_state=$_POST['approve'];
				$m->setState();
				
				$ajax->message='Approve Advisor\'s info success';
				break;
			default:
				$_POST['add_info']=json_decode($_POST['add_info']);
				if($_POST['add_info'][1]>$config->REG_PARTICIPANT_NUM || $_POST['add_info'][1]<0) break;
				require_once 'class.Member.php';
				$m=new Participant($db);
				$m->team_id=$msg->team_id;
				$m->id=$_POST['add_info'][0];
				$m->info_state=$_POST['approve'];
				$m->setState();
				
				$ajax->message='Approve '.Config::ordinal($_POST['add_info'][1]).' Participant\'s info success';
		}
		$t=new Team($db);
		$t->id=$msg->team_id;
		$t->auth(false);
		
		$m=true;
		$m&=$t->team_state==State::ST_PASS;
		for($i=0;$i<=$config->REG_PARTICIPANT_NUM;$i++)
			$m&=$t->getParticipantInfoState($i)==State::ST_PASS;
		
		if($m){
			$t->pay_state=State::ST_EDITABLE;
			$t->setState(Team::ROW_PAY_STATE);
		}
		
		$ajax->result=true;
		$db->commit();
	}catch(Exception $e){
		$db->rollBack();
		$ajax->message=Config::e($e);
		$ajax->result=false;
	}
	if(Config::isAjax()){
		$ajax->msgID='apMsg';
	}else{
		$ajax->msgID='approveForm';
		$ajax->message=approveTeam($msg,$ajax->message);
	}
}elseif(isset($_GET['id'])){ //Show info of team ID: $_GET['id']
	require_once 'class.Message.php';
	$msg=new Message($db);
	$msg->team_id=$_GET['id'];
	$msg->admin_id=$sess->id;
	
	$ajax->msgID='approveForm';
	$ajax->message=approveTeam($msg);
}else{ // Show all table
	require_once 'admin_team_list.view.php';
	$ajax->msgID='teamList';
	$ajax->message=teamList(new Team($db), 'admin_approve_info.php', Team::ROW_TEAM_STATE);
}
if(Config::isAjax()) Config::JSON($ajax);
?>