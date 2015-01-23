<?php
require_once 'config.inc.php';
require_once 'class.SesAdm.php';

$sess=SesAdm::check();
if(!$sess) Config::redirect('admin.php','you are not log in.');
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
<script src="../reg/js/skajax.js"></script>
<link rel="stylesheet" href="../imc_main.css">
<!-- InstanceBeginEditable name="head" -->
<!-- TemplateBeginEditable name="head" -->
<!-- TemplateEndEditable -->
<!-- InstanceEndEditable -->
</head>

<body>
<div id="mainMenu"><ul>
  <li><a href="../index.html" title="Homepage">home</a></li>
  <li><a href="../reg/login.php" title="log in">log in</a></li>
  <li><a href="../regist.html" title="registration">registration</a></li></ul></div>
<div id="content"><!-- InstanceBeginEditable name="Content" --><div id="profileBar"><?=$sess->student_id?><br><?=$sess->nickname?>
  <br>  <a href="../reg/home.php#editProfile" title="edit profile">Edit profile</a> <a href="../reg/home.php#changePassword">Chage password</a> <a href="../reg/logout.php?admin" title="Log out">Log out</a>
</div><div id="adminMenu"><ul><li><a href="../reg/home.php" title="Admin dashboard">Main page</a></li>
  <li>Edit team's, participants', and Observers' information</li>
  <li>Information confirmation</li>
  <li>Payment confirmation</li>
  <li>Post-registration confirmation</li>
  <li>for General Modulator</li>
  <li><a href="../reg/config.php" title="System configuration">System configuration</a></li>
  <li><a href="../reg/edit_admin.php" title="Edit administrator">Edit administrator</a></li>
</ul>
</div><div id="adminContent"><!-- TemplateBeginEditable name="adminContent" -->adminContent<!-- TemplateEndEditable --></div><!-- InstanceEndEditable --></div>
<div id="footer"><a href="../reg/admin.php" title="staff only">staff only</a></div>
</body>
<!-- InstanceEnd --></html>
