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
<script src="js/updateMenuState.js"></script>
<script src="js/save.js"></script>
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
<ul class="side-nav">
  <li class="<?=State::toClass($s->teamState)?>" id="menuTeamInfo"><a href="team.php" title="Team &amp; Institution information">Team &amp; Institution information</a></li>
  <li class="<?=State::toClass($s->getObserverInfoState())?>" id="menuObsvInfo"><a href="#" title="Professor's infomation">Professor's infomation</a></li>
  <? for($i=1;$i<=$config->REG_PARTICIPANT_NUM;$i++):?>
  <li class="<?=State::toClass($s->getParticipantInfoState($i))?>" id="menuPartInfo<?=$i?>"><a href="#" title="<?=Config::ordinal($i)?>  participant's infomation"><?=Config::ordinal($i)?>  participant's infomation</a></li>
  <? endfor;?>
  <li class="<?=State::toClass($s->cfInfoState)?>" id="menuCfInfo"><a href="#" title="Confirmation of Application Form">Confirmation of Application Form</a></li>
  <li class="<?=State::toClass($s->payState)?>" id="menuPay"><a href="#" title="Upload Transaction">Upload Transaction</a></li>
  <li class="<?=State::toClass($s->getObserverPostRegInfoState())?>" id="menuObsvPostReg"><a href="#" title="Update professor's shirt size &amp; passport">Update professor's shirt size &amp; passport</a></li>
  <? for($i=1;$i<=$config->REG_PARTICIPANT_NUM;$i++):?>
  <li class="<?=State::toClass($s->getParticipantPostRegInfoState($i))?>" id="menuPartPostReg<?=$i?>"><a href="#" title="Update <?=Config::ordinal($i)?> participant's shirt size &amp; passport">Update 
    <?=Config::ordinal($i)?>  participant's shirt size &amp; passport</a></li>
  <? endfor;?>
  <li class="<?=State::toClass($s->postRegState)?>" id="menuPostReg"><a href="#" title="Select route &amp; upload team's picture &amp; update arrival time">Select route &amp; upload team's picture &amp; update arrival time</a></li>
  <li class="<?=State::toClass($s->cfPostRegState)?>" id="cfPostReg"><a href="#" title="Confirmation of journey">Confirmation of journey</a></li>
<li class="divider"></li>
  <li><a href="index.php" title="Main page">Main page</a></li>
  <li><a href="index.php#changePW">Change password</a></li>
  <li><a href="logout.php" title="Log out">Log out</a></li>
</ul>
</div><div id="regContent" class="small-12 large-9 columns"><!-- InstanceBeginEditable name="reg_content" -->
<p>Team &amp; Institution information</p>
<div id="teamMsg"><?php
$msg=new Message($db);
$msg->team_id=$s->id;
$msg->load(Message::PAGE_INFO_TEAM);
echo Message::msg($msg);
unset($msg);

$r=!(State::is($s->teamState,State::ST_EDITABLE) && strtotime($config->REG_START_REG)<=time() && time()<strtotime($config->REG_END_REG));
?></div>
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
    <?=Config::country($t->country,$r)?>
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
