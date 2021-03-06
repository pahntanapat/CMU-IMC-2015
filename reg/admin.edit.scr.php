<?php
require_once 'config.inc.php';
require_once 'class.SesAdm.php';

$sess=SesAdm::check();
if(!$sess) Config::redirect('admin.php','you are not log in.');
elseif(!$sess->checkPMS(SesAdm::PMS_ADMIN))  Config::redirect('home.php','you don\'t have permission here.');

require_once 'class.SKAjax.php';
$ajax=new SKAjax();

require_once 'admin.edit.view.php';
$adm=new Admin($config->PDO());
if(isset($_POST['del'])){ // Delete Admin
	$ajax->msgID="adminList";
	try{
		$adm->beginTransaction();
		$ajax->message='Successfully delete '.$adm->del($_POST['del']).' administrator(s)';
		$ajax->result=true;
		$adm->commit();
	}catch(Exception $e){
			$adm->rollBack();
			$ajax->message=Config::e($e);
			$ajax->result=false;
	}
	$ajax->message=tableAdmin($adm,$ajax->message);
}elseif(isset($_POST['id'])){ // Add or Edit admin
	$ajax->msgID=Config::isAjax()?'msgForm':"divAdminForm";
	if(Config::isBlank($_POST,'student_id','nickname','password')){
		$ajax->message='Please fill out all forms.';
	}elseif(!Config::checkPW($_POST['password'],$e)){
		$ajax->message=$e;
	}else{
		try{
			$adm=Config::assocToObjProp(Config::trimArray($_POST),$adm);
			$adm->beginTransaction();
			if($adm->id==0){
				$ajax->message='Add new admin '.($adm->add()?'complete, #'.$adm->id:'fail');
				if(Config::isAjax()){
					$adm->id=0;
					$ajax->addAction(SKAjax::RESET_FORM);
				}
			}else{
				$ajax->message='Edit admin ('.$adm->student_id.') '.($adm->update()?'complete':'fail');
				if(Config::isAjax()) $ajax->setFormDefault($_POST);
			}
			$ajax->result=true;
			$adm->commit();
		}catch(Exception $e){
			$adm->rollBack();
			$ajax->message=Config::e($e);
			$ajax->result=false;
		}
	}
	if(!Config::isAjax()) $ajax->message=formAdmin($adm,false,$ajax->message);
}elseif(isset($_GET['id'])){
	$ajax->msgID="divAdminForm";
	$ajax->message=formAdmin($adm,$_GET['id']);
	if(Config::isAjax()) $ajax->addAction(SKAjax::EVALUTE,'$.addDialog(\''.$ajax->msgID.'\');');
}else{ // Load admin list
	$ajax->msgID="adminList";
	$ajax->message=tableAdmin($adm);
}

if(Config::isAjax()) Config::JSON($ajax);
?>