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
require_once 'class.Team.php';
$t=new Team($config->PDO());
var_dump($t->getRoute(),$t->countRoute());
?></pre>
<div>

</div>
<?php

?>
<div class="dialog"><button>Close</button><div><button>Close</button></div></div>
</body>
</html>