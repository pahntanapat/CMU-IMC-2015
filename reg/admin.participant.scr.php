<?php
require_once 'config.inc.php';
require_once 'class.SesAdm.php';

$sess=SesAdm::check();
if(!$sess) Config::redirect('admin.php','you are not log in.');
if(!$sess->checkPMS(SesAdm::PMS_AUDIT|SesAdm::PMS_PARTC|SesAdm::PMS_GM)) Config::redirect('home.php','you don\'t have permission here.');
//process

//toJSON

//toPrint mode
?>