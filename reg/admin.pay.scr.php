<?php
require_once 'config.inc.php';
require_once 'class.SesAdm.php';

$sess=SesAdm::check();
if(!$sess) Config::redirect('admin.php','you are not log in.');
if(!$sess->checkPMS(SesAdm::PMS_AUDIT)) Config::redirect('home.php','you don\'t have permission here.');

$db=$config->PDO();

require_once 'class.Message.php';
require_once 'class.Team.php';
require_once 'class.UploadImage.php';
require_once 'class.State.php';
require_once 'class.SKAjax.php';

$ajax=new SKAjax();
$ajax->result=false;

function showPay($teamID, $adminID, Team $t, Message $msg, UploadImage $img, $message=''){
	$img->team_id=$teamID;

	$t->id=$img->team_id;
	$t->load();
	
	$msg->team_id=$img->team_id;
	$msg->admin_id=$adminID;
	$msg->show_page=Message::PAGE_PAY;
	
	return $img->toImgPay()
		.($msg->load()->toForm('admin.pay.php?id='.$teamID,
			array(State::ST_WAIT, State::ST_PASS, State::ST_NOT_PASS),
			$t->pay_state
		))
		."<div id=\"msg\">$message</div>";
}

if(Config::isPost()){ // Submit
	try{
		$db->beginTransaction();
		
		$msg=Config::assocToObjProp(Config::trimArray($_POST), new Message($db));
		$msg->admin_id=$sess->id;
		$msg->update();
		
		$t=new Team($db);
		$t->id=$msg->team_id;
		$t->pay_state=$_POST['approve'];
		$t->setState(Team::ROW_PAY_STATE);
		
		$t->post_reg_state=$t->pay_state==State::ST_PASS?State::ST_EDITABLE:State::ST_LOCKED;
		$t->setState(Team::ROW_POST_REG_STATE);
		
		$ajax->message='Approve the transfer slip complete.';
		$ajax->result=true;
		
		$db->commit();
	}catch(Exception $e){
		$db->rollBack();
		$ajax->message=Config::e($e);
		$ajax->result=false;
	}
	if(!Config::isAjax()){
		$ajax->msgID='approveForm';
		$ajax->message=showPay($t->id, $sess->id, $t, $msg, new UploadImage(), $ajax->message);
	}
}elseif(isset($_GET['id'])){ //Show info of team ID: $_GET['id']
	$ajax->msgID='approveForm';
	$ajax->message=showPay($_GET['id'], $sess->id, new Team($db), new Message($db), new UploadImage());
}else{ // Show all table
	require_once 'admin.team.view.php';
	$ajax->msgID='teamList';
	$ajax->message=teamList(new Team($db), 'admin.pay.php', Team::ROW_PAY_STATE);
}
if(Config::isAjax()) Config::JSON($ajax);
?>