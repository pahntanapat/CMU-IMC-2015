<?php
require_once 'config.inc.php';
require_once 'class.SesPrt.php';

if(SesPrt::check(false)) Config::redirect('./','You have already logged in');

if(Config::isPost()) require_once 'login.scr.php';
else{
	require_once 'class.SKAjax.php';
	$ajax=new SKAjax();
}
?>
<!doctype html>
<html><!-- InstanceBegin template="/Templates/IMC_Main.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Log in :Chiang Mai University International Medical Challenge</title>
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
<!-- InstanceBeginEditable name="head" -->
<script src="js/login.js"></script>
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
				<div class="clearfix columns"> <a href="/"><img class="left" src="../img/logo-head_trans.png"/></a>
				</div>
			</div>
          <a href="/"><img class="show-for-small-only" src="../img/logo-head-mini_trans.png"/></a>
			<div class="contain-to-grid">
				<nav class="top-bar" data-topbar data-options="is_hover: false">
					<ul class="title-area">
						<li class="name"><h1><a href="/">HOME</a></h1></li>
						<li class="toggle-topbar menu-icon"><a href="#"><span>menu</span></a></li>
					</ul>
					<section class="top-bar-section">
						<ul class="left">
							<li><a href="https://facebook.com/CMU.IMC" target="_blank" data-reveal-id="news">NEWS</a></li>
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
                                    <li><a href="../sponsor.html">Sponsorship</a></li>
									<li><a href="../faq.html">FAQ</a></li>
									<li class="divider"></li>
									<li><a href="../invite_package.html">Invitation Package</a></li>
								</ul>
							</li>
							<li><a href="../reg">REGISTER</a></li>
					  </ul>
						<ul class="right">
							<li><a href="https://facebook.com/CMU.IMC" target="_blank">FACEBOOK</a></li>
							<li><a href="https://twitter.com/cmu_imc" target="_blank">TWITTER</a></li>
							<li><a href="../contact.html">CONTACT US</a></li>
						</ul>
					</section>
				</nav>
			</div>
		</div>
	</div>

<div class="row"> <!--Whole Body -->
<div class="small-12 columns" id="content"><!-- InstanceBeginEditable name="Content" -->
  <form action="login.php" method="post" name="login" id="login">
  <fieldset><legend>Log in</legend>
  <div>
    <label>E-mail<input name="email" type="email" required id="email" value="<?=@$_POST['email']?>" maxlength="127"></label></div>
  <div>
    <label>Password<input name="pw" type="password" id="pw" maxlength="32" required></label></div>
      <? require 'captcha.php'; ?><div>
    <button type="submit" name="submit" id="submit" value="log in">Log in</button>
    <button type="reset" name="cancel" id="cancel" value="cancel">Cancel</button></div><div> <ul class="inline-list">
    <li><a href="register.php" title="create new account">I don't have any account. Create new account.</a></li>
    <li> <a href="../contact.html">Forget Password? Contact Adminstrator</a></li>
    <li><a href="admin.php">For Administrator</a></li>
  </ul></div></fieldset>
  </form>
  <?=$ajax->toMsg()?>
 
  <!-- InstanceEndEditable --></div>
</div>
</div><!--End Body-->
	<footer class="row">
		<div class="large-12 columns">
			<hr>
            <div class="row">
				<div class="small-10 columns">
					<p>Copyright ?? 2015 <a href="http://labs.sinkanok.com" title="Sinkanok Labs" target="_blank">Sinkanok Labs</a>, <a href="http://sinkanok.com" title="Sinkanok Groups" target="_blank">Sinkanok Groups</a> for CMU-IMC, Faculty of Medicine, Chiang Mai University </p>
				</div>
				<div class="small-2 columns">
					<ul class="inline-list right">
						<li><a href="../contact.html">Contact</a></li>
					</ul>
				</div>
			</div>
		</div>
	</footer>
    <div id="news" class="reveal-modal" data-reveal>
			<h2>NEWS</h2>
			<p>Please follow us on CMU-IMC facebook page for updated news. Click <a href="http://facebook.com/cmu.imc">here</a></p>
			<a class="close-reveal-modal">&times;</a>
		</div>
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
				$(this).text(event.strftime('%D days'));
			});
		});
	</script>
</body>
<!-- InstanceEnd --></html>
