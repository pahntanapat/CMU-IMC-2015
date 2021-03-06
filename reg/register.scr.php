<?php
require_once 'config.inc.php';
require_once 'class.SesPrt.php';

$sess=new SesPrt();
if($sess->checkSession()) Config::redirect('./','You have already logged in.');

if(Config::isPost()){
	require_once 'class.SKAjax.php';
	require_once 'class.State.php';
	$ajax=new SKAjax();
	$ajax->result=false;
	if(!State::is(State::ST_EDITABLE, State::ST_EDITABLE, $config->REG_START_REG, $config->REG_END_REG)){
		$ajax->message='You are not allowed to register now.';
	}elseif(!Config::checkCAPTCHA()){
		$ajax->message='The CAPTCHA Answer is wrong. Please try again.';
	}elseif(Config::isBlank($_POST,'email','pw','country','institution','university')){
		$ajax->message='You must fill out all fields';
	}elseif(strlen($_POST['team_name'])>30){
		$ajax->message='Team\'s name must contain only 1 - 30 characters.';
	}elseif(!Config::checkEmail($_POST['email'],$e)){
		$ajax->message=$e;
	}elseif(!Config::checkPW($_POST['pw'],$e)){
		$ajax->message=$e;
	}elseif($_POST['pw']!=$_POST['cpw']){
		$ajax->message='Comfirm password must be same to password.';
	}else{
		try{
			require_once 'class.Team.php';
			$team=new Team($config->PDO());
			$team->beginTransaction();
			
			$team=Config::assocToObjProp(Config::trimArray($_POST),$team);
			if($team->add()===false){
				$ajax->message='Error: Can not regist new team';
			}else{
				$ajax->result=true;
				$ajax->message='Register: Create new account successfully. Please go to '."<a href=\"login.php\" title=\"Log in page\">Log in page</a>";
				$ajax->addHtmlTextVal(SKAjax::SET_HTML,'#reg',NULL);
			}
			
			$team->commit();
		}catch(Exception $e){
			$team->rollBack();
			$ajax->result=false;
			$ajax->message=Config::e($e);
		}
	}
	if(!$ajax->result) $ajax->addAction(SKAjax::RELOAD_CAPTCHA);
	if(Config::isAjax()) Config::JSON($ajax);
}
?>