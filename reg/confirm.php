<?php
require_once 'config.inc.php';
require_once 'class.SesPrt.php';

$s=SesPrt::check(true,true);
if(!$s) Config::redirect('login.php','You do not log in. Please log in.');

if(isset($_GET['step']))
	if(is_numeric($_GET['step'])) $step=intval($_GET['step']);

if(!isset($step))
	Config::redirect('./','You are redirected.');

require_once 'class.State.php';

$msg='';
if(Config::isPost()||Config::isAjax()){
	if(array_sum($_POST['cf'])==$_POST['sum']){
		$msg='You have already confirm your information.';
		$db=$config->PDO();
		if($step==1){
			try{
				require_once 'class.Team.php';
				require_once 'class.Member.php';
				$db->beginTransaction();
				$t=new Team($db);
				$t->id=$s->id;
				
				$ob=new Observer($db);
				$ob->team_id=$s->id;
				
				$mem=new Participant($db);
				$mem->team_id=$s->id;
				
				$t->team_state=State::ST_WAIT;
				$ob->info_state=State::ST_WAIT;
				$mem->info_state=State::ST_WAIT;
				
				if($s->teamState==State::ST_EDITABLE) $t->setState(Team::ROW_TEAM_STATE);
				$ob->setState(State::ST_EDITABLE);
				$mem->setState(State::ST_EDITABLE);
				
				$db->commit();
				$s->changeID(true);
			}catch(Exception $e){
				if($db->inTransaction()) $db->rollBack();
				$msg=Config::e($e);
			}
		}elseif($step==2){
			try{
				require_once 'class.Team.php';
				$db->beginTransaction();
				
				$t=new Team($db);
				$t->id=$s->id;
				$t->post_reg_state=State::ST_WAIT;
				$t->setState(Team::ROW_POST_REG_STATE);
				
				$db->commit();
				$s->changeID(true);
			}catch(Exception $e){
				if($db->inTransaction()) $db->rollBack();
				$msg=Config::e($e);
			}
		}
	}
}
?>
<!doctype html>
<html><!-- InstanceBegin template="/Templates/IMC_reg.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Confirm your Information :Chiang Mai University International Medical Challenge</title>
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
							<li><a href="https://www.facebook.com/CMU.IMC" target="_blank" data-reveal-id="news">NEWS</a></li>
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
  <li class="<?=State::inTime($s->getObserverInfoState(), $config->REG_START_REG, $config->REG_END_REG, true)?>" id="menuObsvInfo"><a href="member.php?no=0" title="Advisor's information">Advisor's information</a></li>
  <? for($i=1;$i<=$config->REG_PARTICIPANT_NUM;$i++):?>
  <li class="<?=State::inTime($s->getParticipantInfoState($i), $config->REG_START_REG, $config->REG_END_REG, true)?>" id="menuPartInfo<?=$i?>"><a href="member.php?no=<?=$i?>" title="<?=Config::ordinal($i, false)?>  participant's information"><?=Config::ordinal($i)?>  participant's information</a></li>
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
<h2><?=State::img($step==1
	?State::inTime($s->cfInfoState,$config->REG_START_REG,$config->REG_END_REG)
	:State::inTime($s->cfPostRegState,$config->REG_START_PAY,$config->REG_END_INFO))?>Confirm your information</h2>
<?php
echo $step==1?State::toHTML(
		State::inTime($s->cfInfoState, $config->REG_START_REG, $config->REG_END_REG),
		array($config->REG_START_REG, $config->REG_END_REG)
	):State::toHTML(
		State::inTime($s->cfPostRegState, $config->REG_START_PAY, $config->REG_END_INFO),
		array($config->REG_START_PAY, $config->REG_END_INFO)
	);

if(($step==1 && State::is($s->cfInfoState, State::ST_EDITABLE, $config->REG_START_REG, $config->REG_END_REG) && $s->cfInfoState==State::ST_EDITABLE)
 || ($step==2 && State::is($s->cfPostRegState,State::ST_EDITABLE, $config->REG_START_PAY, $config->REG_END_INFO) && $s->cfPostRegState==State::ST_EDITABLE)):
?>
<form action="confirm.php?step=<?=$step?>" method="post" id="confirmForm"><fieldset><legend>Confirm Registration information</legend><div>
<? if($step==1):?>
<input name="sum" type="hidden" id="sum" value="4">
<input type="checkbox" name="cf[]" value="1" id="cf_0">
      <label for="cf_0">I agree to the terms and conditions of <a href="http://cmu-imc.med.cmu.ac.th/competition.html" title="CMU-IMC Competition Rule" target="_blank">CMU-IMC Competition Rules</a>.</label><br>
      <input type="checkbox" name="cf[]" value="1" id="cf_1">
      <label for="cf_1">I have read <a href="http://cmu-imc.med.cmu.ac.th/registration.html" title="The Registration" target="_blank">The Registration Guide</a>.</label><br>
      <input type="checkbox" name="cf[]" value="1" id="cf_2">
      <label for="cf_2">I have completed <a href="./" title="all application forms in previous steps" target="_blank">all application forms in the previous steps</a>.</label><br>
      <input type="checkbox" name="cf[]" value="1" id="cf_3">
      <label for="cf_3">I confirm that the information I have given is true.</label>
<? elseif($step==2):?>
<input name="sum" type="hidden" id="sum" value="2">
<input type="checkbox" name="cf[]" value="1" id="cf_0">
      <label for="cf_0">I have completed <a href="post_reg.php">all application forms in the previous steps</a>.</label><br>
      <input type="checkbox" name="cf[]" value="1" id="cf_1">
      <label for="cf_1">I am ready to go to <a href="http://cmu-imc.med.cmu.ac.th" title="CMU-IMC" target="_blank">CMU-IMC</a>.</label>
      <? endif;?>
</div><div><button type="submit" class="button alert">Confirm</button><button type="reset">Cancel</button></div></fieldset>
</form>
<? endif; if(strlen($msg)>0):?><div class="alert-box radius warning"><b><?=$msg?></b></div><? endif;?>
<div class="panel">
  <h4>
<? if($step==1):?><i class="fa fa-lg fa-money"></i> After your information is approved, we recommend you to transfer <a href="../registration.html#fee" target="_blank">the application fee</a> and upload the transfer slip to <a href="pay.php" target="_blank">the registration system</a>
<? else:?><i class="fa fa-lg fa-credit-card"></i> After your information is approved, we recommend you to send numbers, expire dates, and copies of participants' and advisor's passports<? endif;?> as soon as possible.</h4></div><? if($step==1):?><a href="pay.php" class="button right">Next <i class="fa-arrow-circle-right fa"></i></a><? endif;?><!-- InstanceEndEditable --></div></div>
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
    <div id="news" class="reveal-modal" data-reveal>
			<h2>NEWS</h2>
			<p>Please follow us on CMU-IMC facebook page for updated news. Click <a href="http://www.facebook.com/cmu.imc">here</a></p>
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
