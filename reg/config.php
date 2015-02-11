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
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>System Configuration :Chiang Mai University International Medical Challenge</title>
<!-- InstanceEndEditable -->
<script src="../js/jquery-1.11.2.min.js"></script>
<script src="../js/jquery-migrate-1.2.1.min.js"></script>
<script src="../js/foundation.min.js"></script>
<script src="../js/vendor/modernizr.js"></script>
<script src="../slick/slick.min.js"></script>
<script src="js/skajax.js"></script>
<link rel="stylesheet" type="text/css" href="../slick/slick.css"/>
<link rel="stylesheet" type="text/css" href="../css/foundation.min.css"/>
<link href="../css/imc_main.css" rel="stylesheet" type="text/css">
<link href="../css/prime.css" rel="stylesheet" type="text/css" />

<!-- InstanceBeginEditable name="head" -->
<script src="js/ui.js"></script>
<script src="js/config.js"></script>
<link rel="stylesheet" href="css/ui.css">
<!-- InstanceEndEditable -->

</head>

<body>
	<div class="row">
		<div class="large-12 columns">
			<img class="hide-for-small" src="../img/logo-head.png"/>
			<img class="show-for-small" src="../img/logo-head-mini.png"/>
			<div class="contain-to-grid sticky">
				<nav class="top-bar" data-topbar data-options="is_hover: false">
					<ul class="title-area">
						<li class="name">
							<h1><a href="/">HOME</a></h1>
						</li>
						<li class="toggle-topbar menu-icon"><a href="#"><span>menu</span></a>
						</li>
					</ul>
					<section class="top-bar-section">
						<ul class="left">
							<li><a href="#">NEWS</a></li>
							<li class="has-dropdown"><a>DETAILS</a>
								<ul class="dropdown">
									<li><a href="#">CMU-IMC?</a></li>
									<li class="divider"></li>
									<li><label>CMU-IMC 2015</label></li>
									<li><a href="#">Competition</a></li>
									<li><a href="#">Activities</a></li>
									<li class="divider"></li>
									<li><a href="#">Miscellaneous</a></li>
								</ul>
							</li>
							<li><a href="#">REGISTER</a></li>
						</ul>
						<ul class="right">
							<li><a href="#">FACEBOOK</a></li>
							<li><a href="#">CONTACT</a></li>
						</ul>
					</section>
				</nav>
			</div>
		</div>
	</div>

<div class="row"> <!--Whole Body -->
<div class="small-12 columns" id="content"><div class="small-12 large-3 columns"><div id="profileBar"><b>Student ID: <?=$sess->student_id?><br>Nickname: <?=$sess->nickname?></b>
</div><div id="adminMenu" class="small-12 large-9"><ul class="side-nav"><li><a href="home.php" title="Admin dashboard">Main page</a></li>
    <li> <a href="home.php#editProfile" title="edit profile">Edit profile</a></li>
    <li><a href="home.php#changePassword">Chage password</a></li>
    <li><a href="logout.php?admin" title="Log out">Log out</a></li>
    <li class="divider"></li>
  <li><a href="#" title="Edit team's, participants', and Observers' information">Edit team's, participants', and Observers' information</a></li>
  <li><a href="#" title="Information confirmation">Information confirmation</a></li>
  <li><a href="#" title="Payment confirmation">Payment confirmation</a></li>
  <li><a href="#" title="Post-registration confirmation">Post-registration confirmation</a></li>
  <li><a href="#" title="for General Modulator">for General Modulator</a></li>
  <li><a href="config.php" title="System configuration">System configuration</a></li>
  <li><a href="edit_admin.php" title="Edit administrator">Edit administrator</a></li>
</ul>
</div></div><div id="adminContent" class="small-12 large-9 columns"><!-- InstanceBeginEditable name="adminContent" -->
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
</div><!--End Body-->
	<footer class="row">
		<div class="large-12 columns">
			<hr>
            <div class="row">
				<div class="large-6 columns">
					<p>Copyright © 2015 Faculty of Medicine, Chiang Mai University
					</p>
				</div>
				<div class="large-6 columns">
					<ul class="inline-list right">
						<li><a href="#">Contact</a></li>
					</ul>
				</div>
			</div>
		</div>
	</footer>
	<script src="../js/jquery.countdown.min.js"></script>
    <script>
		$(document).ready(function(){
			$(document).foundation();
			$('.slick').slick({
				dots: true,
				infinite: true,
				speed: 300,
				slidesToShow: 1,
				autoplay: true,
				autoplaySpeed:1500,
				adaptiveHeight: true
			});
			
			$("#regis-countdown").countdown("2015/03/01", function(event) {
				$(this).text(
					event.strftime('%D days')
				);
			});
		});
	</script>
</body>
<!-- InstanceEnd --></html>
