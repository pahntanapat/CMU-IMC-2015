<?php
require_once 'config.inc.php';
require_once 'class.Admin.php';
require_once 'class.SesAdm.php';
require_once 'class.Message.php';

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Test</title>

<link rel="stylesheet" href="css/ui.css">
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
<script>
</script>
</head>

<body>
<pre><?php
require_once 'class.Member.php';
require_once 'class.Team.php';
$db=$config->PDO();/*//$db=new PDO();
$stm=$db->prepare('SELECT *, (CASE gender WHEN 1 THEN "male" ELSE "female" END) AS gender FROM `participant_info`');
$stm->execute();
var_dump($stm->fetchAll(PDO::FETCH_CLASS, 'Participant', array($db)));*/
require_once 'class.State.php';
$t=new Team($db);
var_dump($t->getIDList());
?></pre>
<div>
<?php
function toTable(PDOStatement $stm){
	ob_start();?>
<table><tr><th scope="col"></th><th scope="col"></th></tr>
<? while($r=$stm->fetch(PDO::FETCH_NUM)):?>
    <tr><td><?=$r[0]?></td>
    <td><?=$r[1]?></td></tr>
<? endwhile;?>
</table>
<?php
	return ob_get_clean();
}

$m=new Participant($db);
echo toTable($m->countField(Participant::ROW_GENDER));
?>
</div>
<?php

?>
<div class="dialog"><button>Close</button><div><button>Close</button></div></div>
</body>
</html>