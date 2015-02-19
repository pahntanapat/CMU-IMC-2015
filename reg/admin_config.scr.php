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
		case 'reset':
			$json->message=$config->reset();
			$json->result=$json->message!==false;
			$json->message=$json->result?'Reset system configuration complete; filesize = '.$json->message.' B':'Unable to reset system configuration';
			break;
		case 'save':
			foreach($_POST as $k=>$v)
				if(defined(get_class($config).'::'.$k))
					if(constant('Config::'.$k)!=$v) $config->$k=$v;
					else unset($config->$k);

			$json->message=$config->save();
			$json->result=$json->message!==false;
			$json->message=$json->result?'Save custom system configuration complete; filesize = '.$json->message.' B':'Unable to save custom system configuration';
			break;
	}
}catch(Exception $e){
	$json->message=Config::e($e);
}
if(Config::isAjax()){
	$json->addHtmlTextVal(SKAjax::SET_HTML,'#result',$json->message);
	$json->message='';
	Config::JSON($json,true);
}
?>