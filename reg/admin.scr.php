<?php
require_once 'config.inc.php';
require_once 'class.Element.php';
require_once 'class.SesAdm.php';
require_once 'class.Admin.php';

//Model section
$elem=new Element();
$elem->msg='';
$elem->auth=false;
$sess=SesAdm::check();
if($sess){ //Have already logged in
	Config::redirect('home.php','You have already logged in.');
}

if(!Config::checkCAPTCHA()){
	$elem->msg="The CAPTCHA Answer is wrong. Please try again.";
}elseif(Config::isBlank($_POST,'student_id','password')){
	$elem->msg="You must fill out Student ID and Password. Do not leave it blank.";
}else{
	try{
		$adm=new Admin($config->PDO(true));
		$adm=Config::assocToObjProp($_POST,$adm);
		$elem->auth=$adm->auth();
		if($elem->auth){
			$sess=new SesAdm();
			$sess->student_id=$adm->student_id;
			$sess->nickname=$adm->nickname;
			$sess->pms=(int) $adm->permission;
			$elem->msg='Log in success. You are redirected to main page.';
		}else{
			$elem->msg='Log in fail, your student ID or password are incorrect.';
		}
	}catch(Exception $e){
		$elem->msg.="<br>\nLog in fail, ".Config::e($e);
		$elem->auth=false;
	}
}

//Controller section
if(Config::isAjax()){
	require_once 'class.SKAjax.php';
	$ajax=new SKAjax();
	$ajax->addAction(SKAjax::RELOAD_CAPTCHA);
	$ajax->addHtmlTextVal(SKAjax::SET_HTML,'#msg',$elem->msg);
	if($elem->auth) $ajax->addAction(SKAjax::REDIRECT,'home.php');
	Config::JSON($ajax,true);
}elseif($elem->auth){
	Config::redirect('home.php',$elem->msg);
}
?>