<?php
require_once 'config.inc.php';
require_once 'class.SesAdm.php';
require_once 'class.Element.php';
require_once 'class.Admin.php';

$sess=SesAdm::check();
$elem=new Element();
$elem->success=false;
if(!$sess) Config::redirect('admin.php','you are not log in.');
elseif(isset($_POST['oldPassword'])){ //Change password
	if(Config::isBlank($_POST,'password','cfPW','oldPassword')){
		$elem->msgCP='Password must not leave blank.';
	}else	if($_POST['password']!=$_POST['cfPW']){
		$elem->msgCP='Confirm password must same new password!';
	}elseif(!Config::checkPW($_POST['cfPW'],$adm)){
		$elem->msgCP=$adm;
	}else{
		try{
			$adm=new Admin($config->PDO());
			$adm->beginTransaction();
			$adm->id=$sess->id;
			$adm->password=$_POST['password'];
			$elem->success=$adm->changePW($_POST['oldPassword']);
			
			$elem->msgCP='Change password '.($elem->success?' success':'fail');
			$adm->commit();
		}catch(Exception $e){
			$adm->rollBack();
			$elem->success=false;
			$elem->msgCP=Config::e($e);
		}
	}
}else{ // edit profile
	if(Config::isBlank($_POST,'student_id','nickname')){
		$elem->msgEP='Please fill out both Student ID and Nickname.';
	}else{
		try{
			$adm=Config::assocToObjProp($_POST,new Admin($config->PDO()));
			$adm->beginTransaction();
			$adm->id=$sess->id;
			$elem->success=$adm->updateInfo();
			
			$elem->msgEP='Change profile '.($elem->success?' success':'fail');
			$adm->commit();
		}catch(Exception $e){
			$adm->rollBack();
			$elem->success=false;
			$elem->msgEP=Config::e($e);
		}
	}
}

if(Config::isAjax()){
	require_once 'class.SKAjax.php';
	$json=new SKAjax();
	$json->addHtmlTextVal(SKAjax::SET_HTML,'#'.(isset($elem->msgCP)?'msgCP':'msgEP'),(isset($elem->msgCP)?$elem->msgCP:$elem->msgEP));
	$json->addHtmlTextVal(SKAjax::SET_VAL,':password','');
	Config::JSON($json,true);
}
?>