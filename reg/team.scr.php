<?php
require_once 'config.inc.php';
require_once 'class.SesPrt.php';

$s=SesPrt::check(true,true);
if(!$s) Config::redirect('login.php','You do not log in. Please log in.');

require_once 'class.SKAjaxReg.php';
require_once 'class.Team.php';
$ajax=new SKAjaxReg();
$ajax->result=false;

if(!Config::isPost()){
}elseif(!(State::is($s->teamState,State::ST_EDITABLE) && strtotime($config->REG_START_REG)<=time() && time()<strtotime($config->REG_END_REG))){
	$ajax->message='You have no permission to change the information. Please contact adminstrator.';
}elseif(Config::isBlank($_POST,'team_name','institution','university','country')){
	$ajax->message='Team\'s name, Institution, University, and Country must not be blank.';
}elseif(strlen($_POST['team_name'])>30){
	$ajax->message='Team\'s name must contain only 1 - 30 characters.';
}else{
	try{
		$t=Config::assocToObjProp(
			Config::trimArray($_POST),
			new Team($config->PDO())
		);
		$t->id=$s->id;
		$t->beginTransaction();
		
		if($t->updateInfo()){
			$ajax->message='Update Team\'s information sucess.';
			$ajax->result=true;
			$ajax->updateMenuState($s->changeID(true));
			$ajax->setFormDefault();
		}else{
			$ajax->message='No information changed.';
		}
		$t->commit();
	}catch(Exception $e){
		$ajax->result=false;
		$t->rollBack();
		$ajax->msg.=Config::e($e);
	}
}

if(Config::isAjax()) Config::JSON($ajax,true);

?>