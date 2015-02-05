<?php
require_once 'config.inc.php';
require_once 'class.SesPrt.php';

$s=SesPrt::check(true,true);
if(!$s) Config::redirect('login.php','You do not log in. Please log in.');

require_once 'class.Element.php';
require_once 'class.State.php';
require_once 'class.Team.php';
$elem=new Element();
$elem->result=false;
if(!Config::isPost()){
}elseif(Config::isBlank($_POST,'team_name','institution','university','country')){
	$elem->msg='Team\'s name, Institution, University, and Country must not be blank.';
}elseif(strlen($_POST['team_name'])>30){
	$elem->msg='Team\'s name must contain only 1 - 30 characters.';
}else{
	try{
		$t=Config::assocToObjProp($_POST,new Team($config->PDO()));
		$t->id=$s->id;
		$t->beginTransaction();
		
		if($t->updateInfo()){
			$elem->msg='Update Team\'s information sucess.';
			$elem->result=true;
			$s->changeID(true);
		}else{
			$elem->msg='No information changed.';
		}
		$t->commit();
	}catch(Exception $e){
		$elem->result=false;
		$t->rollBack();
		$elem->msg.=Config::e($e);
	}
}

if(Config::isAjax()){
	require_once 'class.SKAjax.php';
	$json=new SKAjax();
	$json->result=$elem->result;
	$json->message=$elem->msg;
	$json->addHtmlTextVal(SKAjax::SET_HTML,'#msg',$elem->msg);
	if($elem->result){
		foreach($_POST as $k=>$v)
			$json->addHtmlTextVal(SKAjax::SET_VAL,'input[name=\''.$k.'\']',$v);
	}
	Config::JSON($json,true);
}
?>