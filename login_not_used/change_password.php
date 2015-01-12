<?php
session_start();
require_once "check_session.php";
$sess=checkSession::mustLogIn();

ob_start();
require_once 'result_box.php';
$result=new resultCon(false);
$db=newPDO();
$_SESSION=$sess->updateData($db,$_SESSION,30);

if(count($_POST)<=0)
	$result->result=NULL;
elseif(!checkCAPTCHA())
	echo "คำตอบ (Answer) ไม่ถูกต้อง กรุณาตอบใหม่อีกครั้ง";
elseif(!(strlen($_POST['old_pw'])>0 && strlen($_POST['new_pw'])>0 && strlen($_POST['new_pw_confirmation'])>0))
	echo 'กรุณากรอก "รหัสผ่านเก่า", "รหัสผ่านใหม่" และ "ยืนยันรหัสผ่านใหม่"';
elseif(!preg_match_all('/^[[:alnum:]_:;]{6,32}$/',$_POST['new_pw_confirmation'],$stm))
	echo "Password ต้องประกอบไปด้วย A-Z, a-z, 0-9, semicolon (;), colon (:) หรือ underscore (_) ความยาวรวม 6 - 32 ตัวอักขระเท่านั้น";
elseif($_POST['new_pw']!=$_POST['new_pw_confirmation'])
	echo '"ยืนยันรหัสผ่านใหม่" ไม่ตรงกับ "รหัสผ่านใหม่"';
else try{
	$db->beginTransaction();
	$stm=$db->prepare('UPDATE team_info SET password=:new WHERE password=:old AND id=:id');
	$stm->bindParam(':old',$_POST['old_pw']);
	$stm->bindParam(':new',$_POST['new_pw']);
	$stm->bindParam(':id',$sess->ID);
	$stm->execute();
	if($stm->rowCount()==1){
		echo "เปลี่ยนรหัสผ่านสำเร็จ";
		$result->result=true;
		$db->commit();
	}else{
		echo "ไม่สามารถเปลี่ยนรหัสผ่านได้เนื่องจากรหัสผ่านเก่าไม่ถูกต้อง (code:#{$stm->rowCount()})";
		$result->result=false;
		$db->rollBack();
	}
}catch(Exception $e){
	$db->rollBack();
	echo "\nError! ไม่สามารถบันทึกข้อมูลได้เนื่องจาก\n$e";
	$result->result=false;
}
$result->message=nl2br(ob_get_clean());

if(isset($_GET['ajax'])):
	$json=new jsonAjax();
	$json->setResult($result);
	$json->addAction(jsonAjax::RELOAD_CAPTCHA);
	$json->addHtmlTextVal(jsonAjax::SET_VAL,"input[type=password]","");
	echo $json;
else:
?>
<!doctype html>
<html><!-- InstanceBegin template="/Templates/mahidol_login.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta charset="utf-8">
<!-- InstanceBeginEditable name="doctitle" -->
<title>เปลี่ยน password - Mahidol Quiz 2014: การแข่งขันตอบปัญหาวิทยาศาสตร์สุขภาพ ชิงถ้วยสมเด็จพระเทพรัตนราชสุดา สยามบรมราชกุมารี เนื่องในวันมหิดล ประจำปี พ.ศ. 2557</title>
<!-- InstanceEndEditable -->
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="js/jquery-ui-1.10.4.custom.min.js"></script>
<script src="js/mahidol_ajax.js"></script>
<link rel="stylesheet" href="../mahidol_quiz.css">
<link href="../mahidol.css" rel="stylesheet" type="text/css">

<script src="js/mahidol_ajax.js"></script>
<link href="css/mahidol_quiz.css" rel="stylesheet" />
<link href="css/jquery-ui-1.10.4.custom.css" rel="stylesheet" type="text/css" />
<!-- InstanceBeginEditable name="head" -->
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.1.47/jquery.form-validator.min.js"></script>
<script src="js/change_password.js"></script>
<!-- InstanceEndEditable -->

</head>

<body>
<div id="header"></div>
<div id="menu"></div>
<div id="content">
<div class="content">
<div class="heading">
  <div><!-- InstanceBeginEditable name="headline" -->เปลี่ยน password<img src="../images/signin.png" width="60" height="60" alt="Log in ผู้แข่งขัน"><!-- InstanceEndEditable --></div></div>
<div class="main_content"><!-- InstanceBeginEditable name="main_content" --><form action="change_password.php" method="post" name="changePassword" id="changePassword">
    <fieldset>
      <legend>เปลี่ยน password</legend>
      <p>
        <label for="old_pw">รหัสผ่านเก่า</label>
        <input type="password" name="old_pw" id="old_pw"  size="30" data-validation="length alphanumeric" data-validation-allowing="_:;"  data-validation-length="6-32" required>
      </p>
      <p>
        <label for="new_pw_confirmation">รหัสผ่านใหม่</label>
        <input name="new_pw_confirmation" type="password" required id="new_pw_confirmation" size="30" data-validation="length alphanumeric" data-validation-allowing="_:;"  data-validation-length="6-32">
      </p>
      <p>
        <label for="new_pw">ยืนยันรหัสผ่านใหม่</label>
        <input name="new_pw" type="password" required id="new_pw" size="30" data-validation="confirmation">
      </p>
      <? require 'captcha.php'; ?>
      <p>
        <input type="submit" name="submit" id="submit" value="เปลี่ยนรหัสผ่าน">
        <input type="reset" name="Reset" id="button" value="ยกเลิก">
        <a href="forget.php" title="ลืมรหัสผ่าน" target="_blank">forget password?</a></p>
    </fieldset>
  </form>
  <?=$result->getIfNotNull()?><!-- InstanceEndEditable --></div>
</div>
<div class="sidemenu">
<div class="ui-widget-header center ui-corner-top">ข้อมูลเบื้องต้น</div>
<div class="ui-widget-content">
<div class="center bold">ทีม <?=htmlspecialchars($sess->teamName())?></div>
<div class="center bold">ความคืบหน้า</div>
<div id="progressbox" class="ui-corner-all ui-widget-content"><div id="progressbar" class="center ui-widget-header ui-corner-all"><?=$sess->progression();?>%</div></div>
</div>
  <p class="ui-widget-header ui-corner-top center">ขั้นตอนการรับสมัคร</p>
  <div class="menubox countMenu ui-widget-content">
  <div class="<?=$sess->menuClass(CheckSession::PAGE_TEAM_INFO)?>"><a href="team_info.php" title="กรอกข้อมูลผู้แข่งขัน">กรอกข้อมูลผู้แข่งขัน</a></div>
  <div class="<?=$sess->menuClass(CheckSession::PAGE_UPLOAD_TSP)?>"><a href="upload_photo.php" title="Upload ปพ.1">Upload ปพ.1</a></div>
<div class="<?=$sess->menuClass(CheckSession::PAGE_QUIZ)?>"><a href="coach_info.php" title="ทำข้อสอบรอบพิเศษ">ทำข้อสอบรอบพิเศษ</a></div>
  <div class="<?=$sess->menuClass(CheckSession::PAGE_CONFIRM)?>"><a href="confirm.php" title="ยืนยันข้อมูล">ยืนยันข้อมูล</a></div>
  <div class="<?=$sess->menuClass(CheckSession::PAGE_PAY)?>"><a href="payment.php" title="Upload หลักฐานการโอนเงิน">ส่งหลักฐานการชำระเงิน</a></div>
  <div class="<?=$sess->menuClass(CheckSession::PAGE_RECEIVE_ID)?>"><a href="receive_id.php" title="พิมพ์บัตรประจำตัวผู้แข่งขัน">พิมพ์บัตรประจำตัวผู้แข่งขัน</a></div>
  </div>
  <p class="ui-widget-header ui-corner-top center">หน้าอื่นๆ</p>
  <div class="menubox ui-widget-content">
  <div>&nbsp;&nbsp;<a href="../reg/index.php" title="main">หน้าหลัก</a></div>
  <div>&nbsp;&nbsp;<a href="../mahidol_quiz.html" title="main" target="_blank">รายละเอียดการแข่งขัน</a></div>
  <div>&nbsp;&nbsp;<a href="../index.html" title="main" target="_blank">หน้าหลักเว็บมหิดล</a></div>
  <div>&nbsp;&nbsp;<a href="search.php" title="ค้นหา" target="_blank">ค้นหา</a></div>
  <div>&nbsp;&nbsp;<a href="change_password.php" title="เปลี่ยน password">เปลี่ยน password</a></div>
  <div>&nbsp;&nbsp;<a href="../reg/logout.php" title="log out">log out</a></div>
  </div>
</div></div>
<div id="footer"></div>
</body>
<!-- InstanceEnd --></html><? endif ?>