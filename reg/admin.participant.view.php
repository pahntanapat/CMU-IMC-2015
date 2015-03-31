<?php
require_once 'config.inc.php';
require_once 'class.SesAdm.php';

$sess=SesAdm::check();
if(!$sess) Config::redirect('admin.php','you are not log in.');

require_once 'class.State.php';
require_once 'class.Team.php';
require_once 'class.Member.php';
require_once 'admin.team.view.php';
// print mode
function printMode($title, $body){
	ob_start();?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?=$title?> :Chiang Mai University International Medical Challenge</title>
<link href="../css/foundation.min.css" rel="stylesheet" type="text/css">
<link href="../css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="css/admin.participant.css" rel="stylesheet" type="text/css">
</head>

<body><?=$body?><p><small>Print at: <?=date('Y-m-d H:i:s e')?></small></p>
</body>
</html>
<?php
	return ob_get_clean();
}
function toTable(PDOStatement $stm, $field='field'){
	ob_start();?>
<table><tr><th scope="col"><?=$field?></th><th scope="col">N</th></tr>
<? while($r=$stm->fetch(PDO::FETCH_NUM)):?>
<tr><td><?=$r[0]?></td>
<td><?=$r[1]?></td></tr>
<? endwhile;?>
</table>
<?php
	return ob_get_clean();
}
// summarize
function summarize(PDO $db){
	ob_start();
	$tmp=new Team($db);/**/
	?>
    <h2>Summarized information <small class="hide-for-print"><a href="admin.participant.php?print&view=sum" target="_blank" class="button">Print</a> <a href="admin.participant.php?view=sum" class="button reload">Reload</a></small></h2>
    <ol class="hide-for-print">
      <li><a href="#team">Team</a></li>
      <li><a href="#obs">Advisors</a></li>
      <li><a href="#part">Participants (Medical students)</a></li>
    </ol>
<h3 id="team">Teams (<?=$tmp->countField()?> teams)</h3>
<h4>Status of team's information (step 1)</h4>
<?=toTable($tmp->countField(Team::ROW_TEAM_STATE, State::toSQL(Team::ROW_TEAM_STATE)))?>
<h4>Status of team's transfer slip</h4>
<?=toTable($tmp->countField(Team::ROW_PAY, State::toSQL(Team::ROW_PAY)))?>
<h4>Status of team's information (step 2)</h4>
<?=toTable($tmp->countField(Team::ROW_POST_REG_STATE, State::toSQL(Team::ROW_POST_REG_STATE)))?>
<h4>Medical school</h4>
<?=toTable($tmp->countField(Team::ROW_INSTITUTION))?>
<h4>University/College</h4>
<?=toTable($tmp->countField(Team::ROW_UNIVERSITY))?>
<h4>Country</h4>
<?=toTable($tmp->countField(Team::ROW_COUNTRY))?>
<h4>Route</h4>
<?=toTable($tmp->countField(Team::ROW_ROUTE, $tmp->routeSQL()))?>
<hr />
<?	foreach(array(new Observer($db), new Participant($db)) as $tmp):?>
<h3 id="<? if(property_exists($tmp, 'part_no')):?>part<? else:?>obs<? endif;?>"><? if(property_exists($tmp, 'part_no')):?>Participant<? else:?>Advisor<? endif;?>s (<?=$tmp->countField()?> people)</h3>
<h4>Status of their information (step 1)</h4>
<?=toTable($tmp->countField(Participant::ROW_INFO_STATE, State::toSQL(Participant::ROW_INFO_STATE)))?>
<h4>Gender</h4>
<?php
	echo toTable($tmp->countField(Participant::ROW_GENDER, Participant::genderSQL()));
	if(property_exists($tmp, 'part_no')):
?>
<h4>Medical student year</h4>
<?php
	echo toTable($tmp->countField(Participant::ROW_STD_Y));
	endif;
?>
<h4>Nationality</h4>
<?=toTable($tmp->countField(Participant::ROW_NATIONALITY))?>
<h4>Religion</h4>
<?=toTable($tmp->countField(Participant::ROW_RELIGION))?>
<h4>Shirt size</h4>
<?=toTable($tmp->countField(Participant::ROW_SHIRT_SIZE))?>
<hr />
<?php
	endforeach;
	return ob_get_clean();
}
// table team --> admin.team.view.php
function teamTable(PDO $db, $order=0){
	ob_start();?>
<h2>List of participating teams (Order by <?=$order?'Arrival time':'Team\'s name'?>) <small class="hide-for-print"><a href="admin.participant.php?print&view=team&order=<?=$order?>" target="_blank" class="button">Print</a> <a href="admin.participant.php?view=team&order=<?=$order?>" class="button reload">Reload</a></small></h2>
<?php //Team::ROW_ARRIVE_TIME
	echo teamList(new Team($db), 'admin.team.php', ($order?Team::ROW_ARRIVE_TIME:Team::ROW_ARRIVE_BY), false);
	return ob_get_clean();
}

// table part
function partTable(PDO $db){
	ob_start();
	$t=new Team($db);
	$p=new Participant($db);
	?>
<h2>List of participants (students) <small class="hide-for-print"><a href="admin.participant.php?print&view=part" target="_blank" class="button">Print</a> <a href="admin.participant.php?view=part" class="button reload">Reload</a></small></h2>
    <?php
	echo Config::toTable($p->getList(true, $t->getIDList()), Participant::forTableRow());
	return ob_get_clean();
}

// table obs(distinct)

function obsTable(PDO $db, $distinct){
	ob_start();
	$t=new Team($db);
	$o=new Observer($db);
	?>
<h2>List of advisors <small><a href="admin.participant.php?print&view=obs&distinct=<?=$distinct?>" target="_blank" class="button">Print</a> <a href="admin.participant.php?view=obs&distinct=<?=$distinct?>" class="button reload">Reload</a></small></h2>
    <?php
	echo Config::toTable($distinct?$o->getDistinctList(true, $t->getIDList()):$o->getList(true, $t->getIDList()), Observer::forTableRow());
	return ob_get_clean();
}

?>