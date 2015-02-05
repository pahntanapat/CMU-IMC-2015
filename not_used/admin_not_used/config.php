<?php
require_once 'config.inc.php';
require_once $_ROOT.'/login/config.inc.php';
require_once 'class.Session.php'; 

$sess=new Session();
if(Session::isLogIn(true,Session::PMS_WEB_MTR,$sess->load()) && isset($_REQUEST['act'])) require 'config.scr.php';
$db=newPDO();
?>
<!doctype html>
<html><!-- InstanceBegin template="/Templates/mahidol_admin.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta charset="utf-8">
<!-- InstanceBeginEditable name="doctitle" -->
<title>ตั้งค่าระบบ - Admin::Mahidol Quiz</title>
<!-- InstanceEndEditable -->
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="../login_not_used/js/jquery-ui-1.10.4.custom.min.js"></script>
<script src="../login_not_used/js/mahidol_ajax.js"></script>
<link rel="stylesheet" href="../mahidol_quiz.css">
<link href="../mahidol.css" rel="stylesheet" type="text/css">

<script src="../login_not_used/js/mahidol_ajax.js"></script>
<link rel="stylesheet" type="text/css" href="admin.css">
<link rel="stylesheet" type="text/css" href="../login_not_used/css/jquery-ui-1.10.4.custom.css">
<!-- InstanceBeginEditable name="head" -->
<script src="config.js"></script>
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
    <li><a href="../../admin/quiz.php" title="ตรวจ Quiz/อนุมัติเข้ารอบ">ตรวจ Quiz/อนุมัติเข้ารอบ</a></li>
    <li><a href="pay.php" title="ตรวจหลักฐานการโอนเงิน">ตรวจหลักฐานการโอนเงิน</a></li>
    <li><a href="team_message.php" title="ส่งข้อความถึงผู้แข่งขัน">ส่งข้อความถึงผู้แข่งขัน</a></li>
    <li><a href="give_sorted_id.php" title="จัดการรหัสผู้แข่งขันและห้องสอบ">จัดการรหัสผู้แข่งขันและห้องสอบ</a></li>
    <li><a href="logout.php" title="Log out">log out</a></li>
  </ul>
</nav>
<div class="content">
<div class="heading">
  <div><!-- InstanceBeginEditable name="headline" -->ตั้งค่าระบบ<img src="../../images/act.png" width="60" height="60" alt="Admin"><!-- InstanceEndEditable --></div></div>
<div class="mainContent"><!-- InstanceBeginEditable name="main_content" -->
<?
$conf=getConfig();
$orgn=getConfig(true);
?>
  <form action="config.scr.php?act=save" method="post" name="form1" target="_blank" id="form1">
    <fieldset class="left">
      <legend>ตั้งค่าระบบ</legend>
      <div id="tabs">
      <ul>
        <li><a href="#sch">Schedule</a></li>
        <li><a href="#lh">Location &amp; Host</a></li>
        <li><a href="#db">Database</a></li>
        <li><a href="#m">Mail</a></li>
      </ul>
      <div id="lh">
      <div>
        <label for="ROOT">Root folder: </label>
        <input name="ROOT" type="text" id="ROOT" value="<?=$conf['ROOT']?>">
      </div>
      <div>
        <label for="HOST">URL to /login/: </label>
        <input type="text" name="PATH" id="PATH"  value="<?=$conf['PATH']?>">
      </div>
      </div><div id="sch">
      <div>
        <label for="END_REGISTER">ปิดรับสมัคร: </label>
        <input type="text" name="END_REGISTER" id="END_REGISTER" value="<?=$conf['END_REGISTER']?>">
      </div>
      <div>
        <label for="END_EDIT_INFO">หมดเขตแก้ไขข้อมูล: </label>
        <input type="text" name="END_EDIT_INFO" id="END_EDIT_INFO"  value="<?=$conf['END_EDIT_INFO']?>">
      </div>
      <div>
        <label for="START_PAY">เริ่มจ่ายเงิน: </label>
        <input type="text" name="START_PAY" id="START_PAY" value="<?=$conf['START_PAY']?>">
      </div>
      <div>
        <label for="END_PAY">หมดเขตจ่ายเงิน: </label>
        <input type="text" name="END_PAY" id="END_PAY"  value="<?=$conf['END_PAY']?>">
      </div>
      <div>
        <label for="START_PRINT">รับรหัสประจำตัว: </label>
        <input type="text" name="START_PRINT" id="START_PRINT" value="<?=$conf['START_PRINT']?>">
      </div>
      </div><div id="db">
      <div>
        <label for="DB_HOST">DB Host: </label>
        <input type="text" name="DB_HOST" id="DB_HOST" value="<?=$conf['DB_HOST']?>">
      </div>
      <div>
        <label for="DB_NAME">Database: </label>
        <input type="text" name="DB_NAME" id="DB_NAME"  value="<?=$conf['DB_NAME']?>">
      </div>
      <div>
        <label for="DB_USERNAME">Username: </label>
        <input type="text" name="DB_USERNAME" id="DB_USERNAME"  value="<?=$conf['DB_USERNAME']?>"></div>
      <div><label for="DB_PW">Password: </label>
        <input type="text" name="DB_PW" id="DB_PW" value="<?=$conf['DB_PW']?>"></div>
      </div><div id="m">
      <div>
        <label for="SMTP_HOST">SMTP host: </label>
        <input type="text" name="SMTP_HOST" id="SMTP_HOST" value="<?=$conf['SMTP_HOST']?>"></div>
      <div>
        <label for="SMTP_PORT">SMTP port: </label>
        <input type="text" name="SMTP_PORT" id="SMTP_PORT" value="<?=$conf['SMTP_PORT']?>"></div>
        <div>
          <label for="SMTP_SECR">SMTP Encrytion:</label>
          <input name="SMTP_SECR" type="text" id="SMTP_SECR" value="<?=$conf['SMTP_SECR']?>">
        </div>
      <div>
        <label for="DB_PW">SMTP username: </label>
        <input type="text" name="SMTP_USER" id="SMTP_USER" value="<?=$conf['SMTP_USER']?>"></div>
      <div>
        <label for="SMTP_PW">SMTP password: </label>
        <input type="text" name="SMTP_PW" id="SMTP_PW" value="<?=$conf['SMTP_PW']?>"></div>
      <div>
        <label for="MAIL_FROM">Mail from: </label>
        <input type="text" name="MAIL_FROM" id="MAIL_FROM" value="<?=$conf['MAIL_FROM']?>"></div>
      <div>
        <label for="MAIL_REPLY_TO">Reply email to: </label>
        <input type="text" name="MAIL_REPLY_TO" id="MAIL_REPLY_TO"  value="<?=$conf['MAIL_REPLY_TO']?>"></div>
      </div></div>
      <div class="btnset"><button type="submit">บันทึก</button><button type="submit">ยกเลิก</button>
        <a href="config.scr.php?act=reset" title="reset" target="_blank" class="reset">      Reset
        </a></div>
    </fieldset>
  </form>
  <div id="result"></div>
  <form action="config.scr.php?act=mail" method="post" name="form2" target="_blank" id="form2">
    <fieldset>
      <legend>ทดสอบ Mail Function</legend>
      <div>
        <label for="to">To: </label>
        <input type="email" name="to" id="to" placeholder="Mail to" required>
      </div>
      <div>
        <label for="subject">Subject: </label>
        <input type="text" name="subject" id="subject" placeholder="subject" required>
      </div>
      <div>
        <label for="msg">Message: </label><br>
        <textarea name="msg" cols="80%" rows="8" id="msg"></textarea>
      </div>
      <div>
        <label for="func">Function: </label>
        <select name="func" id="func">
          <option value="-1" selected>Force</option>
          <option value="2">SMTP</option>
          <option value="1">SendMail</option>
          <option value="0">Mail</option>
        </select>
      </div>
      <div class="btnset"><button type="submit">ส่ง Email</button><button type="reset">ยกเลิก</button></div>
    </fieldset>
  </form>
  <h3><a href="phpinfo.php" title="PHP Info" target="_blank">PHP Info</a> <?=phpversion()?></h3>
<!-- InstanceEndEditable --></div>
</div></div>
<div id="footer"></div>
</body>
<!-- InstanceEnd --></html>
