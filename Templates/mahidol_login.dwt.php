<?php
session_start();
require_once "check_session.php";
$sess=checkSession::mustLogIn();

require_once 'result_box.php';
$db=newPDO();
$_SESSION=$sess->updateData($db,$_SESSION);
?>
<!doctype html>
<html><!-- InstanceBegin template="/reg/mahidol_abstract.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta charset="utf-8">
<!-- InstanceBeginEditable name="doctitle" -->
<title>การแข่งขันตอบปัญหาวิทยาศาสตร์สุขภาพ Mahidol Quiz 2014</title>
<!-- InstanceEndEditable -->
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="../not_used/login_not_used/js/jquery-ui-1.10.4.custom.min.js"></script>
<script src="../not_used/login_not_used/js/mahidol_ajax.js"></script>
<link rel="stylesheet" href="../not_used/mahidol_quiz.css">
<link href="../not_used/mahidol.css" rel="stylesheet" type="text/css">
<!-- InstanceBeginEditable name="head" -->
<script src="../not_used/login_not_used/js/mahidol_ajax.js"></script>
<link href="../not_used/login_not_used/css/mahidol_quiz.css" rel="stylesheet" />
<link href="../not_used/login_not_used/css/jquery-ui-1.10.4.custom.css" rel="stylesheet" type="text/css" />
<!-- TemplateBeginEditable name="head" -->
<!-- TemplateEndEditable -->
<!-- InstanceEndEditable -->
</head>

<body>
<div id="header"></div>
<div id="menu"></div>
<div id="content"><!-- InstanceBeginEditable name="Body" -->
<div class="content">
<div class="heading">
  <div><!-- TemplateBeginEditable name="headline" -->headline<!-- TemplateEndEditable --></div></div>
<div class="main_content"><!-- TemplateBeginEditable name="main_content" -->main_content<!-- TemplateEndEditable --></div>
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
  <div class="<?=$sess->menuClass(CheckSession::PAGE_TEAM_INFO)?>"><a href="../not_used/login_not_used/team_info.php" title="กรอกข้อมูลผู้แข่งขัน">กรอกข้อมูลผู้แข่งขัน</a></div>
  <div class="<?=$sess->menuClass(CheckSession::PAGE_UPLOAD_TSP)?>"><a href="../not_used/login_not_used/upload_photo.php" title="Upload ปพ.1">Upload ปพ.1</a></div>
<div class="<?=$sess->menuClass(CheckSession::PAGE_QUIZ)?>"><a href="../not_used/login_not_used/coach_info.php" title="ทำข้อสอบรอบพิเศษ">ทำข้อสอบรอบพิเศษ</a></div>
  <div class="<?=$sess->menuClass(CheckSession::PAGE_CONFIRM)?>"><a href="../not_used/login_not_used/confirm.php" title="ยืนยันข้อมูล">ยืนยันข้อมูล</a></div>
  <div class="<?=$sess->menuClass(CheckSession::PAGE_PAY)?>"><a href="../not_used/login_not_used/payment.php" title="Upload หลักฐานการโอนเงิน">ส่งหลักฐานการชำระเงิน</a></div>
  <div class="<?=$sess->menuClass(CheckSession::PAGE_RECEIVE_ID)?>"><a href="../not_used/login_not_used/receive_id.php" title="พิมพ์บัตรประจำตัวผู้แข่งขัน">พิมพ์บัตรประจำตัวผู้แข่งขัน</a></div>
  </div>
  <p class="ui-widget-header ui-corner-top center">หน้าอื่นๆ</p>
  <div class="menubox ui-widget-content">
  <div>&nbsp;&nbsp;<a href="../reg/index.php" title="main">หน้าหลัก</a></div>
  <div>&nbsp;&nbsp;<a href="../mahidol_quiz.html" title="main" target="_blank">รายละเอียดการแข่งขัน</a></div>
  <div>&nbsp;&nbsp;<a href="../index.html" title="main" target="_blank">หน้าหลักเว็บมหิดล</a></div>
  <div>&nbsp;&nbsp;<a href="../not_used/login_not_used/search.php" title="ค้นหา" target="_blank">ค้นหา</a></div>
  <div>&nbsp;&nbsp;<a href="../not_used/login_not_used/change_password.php" title="เปลี่ยน password">เปลี่ยน password</a></div>
  <div>&nbsp;&nbsp;<a href="../reg/logout.php" title="log out">log out</a></div>
  </div>
</div><!-- InstanceEndEditable --></div>
<div id="footer"></div>
</body>
<!-- InstanceEnd --></html>
