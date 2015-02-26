<?php
require_once 'config.inc.php';
require_once 'class.SesAdm.php';

$sess=SesAdm::check();
if(!$sess) Config::redirect('admin.php','you are not log in.');
if(!$sess->checkPMS(SesAdm::PMS_AUDIT|SesAdm::PMS_PARTC|SesAdm::PMS_GM)) Config::redirect('home.php','you don\'t have permission here.');

require_once 'class.SKAjax.php';
$ajax=new SKAjax();
$db=$config->PDO();

//process
require_once 'admin.participant.view.php';
switch(@$_REQUEST['view']){
	case 'team': $ajax->message=teamTable($db, $_REQUEST['order']); break;
	case 'part': $ajax->message=partTable($db); break;
	case 'obs': $ajax->message=obsTable($db, $_REQUEST['distinct']); break;
	default: $ajax->message=summarize($db); break;
}

//toJSON
if(Config::isAjax()) Config::JSON($ajax);
//toPrint mode
if(isset($_GET['print'])){
	Config::HTML();
	exit(printMode(@$_REQUEST['view'], $ajax->toMsg()));
}
?>