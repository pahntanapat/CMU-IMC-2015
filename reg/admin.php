<?php
require_once 'config.inc.php';
require_once 'class.SesAdm.php';
require_once 'class.Element.php';

if(SesAdm::check(false)){
	Config::redirect('home.php');
	exit('You have already logged in');
}
$elem=new Element();
if(Config::isPost()) require_once 'admin.scr.php';
?>
<!doctype html>
<html><!-- InstanceBegin template="/Templates/IMC_Main.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta charset="utf-8">
<!-- InstanceBeginEditable name="doctitle" -->
<title>Chiang Mai University International Medical Challenge</title>
<!-- InstanceEndEditable -->
<script src="../jquery-1.11.2.min.js"></script>
<script src="../jquery-migrate-1.2.1.min.js"></script>
<script src="js/skajax.js"></script>
<link rel="stylesheet" href="../imc_main.css">
<!-- InstanceBeginEditable name="head" -->
<script src="js/admin.js"></script>
<!-- InstanceEndEditable -->
</head>

<body>
<div id="mainMenu"><ul>
  <li><a href="../index.html" title="Homepage">home</a></li>
  <li><a href="login.php" title="log in">log in</a></li>
  <li><a href="../regist.html" title="registration">registration</a></li></ul></div>
<div id="content"><!-- InstanceBeginEditable name="Content" --><form action="admin.php" method="post" name="adminLogin" id="adminLogin">
    <fieldset>
      <legend>Log in</legend>
      <div>
        <label for="student_id">Student ID: </label>
        <input name="student_id" type="text" required id="student_id" value="<?=$elem->val('student_id')?>">
      </div>
      <div>
        <label for="password">Password: </label>
        <input type="password" name="password" id="password" required>
      </div>
      <? require 'captcha.php'; ?>
      <div class="btnset"><button type="submit">Log in</button><button type="reset">Clear</button></div>
    </fieldset>
</form>
<div id="msg"><?=$elem->msg?></div><!-- InstanceEndEditable --></div>
<div id="footer"><a href="admin.php" title="staff only">staff only</a></div>
</body>
<!-- InstanceEnd --></html>
