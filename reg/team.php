<?php
require_once 'config.inc.php';
require_once 'class.SesPrt.php';

$s=SesPrt::check(true,true);
if(!$s) Config::redirect('login.php','You do not log in. Please log in.');

require_once 'class.Element.php';
$elem=new Element();
if(Config::isPost()||Config::isAjax()) require_once 'team.scr.php';

require_once 'class.Message.php';
require_once 'class.State.php';
require_once 'class.Team.php';

$db=$config->PDO();
$t=new Team($db);
$t->id=$s->id;
if(!Config::isPost()) $t->load();
?>
<!doctype html>
<html><!-- InstanceBegin template="/Templates/IMC_reg.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta charset="utf-8">
<!-- InstanceBeginEditable name="doctitle" -->
<title>Chiang Mai University International Medical Challenge</title>
<!-- InstanceEndEditable -->
<script src="../js/jquery-1.11.2.min.js"></script>
<script src="../js/jquery-migrate-1.2.1.min.js"></script>
<script src="js/skajax.js"></script>
<link rel="stylesheet" href="../css/imc_main.css">

<link href="class.State.php?css=1" rel="stylesheet" type="text/css">
<!-- InstanceBeginEditable name="head" -->
<script src="js/save.js"></script>
<!-- InstanceEndEditable -->

</head>

<body>
<div id="mainMenu"><ul>
  <li><a href="../index.html" title="Homepage">home</a></li>
  <li><a href="login.php" title="log in">log in</a></li>
  <li><a href="../regist.html" title="registration">registration</a></li></ul></div>
<div id="content"><div id="sidemenu">
  <div>Team's name: <?=$s->teamName?><br>
    Institution: <?=$s->institution?><br>
    University: <?=$s->university?><br>
    Country: <?=$s->country?>
  </div>
<div id="progression">Progress: <?=$s->getProgression()?></div>
<ol>
  <li class="<?=State::toClass($s->teamState)?>"><?=State::img($s->teamState)?> 
    <a href="team.php" title="Team &amp; Institution information">Team &amp; Institution information</a></li>
  <li class="<?=State::toClass($s->getObserverInfoState())?>"><?=State::img($s->getObserverInfoState())?> Professor's infomation</li>
  <? for($i=1;$i<=$config->REG_PARTICIPANT_NUM;$i++):?>
  <li class="<?=State::toClass($s->getParticipantInfoState($i))?>"><?=State::img($s->getParticipantInfoState($i))?> <?=Config::ordinal($i)?> participant's infomation</li>
  <? endfor;?>
  <li class="<?=State::toClass($s->cfInfoState)?>"><?=State::img($s->cfInfoState)?> Confirmation of Application Form</li>
  <li class="<?=State::toClass($s->payState)?>"><?=State::img($s->payState)?> Upload Transaction</li>
  <li class="<?=State::toClass($s->getObserverPostRegInfoState())?>"><?=State::img($s->getObserverPostRegInfoState())?> Update professor's shirt size &amp; passport</li>
  <? for($i=1;$i<=$config->REG_PARTICIPANT_NUM;$i++):?>
  <li class="<?=State::toClass($s->getParticipantPostRegInfoState($i))?>"><?=State::img($s->getParticipantPostRegInfoState($i))?> Update
<?=Config::ordinal($i)?>
    participant's shirt size &amp; passport</li>
  <? endfor;?>
  <li class="<?=State::toClass($s->postRegState)?>"><?=State::img($s->postRegState)?> Select route &amp; upload team's picture &amp; update arrival time</li>
  <li class="<?=State::toClass($s->cfPostRegState)?>"><?=State::img($s->cfPostRegState)?> Confirmation of journey</li>
</ol>
<ul>
  <li><a href="index.php" title="Main page">Main page</a></li>
  <li><a href="index.php#changePW">Change password</a></li>
  <li><a href="logout.php" title="Log out">Log out</a></li>
</ul>
</div><div id="regContent"><!-- InstanceBeginEditable name="reg_content" -->
<p>Team &amp; Institution information</p>
<div id="teamMsg"><?php
$msg=new Message($db);
$msg->team_id=$s->id;
$msg->load(Message::PAGE_INFO_TEAM);
echo Message::msg($msg);
unset($msg);
?></div>
<form action="team.php" method="post" id="form" data-abide data-action="team.scr.php?updateInfo">
<fieldset class="require">
  <legend>Team's information</legend>
  <div>
  <label class="require">Email for overall contact
    <input name="email" type="email" required id="email" value="<?=$elem->val('email',$t->email)?>">
  </label>
</div>
<div>
  <label class="require">Team's name
    <input name="team_name" type="text" required id="team_name" value="<?=$elem->val('team_name',$t->team_name)?>" maxlength="40">
  </label>
</div>
<div>
  <label class="require">Institution
    <input name="institution" type="text" id="institution" value="<?=$elem->val('institution',$t->institution)?>" required>
  </label>
</div><div>
  <label class="require">University
    <input name="university" type="text" id="university" value="<?=$elem->val('university',$t->university)?>" required>
  </label>
</div><div>
  <label class="require">Address
    <textarea name="address" id="address"><?=$elem->val('address',$t->address)?></textarea>
  </label>
</div><div>
  <label class="require">Country
    <?=Config::country($elem->val('country',$t->country))?>
  </label>
</div><div>
  <label class="require">Institution's telephone number
    <input name="phone" type="phone" id="phone" value="<?=$elem->val('phone',$t->phone)?>" placeholder="(with country code) +XXxxxxxx">
  </label>
</div><div>
  <input type="submit" name="save" id="save" value="save">
  <input type="reset" name="cancel" id="button" value="cancel">
</div></fieldset>
</form>
<div id="msg"><?=$elem->msg?></div>
<!-- InstanceEndEditable --></div></div>
<div id="footer"><a href="admin.php" title="staff only">staff only</a></div>
</body>
<!-- InstanceEnd --></html>
