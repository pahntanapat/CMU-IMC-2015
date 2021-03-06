<?php
require_once 'config.inc.php';
require_once 'class.SesAdm.php';

// Processing section
$sess=SesAdm::check();
if($sess)
	Config::redirect('home.php','You have already logged in.');

require_once 'class.SKAjax.php';
$ajax=new SKAjax();
$ajax->result=false;

if(!Config::checkCAPTCHA()){
	$ajax->message="The CAPTCHA Answer is wrong. Please try again.";
}elseif(Config::isBlank($_POST,'student_id','password')){
	$ajax->message="You must fill out Student ID and Password. Do not leave it blank.";
}else{
	try{
		require_once 'class.Admin.php';
		$adm=Config::assocToObjProp($_POST,new Admin($config->PDO(true)));
		$adm->student_id=trim($adm->student_id);
		$ajax->result=$adm->auth();
		if($ajax->result){
			$sess=new SesAdm();
			$sess->id=$adm->id;
			$sess->student_id=$adm->student_id;
			$sess->nickname=$adm->nickname;
			$sess->pms=(int) $adm->permission;
			$ajax->message='Log in successfully. You are redirected to main page.';
		}else{
			$ajax->message='Fail to log in, your student ID or password are incorrect.';
		}
	}catch(Exception $e){
		$ajax->message.="<br>\nLog in fail, ".Config::e($e);
		$ajax->result=false;
	}
}

//Controller of Processor section
if(Config::isAjax()){
	if($ajax->result) $ajax->addAction(SKAjax::REDIRECT,'home.php');
	else $ajax->addAction(SKAjax::RELOAD_CAPTCHA);
	Config::JSON($ajax);
}elseif($ajax->result){
	Config::redirect('home.php',$ajax->message);
}
?>