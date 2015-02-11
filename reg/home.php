<?php
require_once 'config.inc.php';
require_once 'class.SesAdm.php';

$sess=SesAdm::check();
if(!$sess) Config::redirect('admin.php','you are not log in.');

$elem=new ArrayObject();
if(Config::isPost()) require_once 'home.scr.php';
?>
<!doctype html>
<html><!-- InstanceBegin template="/Templates/IMC_admin.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Admin dashboard :Chiang Mai University International Medical Challenge</title>
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
<script src="js/change_pw.js"></script>
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
<div><fieldset>
      <legend>Your roles</legend><?=SesAdm::checkbox($sess->pms)?></fieldset></div>
  <form action="home.php" method="post" name="editProfile" id="editProfile" data-action="home.scr.php" data-magellan-destination="editProfile">
    <fieldset>
      <legend>Edit profile</legend>
     <div>
       <label for="student_id">Student ID</label>
       <input name="student_id" type="text" id="student_id" value="<?=isset($_POST['student_id'])?$_POST['student_id']:$sess->student_id?>">
     </div>
     <div>
       <label for="nickname">Nickname</label>
       <input name="nickname" type="text" id="nickname" value="<?=isset($_POST['nickname'])?$_POST['nickname']:$sess->nickname?>">
     </div>
     <div>
       <button name="saveEP" type="submit" id="saveEP" value="Save">Save</button>
     <button type="reset" name="resetEP" id="resetEP" value="Reset">Reset</button>
     </div>
    </fieldset>
    <div id="msgEP"><?=isset($elem->msgEP)?$elem->msgEP:''?></div>
      </form>

  <form action="home.php" method="post" name="changePassword" id="changePassword" data-action="home.scr.php" data-magellan-destination="changePassword">
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
     <button name="savePW" type="submit" id="savePW" value="Save">Save</button>
     <button type="reset" name="resetPW" id="resetPW" value="Reset">Reset</button></div>
    </fieldset>
    <div id="msgCP"><?=isset($elem->msgCP)?$elem->msgCP:''?></div>
  </form>
<!-- InstanceEndEditable --></div></div>
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
