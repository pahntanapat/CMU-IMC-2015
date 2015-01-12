<?php
require_once 'config.inc.php';
require_once $_ROOT.'/login/config.inc.php';
require_once 'class.Session.php'; 
require_once $_ROOT.'/login/json_ajax.php';
$sess=new Session();
if(Session::isLogIn(true,Session::PMS_WEB_MTR,$sess->load()->renew()) && isset($_REQUEST['act'])):
	ob_start();
	try{
		$json=new jsonAjax();
		switch($_REQUEST['act']){
			case 'save':
				echo 'บันทึกการตั้งค่าใหม่แล้ว ขนาดไฟล์ '.saveConfig($_POST).' B';
				break;
			case 'reset':
				echo 'Reset การตั้งค่าใหม่แล้ว ขนาดไฟล์ '.saveConfig().' B';
				break;
			case 'mail':
				require_once $_ROOT.'/login/mail.php';
				switch($_POST['func']){
					case -1:
						echo forceSendMail($_POST['to'],$_POST['subject'],nl2br($_POST['msg']),true);
						break;
					default:
						echo sendMail($_POST['to'],$_POST['subject'],nl2br($_POST['msg']),$_POST['func'],true);
						break;
				}
				//$json->addAction(jsonAjax::EVALUTE,'$("#form2 :reset").click();');
				$json->result=true;
		}
	}catch(Exception $e){
		echo $e;
	}
	$json->message=ob_get_clean();
	$json->addHtmlTextVal(jsonAjax::SET_HTML,'#result',nl2br($json->message));
	$json->export();
else:

endif;
?>