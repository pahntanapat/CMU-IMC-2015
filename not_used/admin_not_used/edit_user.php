<?php
require_once 'config.inc.php';
require_once $_ROOT.'/login/config.inc.php';
require_once 'class.Session.php'; 
$sess=new Session();
$db=newPDO();
if(Session::isLogIn(true,Session::PMS_STUDENT,$sess) && (isset($_REQUEST['act'])||isset($_REQUEST['reload'])))
	require_once 'edit_user.scr.php';
?>
<!doctype html>
<html><!-- InstanceBegin template="/Templates/mahidol_admin.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta charset="utf-8">
<!-- InstanceBeginEditable name="doctitle" -->
<title>จัดการผู้แข่งขัน - Admin::Mahidol Quiz</title>
<!-- InstanceEndEditable -->
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="login_not_used/js/jquery-ui-1.10.4.custom.min.js"></script>
<script src="login_not_used/js/mahidol_ajax.js"></script>
<link rel="stylesheet" href="mahidol_quiz.css">
<link href="mahidol.css" rel="stylesheet" type="text/css">

<script src="../login_not_used/js/mahidol_ajax.js"></script>
<link rel="stylesheet" type="text/css" href="admin.css">
<link rel="stylesheet" type="text/css" href="../login_not_used/css/jquery-ui-1.10.4.custom.css">
<!-- InstanceBeginEditable name="head" -->
<script src="edit_user.js"></script>
<!-- InstanceEndEditable -->

</head>

<body>
<div id="header"></div>
<div id="menu"></div>
<div id="content"><nav>
  <ul class="quiz_bar">
    <li><a href="../../index.html" title="หน้าแรกเว็บมหิดล">หน้าแรกเว็บมหิดล</a></li>
    <li><a href="../../mahidol_quiz.html" title="หน้ารายละเอียดการแข่งขัน">หน้ารายละเอียดการแข่งขัน</a></li>
    <li><a href="../login_not_used/search.php" title="ค้นหาใน Mahidol Quiz" target="_blank">Search</a></li>
    <li><a href="index.php" title="หน้าหลัก Admin">หน้าหลัก Admin</a></li>
    <li><a href="admin_edit.php" title="แก้ไขข้อมูลส่วนตัว">แก้ไขข้อมูลส่วนตัว</a></li>
    <li><a href="admin.php" title="จัดการ admin">จัดการ admin</a></li>
    <li><a href="config.php" title="ตั้งค่าระบบ">ตั้งค่าระบบ</a></li>
    <li><a href="edit_user.php" title="จัดการผู้แข่งขัน">จัดการผู้แข่งขัน</a></li>
    <li><a href="coach.php" title="ตรวจ Quiz/อนุมัติเข้ารอบ">ตรวจ Quiz/อนุมัติเข้ารอบ</a></li>
    <li><a href="pay.php" title="ตรวจหลักฐานการโอนเงิน">ตรวจหลักฐานการโอนเงิน</a></li>
    <li><a href="team_message.php" title="ส่งข้อความถึงผู้แข่งขัน">ส่งข้อความถึงผู้แข่งขัน</a></li>
    <li><a href="give_sorted_id.php" title="จัดการรหัสผู้แข่งขันและห้องสอบ">จัดการรหัสผู้แข่งขันและห้องสอบ</a></li>
    <li><a href="logout.php" title="Log out">log out</a></li>
  </ul>
</nav>
<div class="content">
<div class="heading">
  <div><!-- InstanceBeginEditable name="headline" -->จัดการผู้แข่งขัน<img src="../../images/signin.png" width="60" height="60" alt="Log in ผู้แข่งขัน"><!-- InstanceEndEditable --></div></div>
<div class="mainContent"><!-- InstanceBeginEditable name="main_content" --><? $_REQUEST['reload']=true; include 'edit_user.scr.php';?>
<!-- InstanceEndEditable --></div>
</div></div>
<div id="footer"></div>
</body>
<!-- InstanceEnd --></html>