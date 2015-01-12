<?php
require_once 'config.inc.php';
require_once $_ROOT.'/login/config.inc.php';
require_once 'class.Session.php';
require_once $_ROOT.'/login/upload_img_excp.php';
require_once $_ROOT.'/login/json_ajax.php';
$sess=new Session();
if(Session::isLogIn(true,Session::PMS_AUDIT,$sess) && count($_POST)>0){
	ob_start();
	try{
		$json=new jsonAjax();
		$db=newPDO();
		$db->beginTransaction();
		$stm=$db->prepare('UPDATE team_info SET is_pay=:p WHERE id=:id'); //อัพเดตสถานะการจ่ายเงิน
		$stm->bindValue(':id',$_REQUEST['id']); //รหัส
		$stm->bindValue(':p',$_POST['pass'],PDO::PARAM_INT); //สถานะการจ่ายเงิน
		$json->result=$stm->execute();
		echo "บันทึกแล้ว\n";
		if(isset($_POST['img']) && $_POST['pass']==State::STATE_PASS && $stm->rowCount()>0){
			//ลบรูปเอกสารการจ่ายเงินออกเพื่อเพิ่ม พท. host
			if(unlink($_ROOT.$_POST['img'])) echo "ลบหลักฐานการจ่ายเงินแล้ว\n";
			else echo "ลบรูปหลักฐานการจ่ายเงินไม่ได้\n";
		}
		if( $_POST['pass']==State::STATE_PASS){
			require $_ROOT.'/login/mail.php';
			$stm=$db->prepare('SELECT email, team_name FROM team_info WHERE id=? LIMIT 1');
			$stm->execute(array($_REQUEST['id']));
			$row=$stm->fetch(PDO::FETCH_NUM);
			$row[1]=htmlspecialchars($row[1]);
			$m=forceSendMail(
				$row[0],
				"หลักฐานการจ่ายเงินถูกต้อง",
<<<MAIL
<b>หลักฐานการจ่ายเงินของทีม {$row[1]} ถูกต้อง</b><br/>
ขณะนี้ทีมของท่านได้ผ่านขั้นตอน upload หลักฐานการจ่ายเงินเรียบร้อยแล้ว<br/>
กรุณา <a href="{$_PATH}/login/login.php" target="_blank">log in</a> เพื่อรอรับรหัสประจำตัวผู้แข่งขันและห้องสอบ
MAIL
				);
			if($m===true) echo 'ส่ง Email สำเร็จ'.PHP_EOL;
			else echo strip_tags($m);
			//send Mail
		}
		$db->commit();
	}catch(Exception $e){
		$db->rollBack();
		echo $e;
	}
	$json->message=ob_get_clean();
	$json->addAction(jsonAjax::ALERT,$json->message);
	$json->export();
}
?>