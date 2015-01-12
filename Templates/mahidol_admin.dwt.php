<?php
require_once 'config.inc.php';
require_once $_ROOT.'/login/config.inc.php';
require_once 'class.Session.php'; 
require_once $_ROOT.'/login/upload_img_excp.php';
header("Content-Type: text/html; charset=utf-8");
$sess=new Session();
$sess->load();
$db=newPDO();
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
<script src="../login_not_used/js/jquery-ui-1.10.4.custom.min.js"></script>
<script src="../login_not_used/js/mahidol_ajax.js"></script>
<link rel="stylesheet" href="../mahidol_quiz.css">
<link href="../mahidol.css" rel="stylesheet" type="text/css">
<!-- InstanceBeginEditable name="head" -->
<script src="../login_not_used/js/mahidol_ajax.js"></script>
<link rel="stylesheet" type="text/css" href="../admin_not_used/admin.css">
<link rel="stylesheet" type="text/css" href="../login_not_used/css/jquery-ui-1.10.4.custom.css">
<!-- TemplateBeginEditable name="head" -->
<!-- TemplateEndEditable -->
<!-- InstanceEndEditable -->
</head>

<body>
<div id="header"></div>
<div id="menu"></div>
<div id="content"><!-- InstanceBeginEditable name="Body" --><nav>
  <ul class="quiz_bar">
    <li><a href="../index.html" title="หน้าแรกเว็บมหิดล">หน้าแรกเว็บมหิดล</a></li>
    <li><a href="../mahidol_quiz.html" title="หน้ารายละเอียดการแข่งขัน">หน้ารายละเอียดการแข่งขัน</a></li>
    <li><a href="../login_not_used/search.php" title="ค้นหาใน Mahidol Quiz" target="_blank">Search</a></li>
    <li><a href="../admin_not_used/index.php" title="หน้าหลัก Admin">หน้าหลัก Admin</a></li>
    <li><a href="../admin_not_used/admin_edit.php" title="แก้ไขข้อมูลส่วนตัว">แก้ไขข้อมูลส่วนตัว</a></li>
    <li><a href="../admin_not_used/admin.php" title="จัดการ admin">จัดการ admin</a></li>
    <li><a href="../admin_not_used/config.php" title="ตั้งค่าระบบ">ตั้งค่าระบบ</a></li>
    <li><a href="../admin_not_used/edit_user.php" title="จัดการผู้แข่งขัน">จัดการผู้แข่งขัน</a></li>
    <li><a href="../admin_not_used/coach.php" title="ตรวจ Quiz/อนุมัติเข้ารอบ">ตรวจ Quiz/อนุมัติเข้ารอบ</a></li>
    <li><a href="../admin_not_used/pay.php" title="ตรวจหลักฐานการโอนเงิน">ตรวจหลักฐานการโอนเงิน</a></li>
    <li><a href="../admin_not_used/team_message.php" title="ส่งข้อความถึงผู้แข่งขัน">ส่งข้อความถึงผู้แข่งขัน</a></li>
    <li><a href="../admin_not_used/give_sorted_id.php" title="จัดการรหัสผู้แข่งขันและห้องสอบ">จัดการรหัสผู้แข่งขันและห้องสอบ</a></li>
    <li><a href="../admin_not_used/logout.php" title="Log out">log out</a></li>
  </ul>
</nav>
<div class="content">
<div class="heading">
  <div><!-- TemplateBeginEditable name="headline" -->headline<!-- TemplateEndEditable --></div></div>
<div class="mainContent"><!-- TemplateBeginEditable name="main_content" -->mainContent<!-- TemplateEndEditable --></div>
</div><!-- InstanceEndEditable --></div>
<div id="footer"></div>
</body>
<!-- InstanceEnd --></html>
