<?php
require_once 'config.inc.php';
require_once 'class.SesAdm.php';

$sess=SesAdm::check();
if(!$sess) Config::redirect('admin.php','you are not log in.');
elseif(!$sess->checkPMS(SesAdm::PMS_ADMIN))  Config::redirect('home.php','you don\'t have permission here.');

require_once 'edit_admin.scr.php';
/*if(Config::isPost()) require_once 'edit_admin.scr.php';
else{
	require_once 'class.SKAjax.php';
	require_once 'edit_admin.view.php';
	
	$ajax=new SKAjax();
	if(isset($_GET['id'])){
		$ajax->msgID="divAdminForm";
		$ajax->message=formAdmin(new Admin($config->PDO()),Config::isPost()?NULL:$_GET['id']);
	}else{
		$ajax->msgID="adminList";
		$ajax->message=tableAdmin(new Admin($config->PDO()));
	}
}*/
?>
<!doctype html>
<html><!-- InstanceBegin template="/Templates/IMC_admin.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Edit Admin :Chiang Mai University International Medical Challenge</title>
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
<script src="js/edit_admin.js"></script>
<script src="js/ui.js"></script>
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
</div></div><div id="adminContent" class="small-12 large-9 columns"><!-- InstanceBeginEditable name="adminContent" --><h2>Add/Edit/Delete admin</h2>
<?php
	if(isset($_GET['id'])):
		echo $ajax->toMsg();
	else:?><form action="edit_admin.php" method="post" id="adminListForm">
  <div>
    <button type="button" id="selectAll">Select All</button>
    <a href="edit_admin.php#reloadAdminList" title="reload" class="button" id="reloadAdminList">Reload</a> <a href="edit_admin.php?id=0" title="Add new admin" class="edit button">Add</a>
    <button type="submit" name="remove" id="remove" value="remove">Remove</button>
  </div>
  <?=$ajax->toMsg()?></form><? endif;?><!-- InstanceEndEditable --></div></div>
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
