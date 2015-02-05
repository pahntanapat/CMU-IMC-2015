<?php
require_once 'config.inc.php';
require_once 'class.Session.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/login/config.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/login/json_ajax.php';

if(isset($_GET['ajax'])){
	ob_start();
	$json=new jsonAjax();
	$json->result=false;
	if(checkCAPTCHA()){
		try{
			$db=newPDO();
			$db->beginTransaction();
			$sql='SELECT id, student_id, nickname, permission FROM admin WHERE student_id=? AND password=?';
			$stm=$db->prepare($sql);
			$stm->execute(array($_POST['std_id'],$_POST['pw']));
			if($stm->rowCount()>0){
				$row=$stm->fetch(PDO::FETCH_OBJ);
				$sess=new Session();
				$sess->id=$row->id;
				$sess->nick=$row->nickname;
				$sess->pms=$row->permission;
				$sess->std_id=$row->student_id;
				echo 'Log in แล้ว ยินดีต้อนรับ '.$sess->save().PHP_EOL;
				$json->addAction(jsonAjax::REDIRECT,'index.php');
				$json->result=true;
			}elseif($_POST['std_id']==$DB_USER && $_POST['pw']==$DB_PW){
				$sql='SELECT COUNT(*) FROM admin';
				$stm=$db->query($sql);
				if($stm->fetchColumn()==0){
					$sess=new Session();
					echo 'Log in แล้ว ยินดีต้อนรับ '.$sess->save().PHP_EOL;
					$json->addAction(jsonAjax::REDIRECT,'index.php');
					$json->result=true;
				}else{
					echo "ไม่อนุญาตให้ log in ในชื่อ root \n";
					$json->addAction(jsonAjax::RELOAD_CAPTCHA);
				}
			}else{
				echo "ไม่พบ Student ID หรือ password ไม่ถูกต้อง\n";
				$json->addAction(jsonAjax::RELOAD_CAPTCHA);
			}
			$db->commit();
		}catch(Exception $e){
			$db->rollBack();
			echo errMsg($e,$sql);
		}	
	}else{
		echo 'คำตอบไม่ถูกต้อง'.PHP_EOL;
		$json->addAction(jsonAjax::RELOAD_CAPTCHA);
	}
	$json->addAction(jsonAjax::ALERT,ob_get_clean());
	$json->export();
}

?>