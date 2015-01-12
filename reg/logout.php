<?php
ob_start();
require_once 'config.inc.php';
require_once 'class.SesAdm.php';
SesAdm::destroy();
?>
Log out success. You are being redirected to log in page.
<?php
Config::redirect((isset($_GET['admin'])?"admin":"login").".php",ob_get_clean());
?>