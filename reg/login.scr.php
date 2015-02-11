<?php
require_once 'config.inc.php';
require_once 'class.SesPrt.php';

$sess=new SesPrt();
if($sess->checkSession()) Config::redirect('./','You have already logged in');

require_once 'class.SKAjax.php';
$ajax=new SKAjax();
$ajax->result=false;

if(Config::isPost()){
	if(!Config::checkCAPTCHA()){
		$ajax->message='The CAPTCHA Answer is wrong. Please try again.';
	}elseif(Config::isBlank($_POST,'email','pw')){
		$ajax->message='You must fill out all fields';
	}elseif(!Config::checkEmail($_POST['email'],$e)){
		$ajax->message=$e;
	}else{
		require_once 'class.Team.php';
		$t=Config::assocToObjProp($_POST,new Team($config->PDO()));
		if($t->auth(true)){
			$sess->id=$t->id;
			$sess->teamName=$t->team_name;
			
			$sess->country=$t->country;
			$sess->institution=$t->institution;
			$sess->university=$t->university;
			
			$sess->teamState=$t->team_state;
			$sess->payState=$t->pay_state;
			$sess->postRegState=$t->post_reg_state;
			
			$sess->setInfoState($t->getInfoState());
			$sess->setPostRegInfoState($t->getPostRegInfoState());
			$sess->setProgression();
			$sess->write();
			
			$ajax->message='Log in success. You are redirected to main page.';
			if(Config::isAjax()) $ajax->addAction(SKAjax::REDIRECT,'./');
			$ajax->result=true;
		}else{
			$ajax->message='Log in fail, there is not email or password in database.';
			$ajax->addAction(SKAjax::RELOAD_CAPTCHA);
		}
	}
}

if(Config::isAjax()) Config::JSON($ajax,true);
elseif($ajax->result) Config::redirect('./',$ajax->message);
?>