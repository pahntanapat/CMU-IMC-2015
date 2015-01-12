<?php
require_once 'config.inc.php';
require_once $_ROOT.'/login/config.inc.php';
require_once 'class.Session.php';
require_once $_ROOT.'/login/json_ajax.php';
$sess=new Session();

if(Session::isLogIn(true,true,$sess->load()) && count($_POST)>0):
	ob_start();
	try{
		$json=new jsonAjax();
		$db=newPDO();
		$db->beginTransaction();
		$sql=$_REQUEST['msg']>0
			?'UPDATE team_message SET title=:t, detail=:d, sender_id=:sid, show_page=:p, time=:no WHERE id=:msg_id AND team_id=:id'
			:'INSERT INTO team_message (team_id,title,detail,sender_id,show_page,time) VALUES (:id,:t,:d,:sid,:p,:no)';
		$stm=$db->prepare($sql);
		if($_REQUEST['msg']>0)
			$stm->bindValue(':msg_id',$_REQUEST['msg']);
		$stm->bindValue(':p',$_POST['page'],PDO::PARAM_INT);
		$stm->bindValue(':t',$_POST['title']);
		$stm->bindValue(':d',$_POST['detail']);
		$stm->bindValue(':sid',$sess->id);
		$stm->bindValue(':id',$_REQUEST['id']);
		$stm->bindValue(':no',NULL,PDO::PARAM_NULL);
		$json->result=$stm->execute();
		echo "บันทึกแล้วข้อความแล้ว\n";
		 /// SEND MSG
		$stm=$db->prepare('SELECT email FROM team_info WHERE id=? LIMIT 1');
		$stm->execute(array($_REQUEST['id']));
		require_once $_ROOT.'/login/mail.php';
		$to=$stm->fetchColumn();
		$p=State::getPage($_POST['page']);
		$t=date('Y-m-d H:i:s');
		$_POST['detail']=htmlspecialchars(nl2br($_POST['detail']));
		$msg=<<<MSG
<b>กรรมการการแข่งขันได้ส่งข้อความถึงท่าน</b>
<p><b>หัวข้อ :</b> {$_POST['title']}</p>
<p><b>หน้า :</b> {$p}</p>
<p><b>รายละเอียด :</b><br/>{$_POST['detail']}</p>
<h4><b>เวลาโดยประมาณ :</b> {$t}</h4>
<h6><b>Admin ID =</b> {$sess->id}</h6>
MSG;
		$mail=forceSendMail($to,$_POST['title'],$msg);
		echo ($mail===true?'ส่ง Email สำเร็จ':'ส่ง Email ไม่สำเร็จ: '.$mail).PHP_EOL;
		$db->commit();
	}catch(Exception $e){
		$db->rollBack();
		echo $e.PHP_EOL.PHP_EOL.$sql.PHP_EOL;
	}
	$json->message=ob_get_clean();
	$json->addAction(jsonAjax::ALERT,$json->message);
	$json->export();
elseif(isset($_GET['act'])):
	ob_start();
	try{
		$json=new jsonAjax();
		$db=newPDO();
		$db->beginTransaction();
		switch($_GET['act']){
			case 'hide': //ซ่อนข้อความ
				$stm=$db->prepare('UPDATE team_message SET show_page=:sp, time=:no, sender_id=:sid WHERE id=:msg AND team_id=:id');
				$stm->bindValue(':sid',$sess->id);
				$stm->bindValue(':id',$_GET['id']);
				$stm->bindValue(':msg',$_GET['msg']);
				$stm->bindValue(':no',NULL,PDO::PARAM_NULL);
				$stm->bindValue(':sp',State::NOT_SHOWN,PDO::PARAM_INT); //State::NOT_SHOWN=ไม่แสดง
				$json->result=$stm->execute();
				echo "ซ่อนข้อความแล้ว";
				break;
			case 'del': //ลบข้อความ
				$stm=$db->prepare('DELETE FROM team_message WHERE id=:msg AND team_id=:id');
				$stm->bindValue(':msg',$_GET['msg']);
				$stm->bindValue(':id',$_GET['id']);
				$json->result=$stm->execute();
				echo "ลบข้อความแล้ว";
				break;
			case 'clear':
				$stm=$db->prepare('DELETE FROM team_message WHERE team_id=:id');
				$stm->bindValue(':id',$_GET['id']);
				$json->result=$stm->execute();
				echo "ลบข้อความแล้ว ".$stm->rowCount()." ข้อความ";
				break;
		}
		$db->commit();
	}catch(Exception $e){
		$db->rollBack();
		echo $e;
	}
	$json->message=ob_get_clean();
	$json->addAction(jsonAjax::ALERT,$json->message);
	$json->export();
endif;
?>