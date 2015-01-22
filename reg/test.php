<?php
require_once 'config.inc.php';
require_once 'class.Element.php';
require_once 'class.Admin.php';
require_once 'class.SesAdm.php';

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Test</title>
<link rel="stylesheet" href="css/ui.css">
<script src="../jquery-1.11.2.min.js"></script>
<script src="../jquery-migrate-1.2.1.min.js"></script>
<script src="js/skajax.js"></script>
<script>
$(function(e){
	$.addDialog('asdkldakdlkldas<br>asdjklasdf');
});
</script>
</head>

<body>
<pre>
<?php
$password='';
var_dump($password);

var_dump(preg_match_all('/^[[:alnum:]_:;]{6,32}$/',$password,$match));
var_dump($match);

var_dump(Config::checkPW($password,$match));
var_dump($match);
?>
</pre>
<?php
echo Config::country();
?>
<div class="dialog"><button>Close</button><div><button>Close</button></div></div>
</body>
</html>