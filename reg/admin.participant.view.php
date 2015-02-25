<?php
require_once 'config.inc.php';
require_once 'class.SesAdm.php';

$sess=SesAdm::check();
if(!$sess) Config::redirect('admin.php','you are not log in.');

require_once 'class.Team.php';
require_once 'class.Member.php';
require_once 'admin.team.view.php';
// print mode
function printMode($title, $body){
	ob_start();?>
<!--<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?=$title?> :Chiang Mai University International Medical Challenge</title>
<link href="../css/foundation.min.css" rel="stylesheet" type="text/css">
<link href="../css/foundation.min.css" rel="stylesheet" type="text/css">
</head>

<body><?=$body?><p><small>Print at: <?=date('Y-m-d H:i:s e')?></small></p>
</body>
</html>-->
<?php
	return ob_get_clean();
}

// summarize

// table team --> admin.team.view.php
function teamTable(PDO $db){
	ob_start();?>
<h2>List of participating teams <small><a href="admin.participant.php?print&view=team" target="_blank">Print</a></small></h2>
<?php
	echo teamList(new Team($db), 'admin.team.php', Team::ROW_ARRIVE_TIME, false);
	return ob_get_clean();
}

// table part
function partTable(PDO $db){
	ob_start();
	$t=new Team($db);
	$p=new Participant($db);
	?>
<h2>List of participants (students) <small><a href="admin.participant.php?print&view=part" target="_blank">Print</a></small></h2>
    <?php
	echo Config::toTable($p->getList(true, $t->getIDList()), Participant::forTableRow());
	return ob_get_clean();
}

// table obs(distinct)

function obsTable(Observer $m, $distinct){
	ob_start();
	$t=new Team($db);
	$o=new Observer($db);
	?>
<h2>List of advisors <small><a href="admin.participant.php?print&view=observer&distinct=<?=$distinct?>" target="_blank">Print</a></small></h2>
    <?php
	echo Config::toTable($o->getList(true, $t->getIDList()), Observer::forTableRow());
	return ob_get_clean();
}

?>