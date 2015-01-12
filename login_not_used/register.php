<?php
//require_once '../config.inc.php';
require_once "check_session.php";
CheckSession::whenLogIn();
require_once 'result_box.php';
$result=new resultCon(false);
$result->addIfisStr(delOldMail());
if(count($_POST)>0)
	require_once 'register.scr.php';

?><!doctype html>
<html><!-- InstanceBegin template="/Templates/mahidol.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta charset="utf-8">
<!-- InstanceBeginEditable name="doctitle" -->
<title>การแข่งขันตอบปัญหาวิทยาศาสตร์สุขภาพ Mahidol Quiz 2014</title>
<!-- InstanceEndEditable -->
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="js/jquery-ui-1.10.4.custom.min.js"></script>
<script src="js/mahidol_ajax.js"></script>
<link rel="stylesheet" href="../mahidol_quiz.css">
<link href="../mahidol.css" rel="stylesheet" type="text/css">

<!-- InstanceBeginEditable name="head" -->
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.1.47/jquery.form-validator.min.js"></script>
<script src="js/register.js" type="application/javascript"></script>
<!-- InstanceEndEditable -->

</head>

<body>
<div id="header"></div>
<div id="menu"></div>
<div id="content"><div id="Heading"><!-- InstanceBeginEditable name="Heading" -->
		        <h1><strong>สมัครเข้าร่วมการแข่งขัน</strong></h1>
		      <!-- InstanceEndEditable --></div><div id="Content"><!-- InstanceBeginEditable name="Content" --><? if(!$result->result){ ?>
  <form action="register.php" method="post" name="reg" class="center" id="reg">
    <fieldset class="left">
      <legend>สมัครเข้าร่วมการแข่งขัน</legend>
<div><label for="email">Email</label><input name="email" type="email" required id="email" value="<?=@$_POST['email']?>" data-validation="email" placeholder="Email" /></div>
<div><label for="password">Password</label><input name="password_confirmation" type="password" id="password_confirmation" data-validation="length alphanumeric" data-validation-allowing="_:;"  data-validation-length="6-32" placeholder="Password" required></div>
<div><label for="ConfirmPW">Confirm password</label><input name="password" type="password" =id="password" data-validation="confirmation" placeholder="Confirm password" required></div>
<div>
  <fieldset>
    <legend>ประเภททีม</legend>
    <input name="type" type="radio" id="type_0" value="0" required>
      <label for="type_0">ทีมโรงเรียน</label>
      <input type="radio" name="type" value="1" id="type_1" required>
     <label for="type_1"> ทีมอิสระ</label>
  </fieldset>
</div>
<? require('captcha.php');?>
<div class="bold">
    <input name="OK" type="checkbox" required id="OK" value="1">
   <label for="OK">ฉันเข้าใจแล้วยอมรับกติการการแข่งขัน Mahidol Quiz</label></div>
<div class="btnset"><input type="submit" name="submit" id="submit" value="ส่งข้อมูล"> <input type="reset" name="reset" id="reset" value="ล้างข้อมูล"></div>
    </fieldset> </form><? } ?>
 <?=$result->getIfNotNull()?><!-- InstanceEndEditable --></div></div>
<div id="footer"></div>
</body>
<!-- InstanceEnd --></html>
