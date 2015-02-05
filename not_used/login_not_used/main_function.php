<?php
require_once '../../login/config.inc.php';
function delOldMail(PDO $dbh=NULL){ //ล้าง waiting list (email) ที่ยังไม่ได้ confirm
	try{
		if($dbh==NULL) $dbh=newPDO();
		$dbh->beginTransaction();
		
		$sql='DELETE FROM new_account WHERE create_time < (NOW() - INTERVAL 2 DAY)';
		$stmt=$dbh->prepare($sql);
		$stmt->execute();
		
		$row=$stmt->rowCount();
		$dbh->commit();
		unset($sql,$dbh,$stmt);
		return $row;
	} catch(Exception $e){
		if($dbh!=NULL) $dbh->rollBack();
		return errMsg($e,isset($sql)?$sql:'','');
	}
}

function checkCAPTCHA(){
	global $_ROOT;
	if(!isset($_POST['captcha'])) return false;
	require_once($_ROOT.'/securimage/securimage.php');
	$cp=new Securimage();
	return ($cp->check($_POST['captcha']));
}
?>