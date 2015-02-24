<?php
require_once 'config.inc.php';
require_once 'class.SesAdm.php';

$sess=SesAdm::check();
if(!$sess) Config::redirect('admin.php','you are not log in.');
if(!$sess->checkPMS(SesAdm::PMS_PARTC)) Config::redirect('home.php','you don\'t have permission here.');

$db=$config->PDO();

require_once 'class.SKAjax.php';
$ajax=new SKAjax();

if(Config::isPost()){ // Submit
	try{
		
	}catch(Exception $e){
		
	}
}elseif(isset($_GET['id'])){ //Show info of team ID: $_GET['id']
	require_once 'class.UploadImage.php';
	require_once 'class.Message.php';
	require_once 'class.Team.php';
	require_once 'class.State.php';
	
	$img=new UploadImage();
	$img->team_id=$_GET['id'];
	
	$t=new Team($db);
	$t->id=$img->team_id;
	$t->load();
	
	$msg=new Message($db);
	$msg->team_id=$img->team_id;
	$msg->admin_id=$sess->id;
	
	
	$ajax->msgID='apPay';
	$ajax->message=$img->toImgPay()
		.($msg->load(Message::PAGE_PAY)->toForm('admin_pay.scr.php',
			array(State::ST_WAIT,State::ST_PASS,State::ST_NOT_PASS),
			$t->pay_state
	));
}else{ // Show all table
	require_once 'admin_team_list.view.php';
	require_once 'class.Team.php';
	$ajax->msgID='teamList';
	$ajax->message=teamList(new Team($db), 'admin_pay.php', Team::ROW_PAY_STATE);
}
if(Config::isAjax()) Config::JSON($ajax);
?>