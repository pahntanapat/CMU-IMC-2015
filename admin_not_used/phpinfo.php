<?php
require_once 'config.inc.php';
require_once $_ROOT.'/login/config.inc.php';
require_once 'class.Session.php'; 

if(Session::isLogIn(true,Session::PMS_WEB_MTR)) phpinfo();
?>