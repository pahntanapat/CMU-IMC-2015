<?php
require_once '../../login/config.inc.php';
require $_ROOT.'/phpmailer/PHPMailerAutoload.php';
function sendMail($to,$subject,$message,$func=2,$debug=false){
	global $SMTP_HOST, $SMTP_PORT, $STMP_SECR, $SMTP_USER, $SMTP_PASS, $MAIL_FROM, $MAIL_REPLY_TO;
	try{
		if($debug) ob_start();
		$mail=new PHPMailer(true);
		$mail->CharSet = 'UTF-8';
		$mail->Encoding = "quoted-printable";

		switch($func){
			case 2:
				$mail->isSMTP();
				//for debugging only!
				if($debug){
					$mail->SMTPDebug = 1;
					$mail->Debugoutput = 'text';
				}
				$mail->SMTPAuth = true;
				$mail->host=$SMTP_HOST;
				$mail->Port = $SMTP_PORT; // 25 for others 578 465
				$mail->SMTPSecure = $SMTP_SECR; //unset for others
				$mail->Username = $SMTP_USER;
				$mail->Password = $SMTP_PASS;
				break;
			case 1:
				$mail->isSendmail();
				break;
			case 0:
			default:
				$mail->isMail();
		}

		$mail->setFrom($MAIL_FROM);
		$mail->addReplyTo($MAIL_REPLY_TO);
		$mail->addAddress($to);

		$mail->Subject = $subject.': Mahidol Quiz';
		$msg=<<<HTM
<h1>$subject</h1><br/>
<h2>Mahidol Quiz</h2><hr/><br/>
<p>$message</p>
<br/><hr/>
<h6>Automatic message from: {$_SERVER['SERVER_NAME']} Please reply to {$mail->Username}</h6>
HTM;
		$mail->msgHTML($msg, dirname(__FILE__),false);
		$send=$mail->send();
	}catch(phpmailerException $e) {
   		$send=$e->errorMessage();
	} catch (Exception $e) {
    	$send=$e->getMessage();
	}
	if($debug) $send.=PHP_EOL.ob_get_clean();
	return $send;
}

function forceSendMail($to,$subject,$message,$debug=false){
	$tmp='';
	for($i=2;$i>=0;$i--){
		$msg=sendMail($to,$subject,$message,$i,$debug);
		if($msg===true) return $debug?$msg:true;
		$tmp.=PHP_EOL.$msg;
	}
	return $tmp;
}
?>