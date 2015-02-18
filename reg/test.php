<?php
require_once 'config.inc.php';
require_once 'class.Admin.php';
require_once 'class.SesAdm.php';

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
<script src="../reg/js/skajax.js"></script>
<link href="../css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../slick/slick.css"/>
<link rel="stylesheet" type="text/css" href="../css/foundation.min.css"/>
<script>
$(function(e){
	$.addDialog('asdkldakdlkldas<br>asdjklasdf');
});
</script>
</head>

<body>
<pre>
<?php
require_once 'class.UploadImage.php';
$u=new UploadImage();
$u->team_id=123456;
?>
</pre>
<div class="alert-box info"><div class="alert-box alert">Test</div></div>
<?php
echo Config::country();
?>
<div class="dialog"><button>Close</button><div><button>Close</button></div></div>
</body>
</html>