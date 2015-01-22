<?php
require_once 'config.inc.php';
require_once 'class.Admin.php';
require_once 'class.SesAdm.php';
require_once 'class.Element.php';
require_once 'edit_admin.view.php';

$sess=SesAdm::check();
if(!$sess) Config::redirect('admin.php','you are not log in.');
elseif(!$sess->checkPMS(SesAdm::PMS_ADMIN))  Config::redirect('home.php','you don\'t have permission here.');

$elem=new Element();

$adm=new Admin($config->PDO());
if(isset($_POST['del'])){ // Delete Admin
	try{
		$adm->beginTransaction();
		$elem->msg='Delete '.$adm->del($_POST['del']).' administrator(s) success';
		$adm->commit();
	}catch(Exception $e){
			$adm->rollBack();
			$elem->msg=Config::e($e);
	}
	$elem->tb=tableAdmin($adm,$elem->msg);
}elseif(isset($_POST['id'])){ // Add or Edit admin
	if(Config::isBlank($_POST,'student_id','nickname','password')){
		$elem->msg='Please fill out all forms.';
	}elseif(!Config::checkPW($_POST['password'],$e)){
		$elem->msg=$e;
	}else{
		try{
			$adm=Config::assocToObjProp($_POST,$adm);
			$adm->beginTransaction();
			if($adm->id==0){
				$elem->msg='Add new admin '.($adm->add()?'complete, #'.$adm->id:'fail');
				if(Config::isAjax()) $adm->id=0;
			}else{
				$elem->msg='Edit admin ('.$adm->student_id.') '.($adm->update()?'complete':'fail');
			}
			$adm->commit();
		}catch(Exception $e){
			$adm->rollBack();
			$elem->msg=Config::e($e);
		}
	}
	$elem->form=formAdmin($adm,false,$elem->msg);
}elseif(isset($_GET['id'])){
	$elem->form=formAdmin($adm,$_GET['id']);
}else{ // Load admin list
	$elem->tb=tableAdmin($adm);
}

if(Config::isAjax()){
	require_once 'class.SKAjax.php';
	$json=new SKAjax();
	if(Config::isPost())
	  $json->addHtmlTextVal(SKAjax::SET_HTML,
		  isset($elem->tb)?'#adminList':'#divAdminForm',
		  isset($elem->tb)?$elem->tb:$elem->form);
	else
		$json->message=$elem->form;
	Config::JSON($json,true);
}
?>