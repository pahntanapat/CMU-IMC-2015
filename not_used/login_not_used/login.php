<?php
session_start();
require_once "check_session.php";
CheckSession::whenLogIn();

ob_start();
require_once 'result_box.php';
$result=new resultCon(false);
$result->addIfisStr(delOldMail());
if(count($_POST)>0){
	if (!checkCAPTCHA())
		echo "คำตอบ (Answer) ไม่ถูกต้อง กรุณาตอบใหม่อีกครั้ง";
	elseif(!(strlen($_POST['email'])>0 && strlen($_POST['password'])>0))
		echo "กรุณากรอก email และ password";
	elseif(filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)===false)
		echo "รูปแบบ email ไม่ถูกต้อง";
	else try{
		$db=newPDO();
		$db->beginTransaction();
		$sql=isset($_REQUEST['confirm']) ? 'SELECT email,password,type FROM new_account WHERE email=:email AND password=:pw AND confirm_code=:cc':(CheckSession::SQL).' WHERE team_info.email=:email AND team_info.password=:pw;';
		$stm=$db->prepare($sql);
		$stm->bindParam(':email',$_POST['email']);
		$stm->bindParam(':pw',$_POST['password']);
		if(isset($_REQUEST['confirm']))
			$stm->bindParam(':cc',$_REQUEST['confirm']);
		$stm->execute();
		
		if($stm->rowCount()>0){
			$sess=new CheckSession();
			if(isset($_REQUEST['confirm'])){
				$row=$stm->fetch(PDO::FETCH_ASSOC);
				$sql='INSERT INTO team_info (email,password,type) VALUES (:email,:pw,:type)';
				$stm=$db->prepare($sql);
				$stm->bindParam(":email",$row['email']);
				$stm->bindParam(":pw",$row['password']);
				$stm->bindParam(":type",$row['type']);
				$stm->execute();
				
				$sess->newUser($db->lastInsertId(),$row['type']);
				
				$sql='DELETE FROM new_account WHERE email=:email AND password=:pw AND type=:type AND confirm_code=:cc';
				$stm=$db->prepare($sql);
				$stm->bindParam(":email",$row['email']);
				$stm->bindParam(":pw",$row['password']);
				$stm->bindParam(":type",$row['type']);
				$stm->bindParam(':cc',$_REQUEST['confirm']);
				$stm->execute();
			}else{
				$sess->fromDB($stm);
			}
			$_SESSION=$sess->toSession($_SESSION);
			$result->result=true;
			unset($row);
		}else{
			echo "ไม่สามารถ log in ได้ เนื่องจาก <ol><li>Email หรือ Password ไม่ถูกต้อง</li><li>ท่านยังไม่ได้ยืนยัน email สำหรับ account นี้</li><li>Confirm code ไม่ถูกต้อง</li><li>ท่านยืนยัน email นี้ ช้ากว่า 48 ชั่วโมง</li></ol>";
			$result->result=false;
			unset($_SESSION);
		}
		$db->commit();
		unset($db,$stm,$sql);
	} catch(Exception $e){
		$db->rollBack();
		$result->result=false;
		unset($_SESSION);
		echo "\nไม่สามารถ log in ได้ เนื่องจากข้อผิดพลาดของระบบ<br\>\n$e<br\><br\>\n\n{$e->getMessage()}<br\>\nSQL = $sql";
	}
}
$result->message.=ob_get_clean();
if(isset($_GET['ajax'])):
	require_once 'json_ajax.php';
//	header('Content-type: text/json;charset=utf-8');
	$res=new jsonAjax();
	$res->setResult($result);
	if($result->result===true){
		$res->addAction(jsonAjax::REDIRECT,"./");
	}else{
		$res->addAction(jsonAjax::RELOAD_CAPTCHA);
		$res->addHtmlTextVal(jsonAjax::SET_VAL,"#captcha");
	}
	echo $res;
elseif($result->result===true):
	header("Location: ./");
else:
?>
<!doctype html>
<html><!-- InstanceBegin template="/Templates/mahidol.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta charset="utf-8">
<!-- InstanceBeginEditable name="doctitle" -->
<title>การแข่งขันตอบปัญหาวิทยาศาสตร์สุขภาพ Mahidol Quiz 2014</title>
<!-- InstanceEndEditable -->
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="js/jquery-ui-1.10.4.custom.min.js"></script>
<script src="js/mahidol_ajax.js"></script>
<link rel="stylesheet" href="../mahidol_quiz.css">
<link href="../mahidol.css" rel="stylesheet" type="text/css">

<!-- InstanceBeginEditable name="head" -->
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.1.47/jquery.form-validator.min.js"></script>
<script type="text/javascript" src="js/login.js"></script>
<!-- InstanceEndEditable -->

</head>

<body>
<div id="header"></div>
<div id="menu"></div>
<div id="content"><div id="Heading"><!-- InstanceBeginEditable name="Heading" -->
		        <h1><strong>Log in</strong></h1>
		      <!-- InstanceEndEditable --></div><div id="Content"><!-- InstanceBeginEditable name="Content" --><form action="login.php" method="post" name="login" class="center" id="login">
  <fieldset>
  <legend>Log in</legend>
  <div><label for="email">Email</label><input name="email" type="email" required id="email" value="<?=@$_POST['email'];?>" data-validation="email"></div>
  <div><label for="password">Password</label><input name="password" type="password" id="password" data-validation="length alphanumeric" data-validation-allowing="_:;"  data-validation-length="6-32" required></div>
  <? require("captcha.php");?>
  <div class="btnset"><? if(isset($_REQUEST['confirm'])): ?><input name="confirm" type="hidden" id="confirm" value="<?=$_REQUEST['confirm']?>"><? endif;?>
    <button type="submit" id="submit">Log in</button>
    <button type="reset" id="button">Cancel</button>
    <a href="forget.php" title="ลืมรหัสผ่าน">forget password?</a></div>
</fieldset>
  </form><!-- InstanceEndEditable --></div></div>
<div id="footer"></div>
</body>
<!-- InstanceEnd --></html><? endif;?>
