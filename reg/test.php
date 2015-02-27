<?
require_once 'config.inc.php';
require_once 'class.Member.php';
?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Test</title>
</head>

<body>
<pre>
<?
	$m=array();
	for($no=0;$no<=$config->REG_PARTICIPANT_NUM;$no++){
		$m[$no]=$no>0?new Participant($config->PDO()):new Observer($config->PDO());
		$m[$no]->team_id=17;
		if($no>0) $m[$no]->part_no=$no;
		$m[$no]->load();
	}
var_dump($m);
?>
</pre>
</body>
</html>