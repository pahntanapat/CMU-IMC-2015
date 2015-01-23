<?php
require_once 'config.inc.php';
require_once 'class.SesPrt.php';

$sess=new SesPrt();
if($sess->checkSession()) Config::redirect('./','You have already logged in');

require_once 'class.Element.php';
$elem=new Element();
$elem->result=false;

if(Config::isPost()){
	if(!Config::checkCAPTCHA()){
		$elem->msg='The CAPTCHA Answer is wrong. Please try again.';
	}elseif(Config::isBlank($_POST,'email','pw')){
		$elem->msg='You must fill out all fields';
	}elseif(!Config::checkEmail($_POST['email'],$e)){
		$elem->msg=$e;
	}else{
		require_once 'class.Team.php';
		$t=Config::assocToObjProp($_POST,new Team($config->PDO()));
		if($t->auth(true)){
			$sess->id=$t->id;
			
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
			
			$elem->msg='Log in success. You are redirected to main page.'."<pre>".var_export(SesPrt::check(),true)."</pre>";
			$elem->result=true;
		}else{
			$elem->msg='Log in fail, there is not email or password in database.';
		}
	}
}

if(Config::isAjax()){
	require_once 'class.SKAjax.php';
	$ajax=new SKAjax();
	$ajax->addAction(SKAjax::RELOAD_CAPTCHA);
	$ajax->addHtmlTextVal(SKAjax::SET_HTML,'#msg',$elem->msg);
	if($elem->result) $ajax->addAction(SKAjax::REDIRECT,'./');
	Config::JSON($ajax,true);
}elseif($elem->auth){
	Config::redirect('./',$elem->msg);
}
?>