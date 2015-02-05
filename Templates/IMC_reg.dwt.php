<?php
require_once 'config.inc.php';
require_once 'class.SesPrt.php';

$s=SesPrt::check(true,true);
if(!$s) Config::redirect('login.php','You do not log in. Please log in.');

require_once 'class.Element.php';
$elem=new Element();
if(Config::isPost()||Config::isAjax()) require_once 'index.scr.php';

require_once 'class.Message.php';
require_once 'class.State.php';
?>
<!doctype html>
<html><!-- InstanceBegin template="/Templates/IMC_Main.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta charset="utf-8">
<!-- InstanceBeginEditable name="doctitle" -->
<title>Chiang Mai University International Medical Challenge</title>
<!-- InstanceEndEditable -->
<script src="../js/jquery-1.11.2.min.js"></script>
<script src="../js/jquery-migrate-1.2.1.min.js"></script>
<script src="../reg/js/skajax.js"></script>
<link rel="stylesheet" href="../css/imc_main.css">
<!-- InstanceBeginEditable name="head" -->
<link href="../reg/class.State.php?css=1" rel="stylesheet" type="text/css">
<!-- TemplateBeginEditable name="head" -->
<!-- TemplateEndEditable -->
<!-- InstanceEndEditable -->
</head>

<body>
<div id="mainMenu"><ul>
  <li><a href="../index.html" title="Homepage">home</a></li>
  <li><a href="../reg/login.php" title="log in">log in</a></li>
  <li><a href="../regist.html" title="registration">registration</a></li></ul></div>
<div id="content"><!-- InstanceBeginEditable name="Content" --><div id="sidemenu">
  <div>Team's name: <?=$s->teamName?><br>
    Institution: <?=$s->institution?><br>
    University: <?=$s->university?><br>
    Country: <?=$s->country?>
  </div>
<div id="progression">Progress: <?=$s->getProgression()?></div>
<ol>
  <li class="<?=State::toClass($s->teamState)?>"><?=State::img($s->teamState)?> 
    <a href="../reg/team.php" title="Team &amp; Institution information">Team &amp; Institution information</a></li>
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
  <li><a href="../reg/index.php" title="Main page">Main page</a></li>
  <li><a href="../reg/index.php#changePW">Change password</a></li>
  <li><a href="../reg/logout.php" title="Log out">Log out</a></li>
</ul>
</div><div id="regContent"><!-- TemplateBeginEditable name="reg_content" -->reg_content<!-- TemplateEndEditable --></div><!-- InstanceEndEditable --></div>
<div id="footer"><a href="../reg/admin.php" title="staff only">staff only</a></div>
</body>
<!-- InstanceEnd --></html>
