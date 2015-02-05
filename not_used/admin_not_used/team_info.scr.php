<?php
require_once 'config.inc.php';
require_once $_ROOT.'/login/config.inc.php';
require_once 'class.Session.php';

require_once $_ROOT.'/login/upload_img_excp.php';
$db=newPDO();
$sess=new Session();
if(Session::isLogIn(true,Session::PMS_STUDENT,$sess->load())){
	ob_start();
	require_once $_ROOT.'/login/json_ajax.php';
	$json=new jsonAjax();
	$json->result=false;
	try{
		$db->beginTransaction();
		switch(strval(@$_REQUEST['act'])){
			case '':
			case NULL:break;
			case 'del': //ลบทีมออก
				$stm=$db->prepare('DELETE FROM team_info WHERE id=?');
				$stm->execute(array($_REQUEST['id']));
				echo "ลบข้อมูลทีมแล้ว ".$stm->rowCount()." ทีม\n";
				
				$stm=$db->prepare('DELETE FROM participant_info WHERE team_id=?');
				$stm->execute(array($_REQUEST['id']));
				echo "ลบผู้สมัครของทีมแล้ว {$stm->rowCount()} คน\n";
				
				$stm=$db->prepare('DELETE FROM team_message WHERE team_id=?');
				$stm->execute(array($_REQUEST['id']));
				echo "ลบข้อความแจ้งเตือนทีมแล้ว {$stm->rowCount()} ข้อความ\n";
				
				$stm=$db->prepare('DELETE FROM coach_info WHERE team_id=?');
				$stm->execute(array($_REQUEST['id']));
				echo "ลบคำตอบทีมแล้ว {$stm->rowCount()} คำตอบ\n";
				break;
			case '0':  //case 0: //tick ว่าข้อมูลทีมผ่านหรือไม่
				$stm=$db->prepare('UPDATE team_info SET is_pass=:st WHERE id=:id');
				$stm->bindValue(':st',(count($_POST['pass'])<end($_POST['pass']))?State::STATE_MUST_CHANGE:State::STATE_PASS,PDO::PARAM_INT);
				$stm->bindValue(':id',$_REQUEST['id']);
				$stm->execute();
				echo "บันทึกข้อมูลแล้ว";
				if(count($_POST['pass'])==end($_POST['pass'])){ //ถ้าผ่าน
					$stm=$db->prepare('DELETE FROM team_message WHERE team_id=:id AND show_page=:pg');
					$stm->bindValue(':id',$_REQUEST['id']);
					$stm->bindValue(':pg',state::SECT_INFO_TEAM);
					$stm->execute();
					echo "ลบข้อความแจ้งเตือนฯส่วนนี้แล้ว";
				}
				break;
			case '1':	case '2':	case '3': //case 1: 	case 2:		case 3:	
				//tick ว่าข้อมูลผู้แข่งขันแต่ละคนผ่านหรือไม่ (คนที่ 1-3)
				$stm=$db->prepare('UPDATE participant_info SET is_pass=:p, is_upload=:u WHERE id=:id AND team_id=:tid');
				$stm->bindValue(':p',(count($_POST['pass'])<end($_POST['pass']))?State::STATE_MUST_CHANGE:State::STATE_PASS,PDO::PARAM_INT);
				$stm->bindValue(':u',(isset($_POST['tsp']))?State::STATE_PASS:State::STATE_MUST_CHANGE,PDO::PARAM_INT);
				$stm->bindValue(':id',$_POST['sid']);
				$stm->bindValue(':tid',$_REQUEST['id']);
				$stm->execute();
				echo "บันทึกข้อมูลแล้ว\n";
				
				if(count($_POST['pass'])==end($_POST['pass']) && isset($_POST['tsp'])){ //ถ้าผ่าน
					$stm=$db->prepare('DELETE FROM team_message WHERE team_id=:id AND show_page IN(:info,:tsp)');
					$stm->bindValue(':id',$_REQUEST['id']);
					$stm->bindValue(':info',state::SECT_INFO_STD_1+$_REQUEST['act']-1);
					$stm->bindValue(':tsp',state::SECT_TSP_STD_1+$_REQUEST['act']-1);
					$stm->execute();
					echo "ลบข้อความแจ้งเตือนฯส่วนนี้แล้ว\n";
				
					if(is_file($_ROOT.imgTSP($_REQUEST['id'],$_POST['sid']))) //ลบใบปพ.1 ออกเพื่อเพิ่มพท. ให้ host
						echo (unlink($_ROOT.imgTSP($_REQUEST['id'],$_POST['sid'])))?"ลบไฟล์แล้ว\n":"ลบไฟล์ไม่ได้\n";
				}
				break;
			case 'edit': 
				if(isset($_POST['id'])){
					$stm=$db->prepare('UPDATE `participant_info` SET `title`=:title, `firstname`=:firstname, `lastname`=:lastname, `gender`=:gender,`phone`=:phone, `email`=:email, `school`=:school, `sci_grade`=:sci_grade WHERE `id`=:id AND `team_id`=:tid');
					$_POST['edit'][':id']=$_POST['id'];
				}else{
					$stm=$db->prepare('UPDATE team_info SET email=:email, password=:password, team_name=:team_name, type=:type, t_firstname=:t_firstname, t_lastname=:t_lastname, t_phone=:t_phone WHERE id=:tid');
				}
				$_POST['edit'][':tid']=$_GET['id'];
				$stm->execute($_POST['edit']);
				echo 'บันทึกข้อมูลแล้ว จำนวนข้อมูล = '.$stm->rowCount().PHP_EOL;
			break;
		default:
	}
	$db->commit();
	$json->result=true;
		}catch(Exception $e){
			$db->rollBack();
			echo nl2br($e);
		}
	$json->message=ob_get_clean();
	$json->addAction(jsonAjax::ALERT,$json->message);
	$json->export();
}
?>