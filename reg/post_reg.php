<?php
require_once 'config.inc.php';
require_once 'class.SesPrt.php';

$s=SesPrt::check(true,true);
if(!$s) Config::redirect('login.php','You do not log in. Please log in.');

if(Config::isPost()||Config::isAjax()) require_once 'post_reg.scr.php';

$sec=false;
if(isset($_GET['sec']))
	if(is_numeric($_GET['sec'])) $sec=intval($_GET['sec']);

$page='';

$db=$config->PDO();
switch($sec){
	case 1:
		$page='Trip selection';
		break;
	case 2:
		$page='Upload team\'s photo';
		break;
	case 3:
		$page='Transportation info';
		break;
	case false:
	default:
		 Config::redirect('post_reg.php?sec=1','redirecting');
}

if(!isset($t) && ($sec==1 || $sec==3)){
	require_once 'class.Team.php';
	$t=new Team($db);
	$t->id=$s->id;
	$t->load();
}
if($sec==2 || $sec==3)
	require_once 'class.UploadImage.php';

require_once 'class.Message.php';
require_once 'class.State.php';
?>
<!doctype html>
<html><!-- InstanceBegin template="/Templates/IMC_reg.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0" />
<!-- InstanceBeginEditable name="doctitle" -->
<title><?=$page?> :Chiang Mai University International Medical Challenge</title>
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
<script src="js/foundation-datepicker.js"></script>
<script src="js/jquery.maskedinput.min.js"></script>
<script src="js/jquery.form.min.js"></script>
<script src="js/post_reg.js"></script>

<link href="css/foundation-datepicker.css" rel="stylesheet" type="text/css">
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
</div><div id="regContent" class="small-12 large-8 columns"><!-- InstanceBeginEditable name="reg_content" --><h2><?=State::img(State::inTime($s->postRegState,$config->REG_START_PAY,$config->REG_END_INFO)).' '.$page?></h2>
<?php
echo State::toHTML(State::inTime($s->postRegState,$config->REG_START_PAY,$config->REG_END_INFO));

$msg=new Message($db);
$msg->team_id=$s->id;
$msg->show_page=Message::PAGE_POST_REG_TEAM;
echo $msg;
unset($msg);

$r=!State::is($s->postRegState,State::ST_EDITABLE,$config->REG_START_PAY,$config->REG_END_INFO);

if($sec==1):
?>
<form action="post_reg.php?sec=1" method="post" id="infoForm">
<fieldset><legend>Routes of Chiang Mai Tour</legend>
<p><a href="../cm_tour.html" target="_blank"><i class="fa fa-map-marker"></i> Information of routes of Chiang Mai Tour</a> <input name="form" type="hidden" id="form" value="route">
</p>
<?php echo $t->routeForm($r); if(!$r):?>
<div><button type="submit" name="submitInfo">Save</button><button type="reset" name="resetInfo">Cancel</button></div><? endif;?>
</fieldset></form>
<?php
	if(!isset($ajax)){
		require_once 'class.SKAjaxReg.php';
		$ajax=new SKAjaxReg();
	}
	echo $ajax->toMsg();
endif;

if($sec==2):
	$img=new UploadImage();
	$img->team_id=$s->id;
	if(!$r):
?>
<form action="post_reg.php" method="post" enctype="multipart/form-data" id="photoForm"><fieldset class="require">
  <legend>Upload team's photo</legend>
  <div>
    <label class="require">Image file <?=$img->toForm($r)?><input name="upload" type="hidden" id="upload" value="photo"></label>
  </div><div><button type="submit" name="submitUpload">Upload</button><button type="reset" name="resetUpload">Cancel</button></div></fieldset></form>
<?php
	endif;
	if(!isset($teamAjax)){
		require_once 'class.SKAjaxReg.php';
		$teamAjax=new SKAjaxReg();
		$teamAjax->msgID='teamAjax';
		
		$teamAjax->message=$img->toImgTeamPhoto();
	}
	echo $teamAjax->toMsg();
endif;

if($sec==3):
	$img=new UploadImage();
	$img->team_id=$s->id;
?>
<form action="post_reg.php?sec=3" method="post" id="infoForm">
<fieldset><legend>Type/Time of Arrival &amp; Departure</legend>
  <div>
    <label class="require">Arrival time (to Chiang Mai) <small><a href="../local_information.html#other" target="_blank">In Thailand timezone (UTC+07:00)</a></small>
      <input name="form" type="hidden" id="form" value="ticket">
      <input name="arrive_time" type="text" class="datetime" id="arrive_time" value="<?=$t->arrive_time?>"<?=Config::readonly($r)?>>
  </label>
</div>
<div>
  <label class="require">Expected type of arrival <small> Airplane, Bus, Train, Van</small>
    <input name="arrive_by" type="text" id="arrive_by" value="<?=$t->arrive_by?>"<?=Config::readonly($r)?>></label>
</div>
<div>
  <label>Departure time (from Chiang Mai) <small><a href="../local_information.html#other" target="_blank">In Thailand timezone (UTC+07:00)</a></small>
    <input name="depart_time" type="text" class="datetime" id="depart_time" value="<?=$t->depart_time?>"<?=Config::readonly($r)?>>
  </label>
</div>
<div>
  <label>Expected type of departure <small>Airplane, Bus, Train, Van</small>
    <input name="depart_by" type="text" id="depart_by" value="<?=$t->depart_by?>"<?=Config::readonly($r)?>>
  </label>
</div>
<? if(!$r):?>
      <div><button type="submit" name="submitInfo">Save</button><button type="reset" name="resetInfo">Cancel</button></div>
<? endif;?></fieldset></form>
<?php
	if(!isset($ajax)){
		require_once 'class.SKAjaxReg.php';
		$ajax=new SKAjaxReg();
	}
	echo $ajax->toMsg();
?>
<hr>
<h3 data-magellan-destination="ticket" id="ticket">Upload arrival ticket (Arrival to Chiang Mai)</h3>
<? if(!$r):?>
<form action="post_reg.php?sec=3" method="post" enctype="multipart/form-data" id="ticketForm"><fieldset class="require">
  <legend>Upload arrival ticket</legend>
  <div><label class="require">Image file <?=$img->toForm($r)?>
    <input name="upload" type="hidden" id="upload" value="ticket">
  </label>
  </div><div><button type="submit" name="submitUpload">Upload</button><button type="reset" name="resetUpload">Cancel</button></div></fieldset></form>
<?php
	endif;
	if(!isset($ticketAjax)){
		require_once 'class.SKAjaxReg.php';
		$ticketAjax=new SKAjaxReg();
		$ticketAjax->msgID='ticketAjax';
		
		$ticketAjax->message=$img->toImgTicket();
	}
	echo $ticketAjax->toMsg();
endif;
?><a href="<?=$sec>=3?'confirm.php?step=2':'post_reg.php?sec='.($sec+1)?>" class="button right">Next <i class="fa-arrow-circle-right fa"></i></a>
<!-- InstanceEndEditable --></div></div>
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
