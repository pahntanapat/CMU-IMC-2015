<?php
require_once 'config.inc.php';
require_once 'class.SesAdm.php';
require_once 'class.Admin.php';
require_once 'class.Element.php';
require_once 'edit_admin.view.php';

$sess=SesAdm::check();
if(!$sess) Config::redirect('admin.php','you are not log in.');
elseif(!$sess->checkPMS(SesAdm::PMS_ADMIN))  Config::redirect('home.php','you don\'t have permission here.');
if(Config::isPost()) require_once 'edit_admin.scr.php';
else{
	$elem=new Element();
	if(isset($_GET['id'])) $elem->form=formAdmin(new Admin($config->PDO()),$_GET['id']);
	else $elem->tb=tableAdmin(new Admin($config->PDO()));
}
?>
<!doctype html>
<html><!-- InstanceBegin template="/Templates/IMC_admin.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta charset="utf-8">
<!-- InstanceBeginEditable name="doctitle" -->
<title>Edit Admin :Chiang Mai University International Medical Challenge</title>
<!-- InstanceEndEditable -->
<script src="../js/jquery-1.11.2.min.js"></script>
<script src="../js/jquery-migrate-1.2.1.min.js"></script>
<script src="js/skajax.js"></script>
<link rel="stylesheet" href="../css/imc_main.css">

<!-- InstanceBeginEditable name="head" -->
<script src="js/edit_admin.js"></script>
<script src="js/ui.js"></script>
<link rel="stylesheet" href="css/ui.css">
<!-- InstanceEndEditable -->

</head>

<body>
<div id="mainMenu"><ul>
  <li><a href="../index.html" title="Homepage">home</a></li>
  <li><a href="login.php" title="log in">log in</a></li>
  <li><a href="../regist.html" title="registration">registration</a></li></ul></div>
<div id="content"><div id="profileBar"><?=$sess->student_id?><br><?=$sess->nickname?>
  <br>  <a href="home.php#editProfile" title="edit profile">Edit profile</a> <a href="home.php#changePassword">Chage password</a> <a href="logout.php?admin" title="Log out">Log out</a>
</div><div id="adminMenu"><ul><li><a href="home.php" title="Admin dashboard">Main page</a></li>
  <li>Edit team's, participants', and Observers' information</li>
  <li>Information confirmation</li>
  <li>Payment confirmation</li>
  <li>Post-registration confirmation</li>
  <li>for General Modulator</li>
  <li><a href="config.php" title="System configuration">System configuration</a></li>
  <li><a href="edit_admin.php" title="Edit administrator">Edit administrator</a></li>
</ul>
</div><div id="adminContent"><!-- InstanceBeginEditable name="adminContent" --><h2>Add/Edit/Delete admin</h2><?php if(isset($_GET['id'])):?>
	<div id="divAdminForm"><?=$elem->form?></div>
	<? else:?><form action="edit_admin.php" method="post" id="adminListForm">
  <div>
    <button type="button" id="selectAll"><span>Select All</span></button>
    <a href="edit_admin.php#reloadAdminList" title="reload" id="reloadAdminList">Reload</a> <a href="edit_admin.php?id=0" title="Add new admin" class="edit">Add</a>
    <input type="submit" name="remove" id="remove" value="remove">
  </div>
  <div id="adminList"><?=$elem->tb?></div></form><? endif;?><!-- InstanceEndEditable --></div></div>
<div id="footer"><a href="admin.php" title="staff only">staff only</a></div>
</body>
<!-- InstanceEnd --></html>
