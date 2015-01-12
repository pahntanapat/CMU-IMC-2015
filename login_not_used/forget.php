<?php
ob_start();
session_start();
require_once "main_function.php";
require_once 'result_box.php';

$result=new resultCon(false);
$result->addIfisStr(delOldMail());
if(isset($_POST)){
	if(count($_POST)<=0) $result->result=NULL;
	elseif (!checkCAPTCHA())
		echo "คำตอบ (Answer) ไม่ถูกต้อง กรุณาตอบใหม่อีกครั้ง";
	elseif(strlen($_POST['email'])<=0)
		echo "กรุณากรอก email";
	elseif(filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)===false)
		echo "รูปแบบ email ไม่ถูกต้อง";
	elseif(!isset($_POST['type']))
		echo "กรุณาเลือกประเภททีม";
	else try{
		$db=newPDO();
		$stm=$db->prepare('SELECT *,NOW() FROM '.(isset($_POST['isConfirm'])? 'team_info':'new_account').' WHERE email=:email AND type=:type;');
		$stm->bindParam(':email',$_POST['email']);
		$stm->bindParam(":type",$_POST['type'],PDO::PARAM_BOOL);
		$stm->execute();
		
		if($stm->rowCount()>0){
			$res=$stm->fetch(PDO::FETCH_ASSOC);
			$msg=<<<MAIL
ท่านได้ส่งคำร้องขอรหัสผ่านจากระบบ log in ของการแข่งขันตอบปัญหามหิดลเมื่อเวลา <b>{$res['NOW()']}</b><br>
<p><b>รายละเอียด</b></p>
<b>Email: </b>{$res['email']}<br>
<b>Password: </b>{$res['password']}<br>
<b>ประเภททีม: </b> ทีม
MAIL;
			$msg.=($res['type']==0)? 'โรงเรียน':'อิสระ';
			if(isset($res['confirm_code'])){
				$code= 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['REQUEST_URI']).'/login.php?confirm='.$res['confirm_code'];
				$msg.=<<<MAIL
<br><br><b>ท่านต้องยืนยัน email โดยคลิก link นี้แล้ว log in ทันที: <a href="$code" alt="ยืนยัน email" target="_blank">$code</a></b>
MAIL;
			}
			$msg.=<<<MAIL
<br><br>
คำแนะนำเพื่อความปลอดภัย: กรุณาเก็บ password เป็นความลับและควรเปลี่ยน password หลังจาก log in แล้ว<br>
MAIL;
			require 'mail.php';
			$warn=forceSendMail($_POST['email'],'ลืมรหัสผ่าน',$msg);
			if($warn===true){
				$result->result=true;
				echo "ส่งระบบได้ email ให้ท่านแล้ว";
			}else{
				$result->result=false;
				echo "Error: ไม่สามารถส่ง email ได้ กรุณาติดต่อทีมงานการแข่งขันฯ\n$warn";
			}
			unset($msg,$warn);
		}else
			echo "ไม่พบ email ที่ระบุนี้";
		unset($res,$db,$stm);
	}catch(Exception $e){
		echo "\nError: $e\n{$e->getMessage()}\n";
		$result->result=false;
	}
}
$result->message.=nl2br(ob_get_clean());

if(isset($_GET['ajax'])):
	require_once 'json_ajax.php';
	header('Content-type: application/json');
	$res=new jsonAjax();
	$res->setResult($result);
	if($result->result===false){
		$res->addAction(jsonAjax::RELOAD_CAPTCHA);
		$res->addHtmlTextVal(jsonAjax::SET_VAL,"#captcha");
	}
	echo $res;
else:
?><!doctype html>
<html><!-- InstanceBegin template="/Templates/mahidol.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta charset="utf-8">
<!-- InstanceBeginEditable name="doctitle" -->
<title>ลืมรหัสผ่าน - Mahidol Quiz 2014: การแข่งขันตอบปัญหาวิทยาศาสตร์สุขภาพ ชิงถ้วยสมเด็จพระเทพรัตนราชสุดา สยามบรมราชกุมารี เนื่องในวันมหิดล ประจำปี พ.ศ. 2557</title>
<!-- InstanceEndEditable -->
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="js/jquery-ui-1.10.4.custom.min.js"></script>
<script src="js/mahidol_ajax.js"></script>
<link rel="stylesheet" href="../mahidol_quiz.css">
<link href="../mahidol.css" rel="stylesheet" type="text/css">

<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->

</head>

<body>
<div id="header"></div>
<div id="menu"></div>
<div id="content"><div id="Heading"><!-- InstanceBeginEditable name="Heading" -->ลืมรหัสผ่าน<!-- InstanceEndEditable --></div><div id="Content"><!-- InstanceBeginEditable name="Content" --><? if(!$result->result): ?><form action="forget.php" method="post" name="forget" id="forget"><fieldset><legend>ลืมรหัสผ่าน</legend><div><label for="email">Email</label><input name="email" type="email" required id="email" value="<?=@$_POST['email'];?>" size="35" data-validation="email"></div><fieldset><legend>ประเภททีม</legend><div class="radio"><input name="type" type="radio" id="type_0" value="0" required>
           <label for="type_0"> ทีมโรงเรียน</label>
          <input type="radio" name="type" value="1" id="type_1" required>
            <label for="type_1"> ทีมอิสระ</label><input name="isConfirm" type="checkbox" id="isConfirm" value="1"> <label for="isConfirm">ได้ยืนยัน email แล้ว</label>
</div></fieldset>
<? require('captcha.php');?><div class="btnset"><input type="submit" name="submit" id="submit" value="ส่งข้อมูล"> <input type="reset" name="reset" id="reset" value="ล้างข้อมูล"></div></fieldset></form><? endif;
echo $result->getIfNotNull();?><!-- InstanceEndEditable --></div></div>
<div id="footer"></div>
</body>
<!-- InstanceEnd --></html><? endif;?>
