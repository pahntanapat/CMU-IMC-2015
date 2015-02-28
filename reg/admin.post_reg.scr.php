<?php
require_once 'config.inc.php';
require_once 'class.SesAdm.php';

$sess=SesAdm::check();
if(!$sess) Config::redirect('admin.php','you are not log in.');
if(!$sess->checkPMS(SesAdm::PMS_PARTC)) Config::redirect('home.php','you don\'t have permission here.');

$db=$config->PDO();
require_once 'class.State.php';
require_once 'class.SKAjax.php';
require_once 'class.Team.php';
require_once 'class.Message.php';

$ajax=new SKAjax();
$ajax->result=false;

if(Config::isPost()){ // Submit
	try{
		$db->beginTransaction();
		$msg=new Message($db);
		$msg=Config::assocToObjProp(Config::trimArray($_POST), new Message($db));
		$msg->admin_id=$sess->id;
		$msg->show_page=Message::PAGE_POST_REG_TEAM;
		$msg->update();
		
		$t=new Team($db);
		$t->id=$msg->team_id;
		$t->post_reg_state=$_POST['approve'];
		$t->setState(Team::ROW_POST_REG_STATE);
		
		$ajax->message='Successfully approve team\'s information';
		$ajax->result=true;
		$db->commit();
	}catch(Exception $e){
		$db->rollBack();
		$ajax->message=Config::e($e);
		$ajax->result=false;
	}
	if(!Config::isAjax()){
		require_once 'admin.post_reg.view.php';
		$ajax->msgID='approveForm';
		$ajax->message=showPostReg($msg, $ajax->message);
	}
}elseif(isset($_GET['id'])){ //Show info of team ID: $_GET['id']
	require_once 'admin.post_reg.view.php';
	$msg=new Message($db);
	$msg->team_id=$_GET['id'];
	$msg->admin_id=$sess->id;
	$msg->show_page=Message::PAGE_POST_REG_TEAM;
	
	$ajax->msgID='approveForm';
	$ajax->message=showPostReg($msg);
}else{ // Show all table
	require_once 'admin.team.view.php';
	$ajax->msgID='teamList';
	$ajax->message=teamList(new Team($db), 'admin.post_reg.php', Team::ROW_POST_REG_STATE);
}
if(Config::isAjax()) Config::JSON($ajax);
?>