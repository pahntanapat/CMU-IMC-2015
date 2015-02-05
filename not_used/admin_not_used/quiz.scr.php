<?php
require_once 'config.inc.php';
require_once $_ROOT.'/login/config.inc.php';
require_once 'class.Session.php';
require_once $_ROOT.'/login/json_ajax.php';
$sess=new Session();
if(Session::isLogIn(true,Session::PMS_QUIZ,$sess) && (count($_POST)>0 || isset($_REQUEST['act']))){
	ob_start();
	try{
		$json=new jsonAjax();
		$db=newPDO();
		$db->beginTransaction();
		if(@$_REQUEST['act']=='del'){
			$stm=$db->prepare('DELETE FROM coach_info WHERE id=?');
			$stm->execute(array($_GET['id']));
			echo "ลบคำตอบแล้ว {$stm->rowCount()} ทีม\n";
			$json->addAction(jsonAjax::REDIRECT,'quiz.php');
		}elseif(isset($_POST['confirm'])){ // submit มาจากหน้าที่ให้ tick ว่าจะให้ทีมไหนผ่านเข้ารอบ
			$json->result=true;
			foreach(array_unique($_POST['pass']) as $state){
				$stm=$db->prepare('UPDATE team_info SET is_pay=? WHERE id IN('.implode(',',array_fill(0,count(array_keys($_POST['pass'],$state)),'?')).')');
				$json->result&=$stm->execute(array_merge(array($state), array_keys($_POST['pass'],$state)));
			}
			echo "บันทึกสถานะทีมแล้ว\n";
		}elseif(isset($_REQUEST['id'])){  //submit จากในหน้า ตรวจ quiz ของแต่ละคน
			$stm=$db->prepare('UPDATE coach_info SET score=:sc, comment=:cm WHERE id=:id');
			$stm->bindValue(':id',$_REQUEST['id']);
			$stm->bindValue(':sc',$_POST['score']);
			$stm->bindValue(':cm',$_POST['comment']);
			$json->result=$stm->execute();
			echo "บันทึกคะแนนและ comment แล้ว\n";
		}else{ // submit มาจากหน้าที่ให้ tick ว่าจะให้ทีมตรวจ quiz แล้ว
			$json->result=true;
			foreach(array_unique($_POST['pass']) as $st){
				$stm=$db->prepare('UPDATE coach_info SET state=? WHERE id IN('.implode(',',array_fill(0,count(array_keys($_POST['pass'],$st)),'?')).')');
				$json->result&=$stm->execute(array_merge(array($st),array_keys($_POST['pass'],$st)));
			}
			echo "บันทึกสถานะคำตอบ Quiz แล้ว\n";
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