<?php
require_once 'config.inc.php';
require_once 'class.SesPrt.php';

$sess=SesPrt::check();
if(!$sess) Config::redirect('login.php','You do not log in.');

require_once 'class.Element.php';
$elem=new Element();
$elem->result=false;

if(Config::isPost()){ //Change Password
	require_once 'class.Team.php';
	
	$elem->msgCP='';
	if(Config::isBlank($_POST,'pw','cfPW','oldPassword')){
		$elem->msgCP='Password must not leave blank.';
	}else	if($_POST['pw']!=$_POST['cfPW']){
		$elem->msgCP='Confirm password must same to new password!';
	}elseif(!Config::checkPW($_POST['cfPW'],$adm)){
		$elem->msgCP=$adm;
	}else{
		try{
			$t=new Team($config->PDO());
			$t->id=$sess->id;
			$t->pw=$_POST['pw'];
			$elem->result=$t->changePW($_POST['oldPassword']);
			$elem->msgCP='Change password '.($elem->result?' success':'fail');
		}catch(Exception $e){
			$elem->result=false;
			$elem->msgCP=Config::e($e);
		}
	}
}else{ //Reload message
	require_once 'class.Message.php';
	$msg=new Message($config->PDO());
	$msg->team_id=$sess->id;
	$elem->msg=$msg->__toString();
	$elem->result=true;
}

if(Config::isAjax()){
	require_once 'class.SKAjax.php';
	$json=new SKAjax();
	$json->addHtmlTextVal(SKAjax::SET_HTML,'#'.(isset($elem->msgCP)?'msgCP':'msg'),(isset($elem->msgCP)?$elem->msgCP:$elem->msg));
	$json->addHtmlTextVal(SKAjax::SET_VAL,':password','');
	$json->result=$elem->result;
	Config::JSON($json,true);
}
?>