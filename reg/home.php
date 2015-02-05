<?php
require_once 'config.inc.php';
require_once 'class.SesAdm.php';
require_once 'class.Element.php';

$sess=SesAdm::check();
if(!$sess) Config::redirect('admin.php','you are not log in.');

$elem=new Element();
if(Config::isPost()) require_once 'home.scr.php';
?>
<!doctype html>
<html><!-- InstanceBegin template="/Templates/IMC_admin.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta charset="utf-8">
<!-- InstanceBeginEditable name="doctitle" -->
<title>Admin dashboard :Chiang Mai University International Medical Challenge</title>
<!-- InstanceEndEditable -->
<script src="../js/jquery-1.11.2.min.js"></script>
<script src="../js/jquery-migrate-1.2.1.min.js"></script>
<script src="js/skajax.js"></script>
<link rel="stylesheet" href="../css/imc_main.css">

<!-- InstanceBeginEditable name="head" -->
<script src="js/change_pw.js"></script>
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
</div><div id="adminContent"><!-- InstanceBeginEditable name="adminContent" -->
<div><?=SesAdm::checkbox($sess->pms)?></div>
  <form action="home.php" method="post" name="editProfile" id="editProfile" data-action="home.scr.php">
    <fieldset>
      <legend>Edit profile</legend>
     <div>
       <label for="student_id">Student ID</label>
       <input name="student_id" type="text" id="student_id" value="<?=$elem->val('student_id',$sess->student_id)?>">
     </div>
     <div>
       <label for="nickname">Nickname</label>
       <input name="nickname" type="text" id="nickname" value="<?=$elem->val('nickname',$sess->nickname)?>">
     </div>
     <div>
       <input name="saveEP" type="submit" id="saveEP" value="Save">
     <input type="reset" name="resetEP" id="resetEP" value="Reset">
     </div>
    </fieldset>
    <div id="msgEP"><?=$elem->msgEP?></div>
      </form>

  <form action="home.php" method="post" name="changePassword" id="changePassword" data-action="home.scr.php">
    <fieldset>
      <legend>Change password</legend>
      <div>
        <label for="oldPassword">old password</label>
        <input type="password" name="oldPassword" id="oldPassword">
      </div>
      <div>
        <label for="password">new password</label>
        <input type="password" name="password" id="password">
      </div>
      <div>
        <label for="cfPW">confirm password</label>
        <input type="password" name="cfPW" id="cfPW">
      </div>
      <div>
       <input name="savePW" type="submit" id="savePW" value="Save">
     <input type="reset" name="resetPW" id="resetPW" value="Reset"></div>
    </fieldset>
    <div id="msgCP"><?=$elem->msgCP?></div>
  </form>
<!-- InstanceEndEditable --></div></div>
<div id="footer"><a href="admin.php" title="staff only">staff only</a></div>
</body>
<!-- InstanceEnd --></html>
