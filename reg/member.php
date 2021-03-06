<?php
require_once 'config.inc.php';
require_once 'class.SesPrt.php';

$s=SesPrt::check(true,true);
if(!$s) Config::redirect('login.php','You do not log in. Please log in.');

if(Config::isPost()||Config::isAjax()) require_once 'member.scr.php';

$no=isset($_GET['no'])?(is_numeric($_GET['no'])?intval($_GET['no']):-1):-1;
if($no<0||$no>$config->REG_PARTICIPANT_NUM) Config::redirect('member.php?no=0','Redirecting...');
$who=array(
	$no==0?'Advisor':Config::ordinal($no,true).' participant',
	$no==0?'Advisor':Config::ordinal($no,false).' participant'
);

$db=$config->PDO();
if(!isset($member)){
	require_once 'class.Member.php';
	$member=$no==0?new Observer($db):new Participant($db);
	$member->id=false;
	$member->team_id=$s->id;
	if($no>0) $member->part_no=$no;
	$member->load();
}

require_once 'class.Message.php';
require_once 'class.State.php';
?>
<!doctype html>
<html><!-- InstanceBegin template="/Templates/IMC_reg.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Edit <?=$who[1]?>'s information :Chiang Mai University International Medical Challenge</title>
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
<script src="js/member.js"></script>
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
</div><div id="regContent" class="small-12 large-8 columns"><!-- InstanceBeginEditable name="reg_content" -->
<h2><?=State::img(State::inTime($s->getParticipantInfoState($no), $config->REG_START_REG,$config->REG_END_REG)).$who[0]?>'s Information</h2>
<?php
echo State::toHTML(
	State::inTime($s->getParticipantInfoState($no),$config->REG_START_REG,$config->REG_END_REG),
	array($config->REG_START_REG,$config->REG_END_REG)
);

$msg=new Message($db);
$msg->team_id=$s->id;
$msg->show_page=Message::PAGE_INFO_PART($no);
echo $msg;
unset($msg);

$r=!State::is($s->getParticipantInfoState($no),State::ST_EDITABLE,$config->REG_START_REG,$config->REG_END_REG);

if($no>0):?>
<div class="magellan-container" data-magellan-expedition="fixed">
  <dl class="sub-nav">
    <dd data-magellan-arrival="info"><a href="#info">Application form</a></dd>
    <dd data-magellan-arrival="upload"><a href="#upload">Upload</a></dd>
  </dl>
</div>
<hr>
<h3 data-magellan-destination="info" id="info">Application form</h3>
<? else:?>
<div class="alert-box warning radius" data-alert><i class="fa fa-2x  pull-left fa-exclamation-circle"></i> Complete this section if your team has an advisor. If not, please skip this section. <a href="#" class="close">&times;</a></div>
<? endif;?>
   <form action="member.php?no=<?=$no?>" method="post" name="infoForm" id="infoForm">
      <fieldset>
        <legend>General Information</legend>
        <div>
          <label class="require">Title
            <input name="id" type="hidden" id="id" value="<?=$member->id?>">
            <input name="part_no" type="hidden" id="part_no" value="<?=$no?>">
            <input name="title" type="text" id="title" value="<?=$member->title?>"<?=Config::readonly($r)?>>
          </label>
        </div>
         <div><label class="require">Firstname
            <input name="firstname" type="text" id="firstname" value="<?=$member->firstname?>"<?=Config::readonly($r)?>>
          </label></div>
        <div>
          <label>Middlename
<input name="middlename" type="text" id="middlename" value="<?=$member->middlename?>"<?=Config::readonly($r)?>>
          </label></div>
           <div>
             <label class="require">Lastname
<input name="lastname" type="text" id="lastname" value="<?=$member->lastname?>"<?=Config::readonly($r)?>>
          </label></div>
<?php
echo Member::gender($member->gender,$r);
if($no>0):?>
           <div>
             <label class="require">Medical student year
               <input name="std_y" type="text" id="std_y" value="<?=$member->std_y?>"<?=Config::readonly($r)?>>
          </label></div><? endif;?>
           <div>
             <label class="require">Date of Birth <small>Click on the form to show calendar, and click on title bar of calendar to change month, or double click it to select year.</small>
               <input name="birth" type="text" class="date" id="birth" value="<?=$member->birth?>"<?=Config::readonly($r)?>>
          </label></div>
           <div>
             <label class="require">Nationality
               <input name="nationality" type="text" id="nationality" value="<?=$member->nationality?>"<?=Config::readonly($r)?>>
          </label></div>
      </fieldset>
      <fieldset>
        <legend>Contact</legend>
        <div>
          <label>Mobile phone number <small>with country code</small>
            <input name="phone" type="tel" id="phone" placeholder="+xx xxx xxx xxx ..." value="<?=$member->phone?>"<?=Config::readonly($r)?>>
          </label></div>
                   <div>
                     <label>Email address <small>You can fill out the same email as log-in email.</small>
                       <input name="email" type="email" id="email" value="<?=$member->email?>"<?=Config::readonly($r)?>>
          </label></div>
                   <div>
                     <label>Facebook Profile name/URL
                       <input name="fb" type="text" id="fb" placeholder="Mark Zuckerberg or https://www.facebook.com/zuck" value="<?=$member->fb?>"<?=Config::readonly($r)?>>
          </label></div>
                   <div>
                     <label>Twitter
                       <input name="tw" type="text" id="tw" placeholder="@twitter" value="<?=$member->tw?>"<?=Config::readonly($r)?>>
          </label></div><? if($no>0):?>
         <div>
           <label class="require">Emergency contact <small>Phone, Email, Online Chat, Social Networking ...</small>
             <textarea name="emerg_contact" rows="5" id="emerg_contact" <?=config::readonly($r)?>="<?=Config::readonly($r)?>"><?=$member->emerg_contact?></textarea>
           </label></div><? endif;?>
      </fieldset>
      <fieldset>
        <legend>Lifestyle</legend>
         <div>
           <label class="require">Religion
             <input name="religion" type="text" id="religion" value="<?=$member->religion?>"<?=Config::readonly($r)?>>
          </label></div>
          <div>
            <label>Preferred specific cuisine
              <textarea name="cuisine" rows="5" id="cuisine"<?=Config::readonly($r)?>><?=$member->cuisine?></textarea>
          </label></div>
          <div>
            <label>Food/Drug allergy
              <textarea name="allergy" rows="5" id="allergy"<?=Config::readonly($r)?>><?=$member->allergy?></textarea>
          </label></div>
          <div>
            <label>Underlying disease
              <textarea name="disease" rows="5" id="disease"<?=Config::readonly($r)?>><?=$member->disease?></textarea>
          </label></div>
          <div>
            <label>Other requirements
              <textarea name="other_req" rows="5" id="other_req"<?=Config::readonly($r)?>><?=$member->other_req?></textarea>
          </label></div>
      </fieldset>
      <fieldset><legend>Shirt size</legend>
        <a href="../pictures/shirt_size_chart.jpg" target="_blank" class="th"><img src="../pictures/shirt_size_chart.jpg" alt="Shirt size chart"></a><br><br>
<?=Participant::shirtSize($member->shirt_size,$r)?>
      </fieldset><? if(!$r):?>
      <fieldset class="require"><legend>Save</legend>
      <div><button type="submit" name="submitInfo">Save</button><button type="reset" name="resetInfo">Cancel</button></div>
      </fieldset>
      <?php
	  endif;
if(!isset($ajax)){
	require_once 'class.SKAjaxReg.php';
	$ajax=new SKAjaxReg();
}
echo $ajax->toMsg();
unset($ajax);
?>
    </form>
    <? if($no>0):?><hr>
    <h3 data-magellan-destination="upload" id="upload">Upload <?=$who[0]?>'s copy of student ID card or certificate of student status</h3>
   <form action="member.php?no=<?=$no?>" method="post" enctype="multipart/form-data" name="upload" id="uploadForm">
    <?php
require_once 'class.UploadImage.php';
$img=new UploadImage();
$img->team_id=$s->id;

if(!isset($uploadAjax)){
	require_once 'class.SKAjaxReg.php';
	
	$uploadAjax=new SKAjaxReg();
	$uploadAjax->msgID='uploadMsg';
	$uploadAjax->message=$img->toImgPartStudentCard($no);
}
if(!$r):?>
   <fieldset class="require">
        <legend>Upload <?=$who[0]?>'s copy of student ID card or certificate of student status</legend>
        <div class="panel"><h3>Recommended image properties</h3>
    <ul>
      <li>Resolution: &ge;200 dpi (dot per inch)</li>
      <li>File type (file extension): JPEG (*.jpg, *.jpeg), PNG (*.png) or GIF (*.gif)</li>
      <li>Size: &lt;50 KB (recommended), &le; 8 MB (the maximum size)</li>
    </ul></div>
         <div><label class="require">Image file
       <?=$img->toForm($r)?>
       <input name="part_no" type="hidden" id="part_no" value="<?=$no?>">
        <input name="id" type="hidden" id="id" value="<?=$member->id?>">
        </label>
        </div>
        <div><button type="submit" name="submitUpload">Upload</button><button type="reset" name="resetUpload">Cancel</button></div>
      </fieldset>
 <?php
 endif;
 echo $uploadAjax->toMsg();
 ?></form><? endif;?>
<a href="<?=$no>=$config->REG_PARTICIPANT_NUM?'confirm.php?step=1':'member.php?no='.($no+1)?>" class="button right">Next <i class="fa-arrow-circle-right fa"></i></a>
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
