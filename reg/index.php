<?php
require_once 'config.inc.php';
require_once 'class.SesPrt.php';

$s=SesPrt::check();
if(!$s) Config::redirect('login.php','You do not log in. Please log in.');

require_once 'class.State.php';
require_once 'index.scr.php';

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
<link href="../css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../slick/slick.css"/>
<link rel="stylesheet" type="text/css" href="../css/foundation.min.css"/>
<link href="../css/imc_main.css" rel="stylesheet" type="text/css">
<link href="../css/prime.css" rel="stylesheet" type="text/css" />

<script src="js/ui.js"></script>
<script src="js/updateMenuState.js"></script>
<link href="class.State.php?css=1" rel="stylesheet" type="text/css">
<!-- InstanceBeginEditable name="head" -->
<script src="js/change_pw.js"></script>
<script src="../js/vendor/jquery.cookie.js"></script>
<script src="js/joyride.js"></script>
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
<div class="small-12 columns" id="content"><div class="small-12 large-4 columns" id="sidebar">
<ul class="accordion" data-accordion>
    <li class="accordion-navigation">
        <a href="#sbTeamInfo" id="h-sbTeamInfo"><i class="fa fa-user-md"></i> Profile</a>
        <div id="sbTeamInfo" class="content active">
            <b>Team's name:</b> <span id="teamNamePf"><?=$s->teamName?></span><br>
            <b>Institution:</b> <span id="institutionPf"><?=$s->institution?></span><br>
            <b>University:</b> <span id="universityPf"><?=$s->university?></span><br>
            <b>Country:</b> <span id="countryPf"><?=$s->country?><br></span><br>
            <b>Progression</b>
            <div id="progression" class="progress round"><span class="meter" style="width:<?=$s->getProgression()?>%"></span></div>
        </div>
    </li>
    <li class="accordion-navigation">
        <a href="#sbMenu" id="h-sbMenu"><i class="fa fa-bars"></i> Main menu</a>
        <div id="sbMenu" class="content"><ul class="side-nav">
  <li><a href="index.php" title="Main page"><i class="fa fa-home fa-lg"></i> Main page</a></li>
  <li><a href="index.php#changePW"><?=State::img(State::ST_EDITABLE)?>Change password</a></li>
  <li><a href="logout.php" title="Log out"><i class="fa fa-sign-out fa-lg"></i> Log out</a></li></ul>
        </div>
    </li>
    <li class="accordion-navigation">
        <a href="#sbStep" id="h-sbStep"><i class="fa fa-check-square"></i> Edit information</a>
        <div id="sbStep" class="content">
        <ul class="side-nav">
  <li class="<?=State::inTime($s->teamState, $config->REG_START_REG, $config->REG_END_REG, true)?>" id="menuTeamInfo"><a href="team.php" title="Team &amp; Institution information">Team &amp; Institution information</a></li>
  <li class="<?=State::inTime($s->getObserverInfoState(), $config->REG_START_REG, $config->REG_END_REG, true)?>" id="menuObsvInfo"><a href="member.php?no=0" title="Advisor's infomation">Advisor's infomation</a></li>
  <? for($i=1;$i<=$config->REG_PARTICIPANT_NUM;$i++):?>
  <li class="<?=State::inTime($s->getParticipantInfoState($i), $config->REG_START_REG, $config->REG_END_REG, true)?>" id="menuPartInfo<?=$i?>"><a href="member.php?no=<?=$i?>" title="<?=Config::ordinal($i, false)?>  participant's infomation"><?=Config::ordinal($i)?>  participant's information</a></li>
  <? endfor;?>
  <li class="<?=State::inTime($s->cfInfoState, $config->REG_START_REG, $config->REG_END_REG, true)?>" id="menuCfInfo"><a href="confirm.php?step=1" title="Confirmation of Application Form">Confirmation of Application Form</a></li>
  <li><hr></li>
  <li class="<?=State::inTime($s->payState, $config->REG_START_PAY, $config->REG_END_PAY, true)?>" id="menuPay"><a href="pay.php" title="Upload Transfer Slip">Upload &amp; Confirm transfer slip</a></li>
  <li><hr></li>
  <li class="<?=State::inTime($s->postRegState, $config->REG_START_PAY, $config->REG_END_INFO, true)?> menuPostReg"><a href="post_reg.php?sec=1" title="Select route &amp; upload team's picture &amp; update arrival time">Trip selection</a></li>
  <li class="<?=State::inTime($s->postRegState, $config->REG_START_PAY, $config->REG_END_INFO, true)?> menuPostReg"><a href="post_reg.php?sec=2">Upload team's photo</a></li>
  <li class="<?=State::inTime($s->postRegState, $config->REG_START_PAY, $config->REG_END_INFO, true)?> menuPostReg"><a href="post_reg.php?sec=3">Transportation info</a></li>
  <li class="<?=State::inTime($s->cfPostRegState, $config->REG_START_PAY, $config->REG_END_INFO, true)?>" id="cfPostReg"><a href="confirm.php?step=2" title="Confirmation of journey">Confirmation of Application Form</a></li>
</ul>
        </div>
    </li>
</ul>
</div><div id="regContent" class="small-12 large-8 columns"><!-- InstanceBeginEditable name="reg_content" -->
    <h2>CMU-IMC Registration system</h2><div class="panel radius callout" id="teamMsg"><?=$msg?></div><hr><div>
    <h3><?=State::img(State::ST_EDITABLE)?>Change password</h3>
  <form action="index.php" method="post" name="changePassword" id="changePassword" data-action="index.scr.php"> <fieldset class="require">
      <legend>Change password</legend>
      <div>
        <label class="require">old password
        <input type="password" name="oldPassword" id="oldPassword"></label>
      </div>
      <div>
        <label class="require">new password <small>Password must be 6-32 characters with letters, digits, _ (underscore), : (colon), or ; (semicolon).</small>
        <input type="password" name="pw" id="pw"></label>
      </div>
      <div>
        <label class="require">confirm password
        <input type="password" name="cfPW" id="cfPW"></label>
      </div>
      <div>
       <button name="savePW" type="submit" id="savePW" value="Save">Save</button>
     <button type="reset" name="resetPW" id="resetPW" value="Cancel">Cancel</button></div>
    </fieldset>
    <?php 
	if(!isset($ajax)){
		require_once 'class.SKAjax.php';
		$ajax=new SKAjax();
		$ajax->msgID="msgCP";
	}
	echo $ajax->toMsg();
?></form>
</div><hr>
<ul class="accordion hide" data-accordion>
<li class="accordion-navigation"><a href="#sponsor1">Booking form 1</a><div id="sponsor1" class="content active"><img src="http://placehold.it/600x400&text=Booking+Form+5%2C000+THB"/></div></li>
<li class="accordion-navigation"><a href="#sponsor2">Booking form 2</a><div id="sponsor2" class="content active"><img src="http://placehold.it/600x400&text=Booking+Form+4%2C000+THB"/></div></li>
<li class="accordion-navigation"><a href="#sponsor3">Booking form 3</a><div id="sponsor3" class="content active"><img src="http://placehold.it/600x400&text=Booking+Form+3%2C000+THB"/></div></li>
</ul><hr><?=State::stateList()?>
<button type="button" class="right" id="loadJR"><i class="fa fa-question-circle"></i> Help</button>
<ol class="joyride-list" data-joyride>
  <li data-text="Next" data-options="prev_button:false"><h4>Hi!</h4><p>Welcome to CMU-IMC Registration System.</p><input type="checkbox" id="hideJR" name="hideJR" value="1"><label for="hideJR">Don't show it again.</label></li>
  <li data-id="h-sbTeamInfo" data-text="Next" data-prev-text="Prev" data-options="tip_location:right"><h4><i class="fa fa-user-md"></i> Profile</h4><p>Your team's profile and progression of registration are here. You can click <b>the accordions</b> <i>(on the grey bar)</i> to hide or expand them.</p></li>
  <li data-id="h-sbMenu" data-text="Next" data-prev-text="Prev" data-options="tip_location:right"><h4><i class="fa fa-bars"></i> Main menu</h4><p>To go to the main page, change password or log out, please follow the links provided in this menu.</p></li>
  <li data-id="h-sbStep" data-text="Next" data-prev-text="Prev" data-options="tip_location:right"><h4><i class="fa fa-check-square"></i> Edit Information</h4><p>All steps of registration are provided here. If they are hidden, <b>please click the accordions to expand this section</b>.</p></li>
  <li data-id="menuTeamInfo" data-text="Next" data-prev-text="Prev" data-options="tip_location:right"><h4>Step-by-Step</h4><p>The steps of registration are sorted in order so that you can follow them step by step.<small><br><br><i class="fa fa-exclamation-triangle fa-2x"></i> If the Edit Information section is hidden, the instruction callout will point you the wrong position. Please go back to the previous step and then continue the instruction guideline as normal.</small></p></li>
  <li data-id="menuObsvInfo" data-text="Next" data-prev-text="Prev" data-options="tip_animation:fade"><h4><i class="fa fa-pencil"></i> Status [1]</h4><p>Each step has its status, displayed to tell whether it is locked, editable, waiting for approval, etc. <small><br><br><i class="fa fa-exclamation-triangle fa-2x"></i> If the Edit information section is hidden, the instruction callout will point you the wrong position. Please go back to the previous step and then continue the instruction guideline as normal.</small></p></li>
  <li data-id="stateList" data-text="Next" data-prev-text="Prev" data-options="tip_location:top"><h4><i class="fa fa-pencil"></i> Status [2]</h4><p>All descriptions of statuses are here. They will also be mentioned on the top of the page of each registration step.</p></li>
  <li data-id="reloadMsg" data-text="Next" data-prev-text="Prev"><h4>Message from Admin</h4><p>Also, <b>Messages from Administrators (CMU-IMC Staffs)</b> are located on the top of every page. They will notify you any occurring problems and important facts.</p></li>
  <li data-id="loadJR" data-text="Next" data-prev-text="Prev" data-options="tip_location:left"><h4>To replay again</h4><p>Finally, if you want to start the introduction guide again, click this button.</p></li>
  <li data-text="End" data-prev-text="Prev"><h4>Let's go!</h4><p>Thank you for your attention to me. Now let's start the <b><a href="team.php" target="_blank">first step</a></b> of registration.</p></li>
</ol>
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
