<?php
require_once 'config.inc.php';
require_once 'class.SesPrt.php';

if(SesPrt::check(false)) Config::redirect('./','You have already logged in');

require_once 'class.Element.php';
$elem=new Element();
if(Config::isPost()) require_once 'login.scr.php';
?>
<!doctype html>
<html><!-- InstanceBegin template="/Templates/IMC_Main.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta charset="utf-8">
<!-- InstanceBeginEditable name="doctitle" -->
<title>Log in :Chiang Mai University International Medical Challenge</title>
<!-- InstanceEndEditable -->
<script src="../jquery-1.11.2.min.js"></script>
<script src="../jquery-migrate-1.2.1.min.js"></script>
<script src="js/skajax.js"></script>
<link rel="stylesheet" href="../imc_main.css">
<!-- InstanceBeginEditable name="head" -->
<script src="js/login.js"></script>
<!-- InstanceEndEditable -->
</head>

<body>
<div id="mainMenu"><ul>
  <li><a href="../index.html" title="Homepage">home</a></li>
  <li><a href="login.php" title="log in">log in</a></li>
  <li><a href="../regist.html" title="registration">registration</a></li></ul></div>
<div id="content"><!-- InstanceBeginEditable name="Content" -->Content
  <form action="login.php" method="post" name="login" id="login">
  <div>
    <label>E-mail<input name="email" type="email" required id="email" value="<?=$elem->val('email')?>" maxlength="127"></label></div>
  <div>
    <label>Password<input name="pw" type="password" id="pw" maxlength="32" required></label></div>
      <? require 'captcha.php'; ?><div>
    <input type="submit" name="submit" id="submit" value="log in">
    <input type="reset" name="cancel" id="cancel" value="cancel"></div>
  </form>
  <div id="msg"><?=$elem->msg?></div>
<!-- InstanceEndEditable --></div>
<div id="footer"><a href="admin.php" title="staff only">staff only</a></div>
</body>
<!-- InstanceEnd --></html>
