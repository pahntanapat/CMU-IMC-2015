<?
require_once 'config.inc.php';
require_once 'class.Team.php';
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
$t=new Team($config->PDO());
		$t->id=17;
		$t->auth(false);
var_dump($t);
?>
</pre>
</body>
</html>