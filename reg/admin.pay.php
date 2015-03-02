<?php
require_once 'config.inc.php';
require_once 'class.SesAdm.php';

$sess=SesAdm::check();
if(!$sess) Config::redirect('admin.php','you are not log in.');
if(!$sess->checkPMS(SesAdm::PMS_PARTC)) Config::redirect('home.php','you don\'t have permission here.');

require_once 'admin.pay.scr.php';
?>
<!doctype html>
<html><!-- InstanceBegin template="/Templates/IMC_admin.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Approve transfer slip :Chiang Mai University International Medical Challenge</title>
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
<link href="class.State.php?css=1" rel="stylesheet" type="text/css">
<!-- InstanceBeginEditable name="head" -->
<script src="js/admin.approve.js"></script>
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
<div class="small-12 columns" id="content"><div class="small-12 large-3 columns" id="sidebar">
<ul class="accordion" data-accordion>
    <li class="accordion-navigation">
    	<a href="#profileBar"><i class="fa fa-user-md"></i> Admin's Profile</a>
        <div id="profileBar" class="content active"><b>Student ID:</b> <?=$sess->student_id?><br><b>Nickname:</b> <?=$sess->nickname?></div>
    </li>
    <li class="accordion-navigation">
    	<a href="#adminMenu"><i class="fa fa-bars"></i> Main menu</a>
    	<div class="content" id="adminMenu"><ul class="side-nav"><li><a href="home.php" title="Admin dashboard"><i class="fa fa-home fa-lg"></i> Main page</a></li>
    		<li><a href="home.php#editProfile" title="edit profile"><i class="fa fa-pencil fa-lg"></i> Edit profile</a></li>
    		<li><a href="home.php#changePassword"><i class="fa fa-key fa-lg"></i> Chage password</a></li>
    		<li><a href="logout.php?admin" title="Log out"><i class="fa fa-sign-out fa-lg"></i> Log out</a></li></ul></div>
    </li>
    <li class="accordion-navigation">
    	 <a href="#adminTask"><i class="fa fa-tasks"></i> Admin Task</a>
    	 <div class="content" id="adminTask"><ul class="side-nav">
            <li><a href="admin.team.php" title="Edit team's, participants', and advisors' information"><i class="fa fa-pencil-square-o fa-lg"></i> Edit teams', participants', and advisors' information</a></li>
      		<li><a href="admin.participant.php" title="Summarize information"><i class="fa fa-bar-chart fa-lg"></i> Summarize information</a></li>
      		<li><a href="admin.participant.php?view=team&order=0" title="Participating teams"><i class="fa fa-table fa-lg"></i> Confirmed teams (Order by Team's name)</a></li>
      		<li><a href="admin.participant.php?view=team&order=1" title="Participating teams"><i class="fa fa-table fa-lg"></i> Confirmed teams (Order by Arrival time)</a></li>
            <li><a href="admin.participant.php?view=obs&distinct=0" title="Participating teams"><i class="fa fa-table fa-lg"></i> Confirmed advisors</a></li>
            <li><a href="admin.participant.php?view=obs&distinct=1" title="Participating teams"><i class="fa fa-table fa-lg"></i> Confirmed distinct advisors</a></li>
            <li><a href="admin.participant.php?view=part" title="Participating teams"><i class="fa fa-table fa-lg"></i> Confirmed participant (Medical student)</a></li>
      		<li><hr></li>
      		<li><a href="admin.info.php"><i class="fa fa-check-square-o fa-lg"></i> Approve teams' information: step 1</a></li>
      		<li><a href="admin.pay.php"><i class="fa fa-check-square-o fa-lg"></i> Approve the transfer slip</a></li>
      		<li><a href="admin.post_reg.php"><i class="fa fa-check-square-o fa-lg"></i> Approve teams' information: step 2</a></li>
      		<li><hr></li>
      		<li><a href="admin.edit.php" title="Edit administrator"><i class="fa fa-users fa-lg"></i> Edit administrator</a></li>
      		<li><a href="admin.config.php" title="System configuration"><span class="fa-stack"><i class="fa fa-square fa-stack-2x"></i><i class="fa fa-terminal fa-stack-1x fa-inverse"></i></span> System configuration</a></li>
		</ul></div></li>
</ul>
</div>
<div id="adminContent" class="small-12 large-9 columns"><!-- InstanceBeginEditable name="adminContent" --><h2>Approve Participants' transfer slip</h2>
<? if(isset($_GET['id'])):?>
<h5><a href="admin.team.php?id=<?=$_GET['id']?>" target="_blank">View team's information</a></h5>
<?php
	echo $ajax->toMsg();
else:?>
<a href="admin.pay.php#reload" class="button" id="reload">Reload</a>
<?php
echo $ajax->toMsg();
endif;?><!-- InstanceEndEditable --></div></div>
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
			<p class="lead">Any news will be posted on Facebook Page: CMU International Medical Challenge.</p>
			<p>please check out the news <a href="http://www.facebook.com/cmu.imc">here</a> to go to Facebook Page.</p>
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
				$(this).text(
					event.strftime('%D days')
				);
			});
		});
	</script>
</body>
<!-- InstanceEnd --></html>
