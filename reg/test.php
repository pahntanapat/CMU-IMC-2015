<?
require_once 'config.inc.php';
require_once 'class.Team.php';
require_once 'class.Member.php';
require_once 'class.SesPrt.php';
$s=new SesPrt();
?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Test</title>
</head>

<body>
<pre>
<?
$t=new Team($config->PDO());
		$t->id=17;
		$t->auth(false);
var_dump($t, $s);
?>
</pre>
</body>
</html>