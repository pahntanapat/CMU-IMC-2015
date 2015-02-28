<?php
require_once 'config.inc.php';
require_once 'class.SesAdm.php';
require_once 'class.SKAjax.php';

$sess=SesAdm::check();
if(!$sess) Config::redirect('admin.php','you are not log in.');
elseif(!$sess->checkPMS(SesAdm::PMS_WEB)) Config::redirect('home.php','you don\'t have permission here.');

$json=new SKAjax();
$json->result=false;

try{
	switch(@$_GET['act']){
		case 'rdb':
			require_once 'class.Team.php';
			require_once 'class.Admin.php';
			require_once 'class.Member.php';
			require_once 'class.UploadImage.php';
			$db=$config->PDO();
			$db->beginTransaction();
			$tmp=new Admin($db);
			$tmp->reset();
			
			$tmp=new Team($db);
			$tmp->reset();
			
			$tmp=new Participant($db);
			$tmp->reset();
			
			$tmp=new Observer($db);
			$tmp->reset();
			
			$tmp=new UploadImage();
			$tmp->reset();
			
			$json->result=true;
			$json->message='Reset Database and Uploaded image directory complete.';
			$db->commit();
			break;
		case 'rc':
			$json->message=$config->reset();
			$json->result=$json->message!==false;
			$json->message=$json->result?'Reset system configuration complete; filesize = '.$json->message.' B':'Unable to reset system configuration';
			break;
		case 'save':
			require_once 'class.UploadImage.php';
			$UPLOAD_FOLDER=UploadImage::rootFolder();
			
			foreach($_POST as $k=>$v){
				$v=trim($v);
				if(defined(get_class($config).'::'.$k)){
					if(constant(get_class($config).'::'.$k)!=$v) $config->$k=$v;
					else unset($config->$k);
				}
			}

			$json->message=$config->save();
			$json->result=$json->message!==false;
			$json->message=$json->result?'Save custom system configuration complete; filesize = '.$json->message.' B':'Unable to save custom system configuration';
			if($json->result && $UPLOAD_FOLDER!=UploadImage::rootFolder()){
				$json->message=' and move uploaded directory '.(rename($UPLOAD_FOLDER,UploadImage::rootFolder())?'successfully':' fail').'.';
			}
			break;
	}
}catch(Exception $e){
	if(isset($db))
		if($db->inTransaction()) $db->rollBack();
	$json->message=Config::e($e);
}
if(Config::isAjax()){
	$json->addHtmlTextVal(SKAjax::SET_HTML,'#result',$json->message);
	$json->message='';
	Config::JSON($json);
}
?>