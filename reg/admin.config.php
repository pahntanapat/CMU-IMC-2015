<?php
require_once 'config.inc.php';
require_once 'class.SesAdm.php';
require_once 'class.SKAjax.php';

$sess=SesAdm::check();
if(!$sess) Config::redirect('admin.php','you are not log in.');
elseif(!$sess->checkPMS(SesAdm::PMS_WEB)) Config::redirect('home.php','you don\'t have permission here.');
$json=new SKAjax();
if(isset($_GET['act'])) require_once 'admin.config.scr.php';
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
<link href="../css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../slick/slick.css"/>
<link rel="stylesheet" type="text/css" href="../css/foundation.min.css"/>
<link href="../css/imc_main.css" rel="stylesheet" type="text/css">
<link href="../css/prime.css" rel="stylesheet" type="text/css" />

<script src="js/ui.js"></script>
<link href="class.State.php?css=1" rel="stylesheet" type="text/css">
<!-- InstanceBeginEditable name="head" -->
<script src="js/foundation-datepicker.js"></script>
<script src="js/jquery.maskedinput.min.js"></script>
<script src="js/admin.config.js"></script>
<link rel="stylesheet" href="css/ui.css">
<!-- InstanceEndEditable -->

</head>

<body>
	<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div class="withbg-index">
	<div class="row">
		<div class="large-12 columns">
			<div class="row show-for-large-up">
				<div class="clearfix columns">
					<img class="left" src="../img/logo-head_old_trans.png"/>
					<img class="right" src="../img/logo-head_cr3.png"/>
				</div>
			</div>
		  <div class="row show-for-medium-only">
				<div class="clearfix columns">
					<img class="left" src="../img/logo-head_trans.png"/>
				</div>
			</div>
			<img class="show-for-small-only" src="../img/logo-head-mini_trans.png"/>
			<div class="contain-to-grid">
				<nav class="top-bar" data-topbar data-options="is_hover: false">
					<ul class="title-area">
						<li class="name">
							<h1>
								<a href="/">HOME</a>
						  </h1>
						</li>
						<li class="toggle-topbar menu-icon"><a href="#"><span>menu</span></a>
						</li>
					</ul>
					<section class="top-bar-section">
						<ul class="left">
							<li><a href="../news.html">NEWS</a></li>
							<li class="has-dropdown"><a>DETAILS</a>
								<ul class="dropdown">
									<li><a href="../about_IMC.html">CMU-IMC</a></li>
									<li><a href="../competition.html">Competition</a></li>
									<li><a href="../registration.html">Registration</a></li>
									<li><a href="../mini_gallery.html">Gallery</a></li>
									<li class="divider"></li>
									<li><a href="../accommodation.html">Accommodation</a></li>
									<li><a href="../activities.html">Recreational activities</a></li>
									<li><a href="../cm_tour.html">Chiang Mai Tour</a></li>
									<li class="divider"></li>
									<li><a href="../local_information.html">Local Information</a></li>
									<li><a href="../faq.html">FAQ</a></li>
									<li class="divider"></li>
									<li><a href="../invite_package.html">Invitation Package</a></li>
								</ul>
							</li>
							<li><a href="../reg/">REGISTER</a></li>
							
					  </ul>
						<ul class="right">
							<li><a href="https://www.facebook.com/CMU.IMC" target="_blank">FACEBOOK</a></li>
							<li><a href="https://twitter.com/cmu_imc" target="_blank">TWITTER</a></li>
							<li><a href="../contact.html">CONTACT US</a></li>
						</ul>
					</section>
				</nav>
			</div>
		</div>
	</div>

<div class="row"> <!--Whole Body -->
<div class="small-12 columns" id="content"><div class="small-12 large-3 columns" id="sidebar">
<ul class="accordion" data-accordion>
    <li class="accordion-navigation">
    	<a href="#profileBar"><i class="fa fa-user-md"></i> Admin's Profile</a>
        <div id="profileBar" class="content active"><b>Student ID:</b> <?=$sess->student_id?><br><b>Nickname:</b> <?=$sess->nickname?></div>
    </li>
    <li class="accordion-navigation">
    	<a href="#adminMenu"><i class="fa fa-bars"></i> Main menu</a>
    	<div class="content" id="adminMenu"><ul class="side-nav"><li><a href="home.php" title="Admin dashboard"><i class="fa fa-home fa-lg"></i> Main page</a></li>
    		<li><a href="home.php#editProfile" title="edit profile"><i class="fa fa-pencil fa-lg"></i> Edit profile</a></li>
    		<li><a href="home.php#changePassword"><i class="fa fa-key fa-lg"></i> Chage password</a></li>
    		<li><a href="logout.php?admin" title="Log out"><i class="fa fa-sign-out fa-lg"></i> Log out</a></li></ul></div>
    </li>
    <li class="accordion-navigation">
    	 <a href="#adminTask"><i class="fa fa-tasks"></i> Admin Task</a>
    	 <div class="content" id="adminTask"><ul class="side-nav">
            <li><a href="admin.team.php" title="Edit team's, participants', and advisors' information"><i class="fa fa-pencil-square-o fa-lg"></i> Edit teams', participants', and advisors' information</a></li>
      		<li><a href="admin.participant.php" title="Summarize information"><i class="fa fa-bar-chart fa-lg"></i> Summarize information</a></li>
      		<li><a href="admin.participant.php?view=team&order=0" title="Participating teams"><i class="fa fa-table fa-lg"></i> Confirmed teams (Order by Team's name)</a></li>
      		<li><a href="admin.participant.php?view=team&order=1" title="Participating teams"><i class="fa fa-table fa-lg"></i> Confirmed teams (Order by Arrival time)</a></li>
            <li><a href="admin.participant.php?view=obs&distinct=0" title="Participating teams"><i class="fa fa-table fa-lg"></i> Confirmed advisors</a></li>
            <li><a href="admin.participant.php?view=obs&distinct=1" title="Participating teams"><i class="fa fa-table fa-lg"></i> Confirmed distinct advisors</a></li>
            <li><a href="admin.participant.php?view=part" title="Participating teams"><i class="fa fa-table fa-lg"></i> Confirmed participant (Medical student)</a></li>
      		<li><hr></li>
      		<li><a href="admin.info.php"><i class="fa fa-check-square-o fa-lg"></i> Approve teams' information: step 1</a></li>
      		<li><a href="admin.pay.php"><i class="fa fa-check-square-o fa-lg"></i> Approve the transfer slip</a></li>
      		<li><a href="admin.post_reg.php"><i class="fa fa-check-square-o fa-lg"></i> Approve teams' information: step 2</a></li>
      		<li><hr></li>
      		<li><a href="admin.edit.php" title="Edit administrator"><i class="fa fa-users fa-lg"></i> Edit administrator</a></li>
      		<li><a href="admin.config.php" title="System configuration"><span class="fa-stack"><i class="fa fa-square fa-stack-2x"></i><i class="fa fa-terminal fa-stack-1x fa-inverse"></i></span> System configuration</a></li>
		</ul></div></li>
</ul>
</div>
<div id="adminContent" class="small-12 large-9 columns"><!-- InstanceBeginEditable name="adminContent" -->
<h2>System configuration</h2>
 <form action="admin.config.php?act=save" method="post">
    <fieldset>
      <legend>ตั้งค่าระบบ</legend>
      <ul class="tabs" data-tab>
        <li class="tab-title active"><a href="#cpr">&copy; Copyright</a></li>
        <li class="tab-title"><a href="#sch">Schedule</a></li>
        <li class="tab-title"><a href="#regCf">Register</a></li>
        <li class="tab-title"><a href="#db">Database</a></li>
      </ul>
      <div class="tabs-content">
      <div id="cpr" class="content active">
        <p>Copyright &copy; 2015 <a href="http://labs.sinkanok.com">Sinkanok Labs</a>, <a href="http://sinkanok.com">Sinkanok Groups</a></p>
        <p><strong>Programmer:</strong> Pahn - Sinkanok Labs </p>
        <p>Powered by <strong>SKAjax Framwork</strong> and <strong>Modified Programming Architecture of Sinkanok Labs</strong>.</p>
        <h5>Products of Sinkanok Labs: <a href="http://labs.sinkanok.com/sakodpid.html" target="_blank">Sakodpid</a>, <a href="http://labs.sinkanok.com/sakodpid.html" target="_blank">Sakodpid 2.0</a>, and <a href="http://labs.sinkanok.com/converter/total.html" target="_blank">Unit Converter</a></h5>
        <div class="row">
          <div class="small-6 medium-3 columns"><a href="http://labs.sinkanok.com/sakodpid_mini.html" target="_blank" class="th"><img src="img/promotebanner_mini.png" alt="Sakodpid 1.0 mini" longdesc="http://labs.sinkanok.com/sakodpid_mini.html" /></a></div>
          <div class="small-6 medium-3 columns"><a href="http://labs.sinkanok.com/converter/total.html" target="_blank" class="th"><img src="img/promotebanner_pro.png" alt="Sakodpid 1.0 mini" longdesc="http://labs.sinkanok.com/converter/total.html" /></a></div>
          <div class="small-6 medium-3 columns"><a href="http://labs.sinkanok.com/converter/essential.html" target="_blank" class="th"><img src="img/essential_logo.png" alt="Essential Unit Converter" longdesc="http://labs.sinkanok.com/converter/essential.html" /></a></div>
          <div class="small-6 medium-3 columns"><a href="http://labs.sinkanok.com/converter/total.html" target="_blank" class="th"><img src="img/total_logo.png" alt="Essential Unit Converter" longdesc="http://labs.sinkanok.com/converter/total.html" /></a></div>
        </div>
      </div>
      <div id="sch" class="content">
      <div class="alert-box secondary" data-alert><i class="fa fa-calendar"></i> วันเวลาต้องอยู่ในรูปแบบ<br>&quot;[ปี ค.ศ. 4 หลัง]-[เลขเดือน 2 หลัก เช่น 01]-[วันที่ 2 หลัก เช่น 09][เว้นวรรค 1 ครั้ง][ชั่วโมง 2 หลัก]:[นาที 2 หลัก]:[วินาที 2 หลัก]&quot;<br>เช่น<br><ul><li>2015-01-20 04:30:00</li><li>2015-12-31 23:59:59</li><li>2015-10-02 00:00:00</li></ul></div>
      <div>
        <label for="REG_START_REG">เปิดรับสมัคร: </label>
        <input type="datetime" name="REG_START_REG" id="REG_START_REG" value="<?=$config->REG_START_REG?>" required>
      </div>
      <div>
        <label for="REG_END_REG">ปิดรับสมัคร: </label>
        <input type="datetime" name="REG_END_REG" id="REG_END_REG"  value="<?=$config->REG_END_REG?>" required>
      </div>
      <div>
        <label for="REG_START_PAY">เริ่มจ่ายเงิน: </label>
        <input type="datetime" name="REG_START_PAY" id="REG_START_PAY" value="<?=$config->REG_START_PAY?>" required>
      </div>
      <div>
        <label for="REG_END_PAY">หมดเขตจ่ายเงิน: </label>
        <input type="datetime" name="REG_END_PAY" id="REG_END_PAY"  value="<?=$config->REG_END_PAY?>" required>
      </div><div>
        <label for="REG_END_PAY">หมดเขตแก้ไขข้อมูลเพิ่มเติม: </label>
        <input type="datetime" name="REG_END_INFO" id="REG_END_INFO" value="<?=$config->REG_END_INFO?>" required></div>
      </div><div class="content" id="regCf">
      <div>
        <label for="REG_PARTICIPANT_NUM">จำนวนผู้แข่งขันต่อทีม: </label>
        <input type="number" step="1" min="0" max="255" name="REG_PARTICIPANT_NUM" id="REG_PARTICIPANT_NUM" value="<?=$config->REG_PARTICIPANT_NUM?>" required>
      </div>
      <div>
      <label for="REG_MAX_TEAM">จำนวนทีมสูงสุด: </label>
        <input type="number" step="1" min="0" name="REG_MAX_TEAM" id="REG_MAX_TEAM" value="<?=$config->REG_MAX_TEAM?>" required></div>
        <div><div class="row collapse">
        <label for="REG_PAY_PER_PART_US">ค่าสมัครต่อคน (USD): </label><div class="small-2 large-1 columns"><span class="prefix">$</span></div>
        <div class="small-10 large-11 columns"><input type="number" step="0.01" min="0" name="REG_PAY_PER_PART_US" id="REG_PAY_PER_PART_US" value="<?=$config->REG_PAY_PER_PART_US?>" required></div>
        </div></div>
        <div><div class="row collapse">
        <label for="REG_PAY_PER_PART_TH">ค่าสมัครต่อคน (THB): </label><div class="small-2 large-1 columns"><span class="prefix">฿</span></div>
        <div class="small-10 large-11 columns"><input type="number" min="0" step="0.01" name="REG_PAY_PER_PART_TH" id="REG_PAY_PER_PART_TH" value="<?=$config->REG_PAY_PER_PART_TH?>" required></div>
        </div></div>
        <div>
          <label>Shirt size: <small>One Size One Line; พิมพ์ 1 ขนาดต่อ 1 บรรทัด</small>
            <textarea name="INFO_SHIRT_SIZE" rows="5" id="INFO_SHIRT_SIZE"><?=$config->INFO_SHIRT_SIZE?></textarea>
            <div data-alert class="alert-box info radius"><i class="fa fa-lg pull-left fa-info-circle"></i>ถ้าเปลี่ยนแปลงขนาดเสื้อ (ตารางขนาดเสื้อ) อย่าลืมไปแก้ไฟล์ member.php</div>
          </label>
        </div>
        <div>
          <label>
          Routes of Chiang Mai Tour: <small>One Route One Line; พิมพ์ 1 เส้นทางต่อ 1 บรรทัด</small>
            <textarea name="INFO_ROUTE" rows="5" id="INFO_ROUTE" wrap="off"><?=$config->INFO_ROUTE?></textarea>
            <div data-alert class="alert-box alert radius"><i class="fa fa-2x pull-left fa-exclamation-triangle"></i>ถ้าสลับที่ route อาจจะทำให้ข้อมูลผู้แข่งขันเปลี่ยนได้ เพราะระบบจะจดจำ route ตามลำดับบรรทัด ไม่ใช่ตามชื่อ route และถ้ามีการเปลี่ยนแปลง ควรแจ้งผู้แข่งขันทุกครั้ง</div>
          </label>
        </div>
        </div>
        <div class="content" id="db">
      <div>
        <label for="DB_HOST">DB Host: </label>
        <input type="text" name="DB_HOST" id="DB_HOST" value="<?=$config->DB_HOST?>" required>
      </div>
      <div>
        <label for="DB_NAME">Database: </label>
        <input type="text" name="DB_NAME" id="DB_NAME"  value="<?=$config->DB_NAME?>" required>
      </div>
      <div>
        <label for="DB_USER">Username: </label>
        <input type="text" name="DB_USER" id="DB_USER"  value="<?=$config->DB_USER?>" required></div>
      <div><label for="DB_PW">Password: </label>
        <input type="text" name="DB_PW" id="DB_PW" value="<?=$config->DB_PW?>" required></div>
        <div><label for="DB_PW">Upload folder: </label>
        <input type="text" name="UPLOAD_FOLDER" id="UPLOAD_FOLDER" value="<?=$config->UPLOAD_FOLDER?>" required>
        <div data-alert class="alert-box alert radius"><i class="fa fa-2x pull-left fa-exclamation-triangle"></i>ถ้าเปลี่ยน upload folder แต่ระบบไม่สามารถย้าย folder ได้ เมื่อมีคน upload ไฟล์ขึ้นไปแล้ว จำทำให้ระบบไม่สามารถหาไฟล์ได้ แม้ว่าจะมีไฟล์อยู่บน host ดังนั้น ผู้ที่แก้ไขต้องไปย้ายไฟล์บน host ด้วย FTP เองด้วย</div>
        </div>
      </div>
      <div class="btnset"><button type="submit">บันทึก</button><button type="reset">ยกเลิก</button><a href="admin.config.php?act=rc" title="reset" class="reset button">Reset Config</a><a href="admin.config.php?act=rpd" title="reset" class="reset button">Reset participants' information &amp; Upload directory</a><a href="admin.config.php?act=rdb" title="reset" class="reset button">Reset database &amp; Upload directory (Factory reset)</a></div>
        </div>
    </fieldset>
  </form>
  <div id="result"><?=$json->message?></div>
  <h4>PHP version 
    <?=phpversion()?>
  </h4>
<!-- InstanceEndEditable --></div></div>
</div>
</div><!--End Body-->
	<footer class="row">
		<div class="large-12 columns">
			<hr>
            <div class="row">
				<div class="small-10 columns">
					<p>Copyright © 2015 <a href="http://labs.sinkanok.com" title="Sinkanok Labs" target="_blank">Sinkanok Labs</a>, <a href="http://sinkanok.com" title="Sinkanok Groups" target="_blank">Sinkanok Groups</a> for CMU-IMC, Faculty of Medicine, Chiang Mai University </p>
				</div>
				<div class="small-2 columns">
					<ul class="inline-list right">
						<li><a href="../contact.html">Contact</a></li>
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
