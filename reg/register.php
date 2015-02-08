<?php
require_once 'config.inc.php';
require_once 'class.SesPrt.php';

if(SesPrt::check(false)) Config::redirect('./','You have already logged in');

require_once 'class.Element.php';
$elem=new Element();
if(Config::isPost()) require_once 'register.scr.php';
?>
<!doctype html>
<html><!-- InstanceBegin template="/Templates/IMC_Main.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Chiang Mai University International Medical Challenge</title>
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
<script src="js/register.js"></script>
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
<div class="small-12 columns" id="content"><!-- InstanceBeginEditable name="Content" -->
  <?php if(!$elem->result):?>
  <form action="register.php" method="post" name="reg" id="reg">
  <div>
    <label>E-mail<input name="email" type="email" required id="email" value="<?=$elem->val('email')?>" maxlength="127"></label></div>
  <div>
    <label>Password<input name="pw" type="password" id="pw" maxlength="32" required></label></div>
    <div>
    <label>Comfirm password
      <input name="cpw" type="password" id="cpw" maxlength="32" required></label></div>
      <div>
    <label>Team's name
      <input name="team_name" type="text" required id="team_name" value="<?=$elem->val('team_name')?>" maxlength="100"></label></div>
  <div>
    <label>Medical school<input name="institution" type="text" required id="institution" value="<?=$elem->val('institution')?>" maxlength="100"></label></div>
  <div>
    <label>University<input name="university" type="text" required id="university" value="<?=$elem->val('university')?>" maxlength="100"></label></div>
  <div>
    <label>Country<?=Config::country()?></label></div>
  <div>
  <? require 'captcha.php'; ?>
  <div>
    <input type="submit" name="submit" id="submit" value="register">
    <input type="reset" name="cancel" id="cancel" value="cancel"></div>
  </div>
  </form><? endif;?>
  <div id="msg"><?=$elem->msg?></div>
<!-- InstanceEndEditable --></div>
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
