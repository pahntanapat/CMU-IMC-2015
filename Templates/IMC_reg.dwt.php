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
<script src="../jquery-1.11.2.min.js"></script>
<script src="../jquery-migrate-1.2.1.min.js"></script>
<script src="../reg/js/skajax.js"></script>
<link rel="stylesheet" href="../imc_main.css">
<!-- InstanceBeginEditable name="head" -->
<!-- TemplateBeginEditable name="head" -->
<!-- TemplateEndEditable -->
<!-- InstanceEndEditable -->
</head>

<body>
<div id="mainMenu"><ul>
  <li><a href="../index.html" title="Homepage">home</a></li>
  <li><a href="../reg/login.php" title="log in">log in</a></li>
  <li><a href="../regist.html" title="registration">registration</a></li></ul></div>
<div id="content"><!-- InstanceBeginEditable name="Content" --><div id="sidemenu"><div id="progression">Progress: <?=$s->getProgression()?></div>
<ol>
  <li><?=State::img($s->teamState)?> Team &amp; Institution information</li>
  <li><?=State::img($s->getObserverInfoState())?> Professor's infomation</li>
  <? for($i=1;$i<=$config->REG_PARTICIPANT_NUM;$i++):?>
  <li><?=State::img($s->getParticipantInfoState($i))?> <?=Config::ordinal($i)?> participant's infomation</li>
  <? endfor;?>
  <li><?=State::img($s->cfInfoState)?> Confirmation of Application Form</li>
  <li><?=State::img($s->payState)?> Upload Transaction</li>
  <li><?=State::img($s->getObserverPostRegInfoState())?> Update professor's shirt size &amp; passport</li>
  <? for($i=1;$i<=$config->REG_PARTICIPANT_NUM;$i++):?>
  <li><?=State::img($s->getParticipantPostRegInfoState($i))?> Update
<?=Config::ordinal($i)?>
    participant's shirt size &amp; passport</li>
  <? endfor;?>
  <li><?=State::img($s->postRegState)?> Select route &amp; upload team's picture &amp; update arrival time</li>
  <li><?=State::img($s->cfPostRegState)?> Confirmation of journey</li>
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
