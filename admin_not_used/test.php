<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Test</title>
</head>

<body><pre>
<?php
ob_start();
$r=array();
		$r['result']=false;
		echo "ไม่สามารถ log in ได้ เนื่องจาก <ol><li>Email หรือ Password ไม่ถูกต้อง</li><li>ท่านยังไม่ได้ยืนยัน email สำหรับ account นี้</li><li>Confirm code ไม่ถูกต้อง</li><li>ท่านยืนยัน email นี้ ช้ากว่า 48 ชั่วโมง</li></ol>";
		$m=ob_get_clean();
		$r['message']=$m;
		$r['action']=array();
		//return json_encode($r,$option,$depth);

var_dump($r,json_decode(json_encode($r)));
?></pre>
</body>
</html>