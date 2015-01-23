<?php
require_once 'config.inc.php';
require_once 'class.SesPrt.php';

$sess=new SesPrt();
if($sess->checkSession()) Config::redirect('./','You have already logged in.');

require_once 'class.Element.php';
$elem=new Element();

if(Config::isPost()){
	$elem->result=false;
	if(!Config::checkCAPTCHA()){
		$elem->msg='The CAPTCHA Answer is wrong. Please try again.';
	}elseif(Config::isBlank($_POST,'email','pw','country','institution','university')){
		$elem->msg='You must fill out all fields';
	}elseif(!Config::checkEmail($_POST['email'],$e)){
		$elem->msg=$e;
	}elseif(!Config::checkPW($_POST['pw'],$e)){
		$elem->msg=$e;
	}elseif($_POST['pw']!=$_POST['cpw']){
		$elem->msg='Comfirm password must be same to password.';
	}else{
		try{
			require_once 'class.Team.php';
			$team=new Team($config->PDO());
			$team->beginTransaction();
			
			$team=Config::assocToObjProp($_POST,$team);
			if($team->add()===false){
				$elem->msg='Error: Can not regist new team';
			}else{
				$elem->msg='Register: Create new account success. Please go to '."<a href=\"login.php\" title=\"Log in page\">Log in page</a>";
				$elem->result=true;
			}
			
			$team->commit();
		}catch(Exception $e){
			$team->rollBack();
			$elem->result=false;
			$elem->msg=Config::e($e);
		}
	}
}

if(Config::isAjax()){
	require_once 'class.SKAjax.php';
	$json=new SKAjax();
	$json->result=$elem->result;
	$json->addHtmlTextVal(SKAjax::SET_HTML,'#msg',$elem->msg);
	if($elem->result)
		$json->addHtmlTextVal(SKAjax::SET_HTML,'#reg',NULL);
	else
		$json->addAction(SKAjax::RELOAD_CAPTCHA);
	Config::JSON($json);
}
?>