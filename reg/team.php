<?php
require_once 'config.inc.php';
require_once 'class.SesPrt.php';

$s=SesPrt::check(true,true);
if(!$s) Config::redirect('login.php','You do not log in. Please log in.');

require_once 'class.SKAjaxReg.php';
$ajax=new SKAjaxReg();
if(Config::isPost()||Config::isAjax()) require_once 'team.scr.php';

require_once 'class.Message.php';
require_once 'class.Team.php';

$db=$config->PDO();

$t=new Team($db);
$t->id=$s->id;
$t->submitLoad();
?>
<!doctype html>
<html><!-- InstanceBegin template="/Templates/IMC_reg.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Update Team's information :Chiang Mai University International Medical Challenge</title>
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
<script src="js/save.js"></script>
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
							<li><a href="contact.html">CONTACT US</a></li>
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
        <a href="#sbTeamInfo"><i class="fa fa-user-md"></i> Profile</a>
        <div id="sbTeamInfo" class="content active">
            <b>Team's name:</b> <?=$s->teamName?><br>
            <b>Institution:</b> <?=$s->institution?><br>
            <b>University:</b> <?=$s->university?><br>
            <b>Country:</b> <?=$s->country?><br><br>
            <b>Progression</b>
            <div id="progression" class="progress round"><span class="meter" style="width:<?=$s->getProgression()?>%"></span></div>
        </div>
    </li>
    <li class="accordion-navigation">
        <a href="#sbMenu"><i class="fa fa-bars"></i> Main menu</a>
        <div id="sbMenu" class="content"><ul class="side-nav">
        <li class="divider"></li>
  <li><a href="index.php" title="Main page">Main page</a></li>
  <li><a href="index.php#changePW">Change password</a></li>
  <li><a href="logout.php" title="Log out">Log out</a></li></ul>
        </div>
    </li>
    <li class="accordion-navigation">
        <a href="#sbStep"><i class="fa fa-check-square"></i> Steps of Registration</a>
        <div id="sbStep" class="content">
        <ul class="side-nav">
  <li class="<?=State::inTime($s->teamState, $config->REG_START_REG, $config->REG_END_REG, true)?>" id="menuTeamInfo"><a href="team.php" title="Team &amp; Institution information">Team &amp; Institution information</a></li>
  <li class="<?=State::inTime($s->getObserverInfoState(), $config->REG_START_REG, $config->REG_END_REG, true)?>" id="menuObsvInfo"><a href="member.php?no=0" title="Advisor's infomation">Advisor's infomation</a></li>
  <? for($i=1;$i<=$config->REG_PARTICIPANT_NUM;$i++):?>
  <li class="<?=State::inTime($s->getParticipantInfoState($i), $config->REG_START_REG, $config->REG_END_REG, true)?>" id="menuPartInfo<?=$i?>"><a href="member.php?no=<?=$i?>" title="<?=Config::ordinal($i, false)?>  participant's infomation"><?=Config::ordinal($i)?>  participant's infomation</a></li>
  <? endfor;?>
  <li class="<?=State::inTime($s->cfInfoState, $config->REG_START_REG, $config->REG_END_REG, true)?>" id="menuCfInfo"><a href="confirm.php?step=1" title="Confirmation of Application Form">Confirmation of Application Form</a></li>
  <li><hr></li>
  <li class="<?=State::inTime($s->payState, $config->REG_START_PAY, $config->REG_END_PAY, true)?>" id="menuPay"><a href="pay.php" title="Upload Transaction">Upload Transaction</a></li>
  <li><hr></li>
  <li class="<?=State::inTime($s->postRegState, $config->REG_START_PAY, $config->REG_END_INFO, true)?>" id="menuPostReg"><a href="post_reg.php" title="Select route &amp; upload team's picture &amp; update arrival time">Update your journey</a></li>
  <li class="<?=State::inTime($s->cfPostRegState, $config->REG_START_PAY, $config->REG_END_INFO, true)?>" id="cfPostReg"><a href="confirm.php?step=2" title="Confirmation of journey">Confirmation of the journey</a></li>
</ul>
        </div>
    </li>
</ul>
</div><div id="regContent" class="small-12 large-8 columns"><!-- InstanceBeginEditable name="reg_content" -->
<h2><?=State::img(State::inTime($s->teamState,$config->REG_START_REG,$config->REG_END_REG))?>Team &amp; Institution information</h2>
<?php
echo State::toHTML(
	State::inTime($s->teamState,$config->REG_START_REG,$config->REG_END_REG),
	array($config->REG_START_REG,$config->REG_END_REG)
);

$msg=new Message($db);
$msg->team_id=$s->id;
$msg->show_page=Message::PAGE_INFO_TEAM;
echo $msg;
unset($msg);

$r=!State::is($s->teamState,State::ST_EDITABLE,$config->REG_START_REG,$config->REG_END_REG);
?>
<form action="team.php" method="post" id="form" data-abide data-action="team.scr.php?updateInfo">
<fieldset class="require">
  <legend>Team's information</legend>
  <div>
  <label class="require">Email for overall contact
    <input name="email" type="email" required id="email" value="<?=$t->email?>"<?=Config::readonly($r)?>>
  </label>
</div>
<div>
  <label class="require">Team's name
    <input name="team_name" type="text" required id="team_name" value="<?=$t->team_name?>" maxlength="40"<?=Config::readonly($r)?>>
  </label>
</div>
<div>
  <label class="require">Institution
    <input name="institution" type="text" id="institution" value="<?=$t->institution?>" required<?=Config::readonly($r)?>>
  </label>
</div><div>
  <label class="require">University
    <input name="university" type="text" id="university" value="<?=$t->university?>" required<?=Config::readonly($r)?>>
  </label>
</div><div>
  <label class="require">Address
    <textarea name="address" rows="5" id="address"<?=Config::readonly($r)?>><?=$t->address?></textarea>
  </label>
</div><div>
  <label class="require">Country
    <?=Team::country($t->country,$r)?>
  </label>
</div><div>
  <label class="require">Institution's telephone number
    <input name="phone" type="phone" id="phone" value="<?=$t->phone?>" placeholder="(with country code) +XXxxxxxx"<?=Config::readonly($r)?>>
  </label>
</div><? if(!$r):?><div>
  <button type="submit" name="save" id="save" value="save">save</button>
  <button type="reset" name="cancel" id="button" value="cancel">cancel</button><? endif;?>
</div></fieldset>
</form>
<?=$ajax->toMsg()?>
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
