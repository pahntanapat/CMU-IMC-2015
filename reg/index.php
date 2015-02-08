<?php
require_once 'config.inc.php';
require_once 'class.SesPrt.php';

$s=SesPrt::check();
if(!$s) Config::redirect('login.php','You do not log in. Please log in.');

require_once 'class.Element.php';
$elem=new Element();

require_once 'class.Message.php';
require_once 'index.scr.php';

require_once 'class.State.php';
?>
<!doctype html>
<html><!-- InstanceBegin template="/Templates/IMC_reg.dwt.php" codeOutsideHTMLIsLocked="false" -->
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

<link href="class.State.php?css=1" rel="stylesheet" type="text/css">
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
<div class="small-12 columns" id="content"><div class="small-12 large-3 columns">
  <div>Team's name: <?=$s->teamName?><br>
    Institution: <?=$s->institution?><br>
    University: <?=$s->university?><br>
    Country: <?=$s->country?>
  </div>
<div id="progression">Progress: <?=$s->getProgression()?></div>
<ol class="side-nav">
  <li class="<?=State::toClass($s->teamState)?>" id="menuTeamInfo"><a href="team.php" title="Team &amp; Institution information">Team &amp; Institution information</a></li>
  <li class="<?=State::toClass($s->getObserverInfoState())?>" id="menuObsvInfo">Professor's infomation</li>
  <? for($i=1;$i<=$config->REG_PARTICIPANT_NUM;$i++):?>
  <li class="<?=State::toClass($s->getParticipantInfoState($i))?>" id="menuPartInfo<?=$i?>"><?=Config::ordinal($i)?> participant's infomation</li>
  <? endfor;?>
  <li class="<?=State::toClass($s->cfInfoState)?>" id="menuCfInfo">Confirmation of Application Form</li>
  <li class="<?=State::toClass($s->payState)?>" id="menuPay">Upload Transaction</li>
  <li class="<?=State::toClass($s->getObserverPostRegInfoState())?>" id="menuObsvPostReg">Update professor's shirt size &amp; passport</li>
  <? for($i=1;$i<=$config->REG_PARTICIPANT_NUM;$i++):?>
  <li class="<?=State::toClass($s->getParticipantPostRegInfoState($i))?>" id="menuPartPostReg<?=$i?>">Update <?=Config::ordinal($i)?>  participant's shirt size &amp; passport</li>
  <? endfor;?>
  <li class="<?=State::toClass($s->postRegState)?>" id="menuPostReg">Select route &amp; upload team's picture &amp; update arrival time</li>
  <li class="<?=State::toClass($s->cfPostRegState)?>" id="cfPostReg">Confirmation of journey</li>
</ol>
<ul class="side-nav">
  <li><a href="index.php" title="Main page">Main page</a></li>
  <li><a href="index.php#changePW">Change password</a></li>
  <li><a href="logout.php" title="Log out">Log out</a></li>
</ul>
</div><div id="regContent" class="small-12 large-9 columns"><!-- InstanceBeginEditable name="reg_content" --><div id="msg"><?=$elem->msg?></div><div>
  <form action="index.php" method="post" name="changePassword" id="changePassword" data-action="index.scr.php"> <fieldset>
      <legend>Change password</legend>
      <div>
        <label for="oldPassword">old password</label>
        <input type="password" name="oldPassword" id="oldPassword">
      </div>
      <div>
        <label for="pw">new password</label>
        <input type="password" name="pw" id="pw">
      </div>
      <div>
        <label for="cfPW">confirm password</label>
        <input type="password" name="cfPW" id="cfPW">
      </div>
      <div>
       <input name="savePW" type="submit" id="savePW" value="Save">
     <input type="reset" name="resetPW" id="resetPW" value="Reset"></div>
    </fieldset>
    <div id="msgCP"><?=$elem->msgCP?></div>
  </form>
</div><!-- InstanceEndEditable --></div></div>
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
