<?php
require_once 'config.inc.php';
require_once 'class.SesAdm.php';
require_once 'class.SKAjax.php';

$sess=SesAdm::check();
if(!$sess) Config::redirect('admin.php','you are not log in.');
elseif(!$sess->checkPMS(SesAdm::PMS_WEB)) Config::redirect('home.php','you don\'t have permission here.');
$json=new SKAjax();
if(isset($_GET['act'])) require_once 'config.scr.php';
?>
<!doctype html>
<html><!-- InstanceBegin template="/Templates/IMC_admin.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta charset="utf-8">
<!-- InstanceBeginEditable name="doctitle" -->
<title>System Configuration :Chiang Mai University International Medical Challenge</title>
<!-- InstanceEndEditable -->
<script src="../jquery-1.11.2.min.js"></script>
<script src="../jquery-migrate-1.2.1.min.js"></script>
<script src="js/skajax.js"></script>
<link rel="stylesheet" href="../imc_main.css">

<!-- InstanceBeginEditable name="head" -->
<script src="js/ui.js"></script>
<script src="js/config.js"></script>
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
  <li>Team's &amp; participants' information</li>
  <li>Observers' information</li>
  <li>Payment confirmation</li>
  <li>for General Modulator</li>
  <li><a href="config.php" title="System configuration">System configuration</a></li>
  <li><a href="edit_admin.php" title="Edit administrator">Edit administrator</a></li>
</ul>
</div><div id="adminContent"><!-- InstanceBeginEditable name="adminContent" -->
 <form action="config.php?act=save" method="post">
    <fieldset class="left">
      <legend>ตั้งค่าระบบ</legend>
      <div id="tabs">
      <ul>
        <li><a href="#sch">Schedule</a></li>
        <li><a href="#regCf">Register</a></li>
        <li><a href="#db">Database</a></li>
      </ul>
      <div id="sch">
      <div>
        <label for="REG_START_REG">เปิดรับสมัคร: </label>
        <input type="text" name="REG_START_REG" id="REG_START_REG" value="<?=$config->REG_START_REG?>" required>
      </div>
      <div>
        <label for="REG_END_REG">ปิดรับสมัคร: </label>
        <input type="text" name="REG_END_REG" id="REG_END_REG"  value="<?=$config->REG_END_REG?>" required>
      </div>
      <div>
        <label for="REG_START_PAY">เริ่มจ่ายเงิน: </label>
        <input type="text" name="REG_START_PAY" id="REG_START_PAY" value="<?=$config->REG_START_PAY?>" required>
      </div>
      <div>
        <label for="REG_END_PAY">หมดเขตจ่ายเงิน: </label>
        <input type="text" name="REG_END_PAY" id="REG_END_PAY"  value="<?=$config->REG_END_PAY?>" required>
      </div><div>
        <label for="REG_END_PAY">หมดเขตแก้ไขข้อมูลเพิ่มเติม: </label>
        <input type="text" name="REG_END_INFO" id="REG_END_INFO" value="<?=$config->REG_END_INFO?>" required></div>
      </div><div id="regCf">
      <div>
        <label for="REG_PARTICIPANT_NUM">จำนวนผู้แข่งขันต่อทีม: </label>
        <input type="number" step="1" min="0" max="255" name="REG_PARTICIPANT_NUM" id="REG_PARTICIPANT_NUM" value="<?=$config->REG_PARTICIPANT_NUM?>" required>
      </div>
      <div>
      <label for="REG_MAX_TEAM">จำนวนทีมสูงสุด: </label>
        <input type="number" step="1" min="0" name="REG_MAX_TEAM" id="REG_MAX_TEAM" value="<?=$config->REG_MAX_TEAM?>" required></div><div>
        <label for="REG_PAY_PER_PART_US">ค่าสมัครต่อคน (USD): $</label>
        <input type="number" step="0.01" min="0" name="REG_PAY_PER_PART_US" id="REG_PAY_PER_PART_US" value="<?=$config->REG_PAY_PER_PART_US?>" required></div><div>
        <label for="REG_PAY_PER_PART_TH">ค่าสมัครต่อคน (THB): ฿</label>
        <input type="number" min="0" step="0.01" name="REG_PAY_PER_PART_TH" id="REG_PAY_PER_PART_TH" value="<?=$config->REG_PAY_PER_PART_TH?>" required></div></div><div id="db">
      <div>
        <label for="DB_HOST">DB Host: </label>
        <input type="text" name="DB_HOST" id="DB_HOST" value="<?=$config->DB_HOST?>" required>
      </div>
      <div>
        <label for="DB_NAME">Database: </label>
        <input type="text" name="DB_NAME" id="DB_NAME"  value="<?=$config->DB_NAME?>" required>
      </div>
      <div>
        <label for="DB_USERNAME">Username: </label>
        <input type="text" name="DB_USERNAME" id="DB_USERNAME"  value="<?=$config->DB_USER?>" required></div>
      <div><label for="DB_PW">Password: </label>
        <input type="text" name="DB_PW" id="DB_PW" value="<?=$config->DB_PW?>" required></div>
      </div></div>
      <div class="btnset"><button type="submit">บันทึก</button><button type="submit">ยกเลิก</button>
        <a href="config.php?act=reset" title="reset" class="reset">Reset
        </a></div>
    </fieldset>
  </form>
  <div id="result"><?=$json->message?></div>
  <h3>PHP version <?=phpversion()?></h3><!-- InstanceEndEditable --></div></div>
<div id="footer"><a href="admin.php" title="staff only">staff only</a></div>
</body>
<!-- InstanceEnd --></html>
