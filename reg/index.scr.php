<?php
require_once 'config.inc.php';
require_once 'class.SesPrt.php';

$sess=SesPrt::check();
if(!$sess) Config::redirect('login.php','You do not log in.');

if(Config::isPost()){ //Change Password
	require_once 'class.Team.php';
	require_once 'class.SKAjax.php';
	$ajax=new SKAjax();
	$ajax->result=false;
	$ajax->msgID='msgCP';
	
	if(Config::isBlank($_POST,'pw','cfPW','oldPassword')){
		$ajax->message='Please fill out password';
	}else	if($_POST['pw']!=$_POST['cfPW']){
		$ajax->message='Please fill out the same password in "new password" and "confirm password".';
	}elseif(!Config::checkPW($_POST['cfPW'],$t)){
		$ajax->message=$t;
	}else{
		try{
			$t=new Team($config->PDO());
			$t->id=$sess->id;
			$t->pw=$_POST['pw'];
			$ajax->result=$t->changePW($_POST['oldPassword']);
			$ajax->message=($ajax->result?'Successfully':'Fail to').' change password ';
			if($ajax->result && Config::isAjax()) $ajax->addHtmlTextVal(SKAjax::SET_VAL,':password','');
		}catch(Exception $e){
			$ajax->result=false;
			$ajax->message=Config::e($e);
		}
	}
	if(Config::isAjax()) Config::JSON($ajax);
}else{ //Reload message
	require_once 'class.Message.php';
	$msg=new Message($config->PDO());
	$msg->team_id=$sess->id;
	
	$sess->changeID(true);
	
	if(Config::isAjax()){
		require_once 'class.SKAjaxReg.php';
		$ajax=new SKAjaxReg();
		$ajax->message=$msg->__toString();
		$ajax->msgID="teamMsg";
		$ajax->updateMenuState($sess);
		Config::JSON($ajax);
	}
}
?>