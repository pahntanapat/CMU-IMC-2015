<?php
ob_start();
require_once "check_session.php";
require_once 'main_function.php';
require_once "result_box.php";

$result=new resultCon();
if(count($_POST)>0){
	if(!checkCAPTCHA())
		echo "คำตอบ (Answer) ไม่ถูกต้อง กรุณาตอบใหม่อีกครั้ง";
	elseif(time()>$_END_REGISTER)
		echo "หมดเขตรับสมัครตั้งแต่ ".date('Y-m-d H:i:s',$_END_REGISTER);
	elseif(!isset($_POST['OK']))
		echo "กรุณาอ่านทำความเข้าใจกติการการแข่งขัน";
	elseif(!isset($_POST['type']))
		echo "กรุณาเลือกประเภททีม";
	elseif(!(strlen($_POST['email'])>0 & strlen($_POST['password'])>0 && $_POST["password_confirmation"]))
		echo "กรุณากรอก email และ password";
	elseif(filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)===false)
		echo "รูปแบบ email ไม่ถูกต้อง";
	elseif(!preg_match_all('/^[[:alnum:]_:;]{6,32}$/',$_POST['password_confirmation'],$stm))
		echo "Password ต้องประกอบไปด้วย A-Z, a-z, 0-9, semicolon (;), colon (:) หรือ underscore (_) ความยาวรวม 6 - 32 ตัวอักขระเท่านั้น";
	elseif($_POST['password']!=$_POST["password_confirmation"])
		echo "Confirm password ไม่ตรงกับ password";
	else
		try{
			$db=newPDO();
			$db->beginTransaction();
			$stm=$db->prepare('SELECT (SELECT COUNT(*) FROM new_account WHERE email =:email)+(SELECT COUNT(*) FROM team_info WHERE email =:email);');
			$stm->execute(array(':email'=>$_POST['email']));
			
			if($stm->fetchColumn()>0)
				echo "Email นี้ได้ถูกลงทะเบียนแล้ว";
			else{
				$stm=$db->prepare('INSERT INTO new_account (email,password,type,confirm_code) VALUES (:email,:password,:type,:code)');
				$stm->bindParam(':email',$_POST['email']);
				$stm->bindParam(':password',$_POST['password']);
				$stm->bindParam(':type',$_POST['type'],PDO::PARAM_BOOL);
				$code=md5($_POST['email'].':'.$_POST['password'].'@'.$_POST['type'].'<'.$_POST['captcha'].'>;');
				$stm->bindValue(':code',$code);
				$stm->execute();
				
				$code= $_PATH.'/login.php?confirm='.$code;
	/*			require_once('mail.php');
				$message=<<<TXT
กรุณาคลิก link นี้และ log in ทันทีเพื่อยืนยัน email: $_POST[email] ของท่านหลังจากสมัครสมาชิกภายใน 48 ชั่วโมง<br/><br/>
<b><a href="$code" alt="ยืนยัน email" target="_blank">$code</a></b><br/><br/>
ถ้าท่านไม่สามารถคลิกที่ link ได้ ให้คัดลอก URL นี้ไปวางในโปรแกรม web browser<br/>
<b>$code</b><br/>
หากท่านยืนยัน email ช้ากว่า 48 ชั่วโมง ระบบจะลบ email ของท่านออกโดยอัตโนมัติ ท่านต้องสมัครสมาชิกใหม่โดยใช้ email เดิมหรือ email ใหม่ก็ได้
TXT;
				$warn=forceSendMail($_POST['email'],'ยืนยัน email',$message);
				if($warn!==true){
					$db->rollBack();
					$db->beginTransaction();
					echo "ไม่สามารถส่ง email ได้ไปยัง $_POST[email] ได้ เนื่องจาก ";
					echo $warn;
				}else{
					$result->result=true;
					echo "ลงทะเบียนเรียบร้อยแล้ว ระบบจะส่ง link ยืนยันไปยัง email ของท่าน\nกรุณา check email ของท่านภายใน 48 ชั่วโมง\nถ้าไม่พบให้ตรวจสอบใน spam mail";
				}
				unset($code,$head,$message,$warn);
				*/
				echo "ลงทะเบียนเรียบร้อยแล้ว\nกรุณา Click link ข้างล่างนี้แล้ว Log in เพื่อเปิดใช้งาน Account\n";
				echo <<<HTML
<a href=$code target=_blank>$code</a>
HTML;
				$result->result=true;
			}
			$db->commit();
			unset($db,$stm);
		}catch(Exception $e){
			$db->rollBack();
			echo "\nError! ไม่สามารถบันทึกข้อมูลได้เนื่องจาก\n$e";
			$result->result=false;
		}
}
$result->message.=nl2br(ob_get_clean());
if(isset($_GET['ajax'])){
	require_once 'json_ajax.php';
	$res=new jsonAjax();
	$res->setResult($result);
	if($result->result!==true){
		$res->addAction(jsonAjax::RELOAD_CAPTCHA);
		$res->addHtmlTextVal(jsonAjax::SET_VAL,"#captcha");
	}
	$res->export();
}
?>