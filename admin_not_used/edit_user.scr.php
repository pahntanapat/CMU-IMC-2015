<?php
//  require_once '../login_not_used/json_ajax.php';require_once '../login/config.inc.php'; require_once '../login_not_used/main_function.php';
require_once 'config.inc.php';
require_once $_ROOT.'/login/config.inc.php';
require_once 'class.Session.php'; 
require_once $_ROOT.'/login/upload_img_excp.php';
$sess=new Session();
$db=newPDO();

if(Session::isLogIn(true,Session::PMS_STUDENT,$sess) && isset($_REQUEST['act'])):
	ob_start();
	require_once $_ROOT.'/login/json_ajax.php';
	$json=new jsonAjax();
	switch($_GET['act']){
		case 'clean': //ล้าง account หมดอายุ
			require_once $_ROOT.'/login/main_function.php';
			echo 'ลบ email ที่ยังไม่ confirm แล้ว จำนวน '.delOldMail($db).' accounts'.PHP_EOL; //ล้าง waiting list (email) ที่ยังไม่ได้ confirm
			break;
		case 'del': //ลบ
			try{
				$db->beginTransaction();
				$stm=$db->prepare('DELETE FROM new_account WHERE id=?');
				$stm->execute(array($_REQUEST['id']));
				if($stm->rowCount()>0)	echo "ลบ #".$_REQUEST['id']." แล้ว\n";
				else echo "ไม่มี #".$_REQUEST['id']." ให้ลบ\n";
				$json->result=true;
				$db->commit();
			}catch(Exception $e){
				$db->rollBack();
				echo nl2br($e);
			}
			break;
		case 'send': //ส่ง mail อีกรอบ
			require_once $_ROOT.'/login/mail.php';
			$stm=$db->prepare('SELECT email,confirm_code,(create_time+INTERVAL 2 DAY) AS lim FROM new_account WHERE id=? LIMIT 1');
			$stm->execute(array($_REQUEST['id']));
			if($stm->rowCount()>0){
			$row=$stm->fetch(PDO::FETCH_ASSOC);
			$code= $_PATH.'/login.php?confirm='.$row['confirm_code']; //รหัสยืนยัน
			$message=<<<TXT
กรุณาคลิก link นี้และ log in ทันทีเพื่อยืนยัน email: $row[email] ของท่านหลังจากสมัครสมาชิกภายใน 48 ชั่วโมง<br/><br/>
<b><a href="$code" alt="ยืนยัน email" target="_blank">$code</a></b><br/><br/>
ถ้าท่านไม่สามารถคลิกที่ link ได้ ให้คัดลอก URL นี้ไปวางในโปรแกรม web browser<br/>
<b>$code</b><br/>
หากท่านยืนยัน email ช้ากว่า $row[lim] ระบบจะลบ email ของท่านออกโดยอัตโนมัติ ท่านต้องสมัครสมาชิกใหม่โดยใช้ email เดิมหรือ email ใหม่ก็ได้
TXT;
			$warn=forceSendMail($row['email'],'ยืนยัน email',$message); //ส่งเมล์
			if($warn===true) echo 'ส่งเมล์สำเร็จ'.PHP_EOL;
			else echo $warn.PHP_EOL;
			}else
				echo "ไม่มี #".$_REQUEST['id'].PHP_EOL;
			break;
	}
	$json->message=ob_get_clean();
	$json->addAction(jsonAjax::ALERT,$json->message);
	if(isset($_GET['ajax'])){
		$json->export();
		$json->message=NULL;
	}else exit($json->message);
elseif(isset($_REQUEST['reload'])):
?>
<div><a href="edit_user.php" id="reloadTable">Reload</a> <em>&nbsp;The lastest update: <?=date('Y-m-d H:i:s')?></em></div>
<?php
try{
	$stm=$db->prepare('SELECT team_info.id,team_info.email,team_name,type FROM team_info LEFT JOIN participant_info ON participant_info.team_id=team_info.id WHERE participant_info. is_pass=:w OR team_info. is_pass=:w OR is_upload=:w GROUP BY team_info.id');
	$stm->bindValue(':w',State::STATE_WAIT,PDO::PARAM_INT);
	$stm->execute();
?>
  <h2>ผู้แข่งขันที่ยืนยันข้อมูลแล้ว (<?=State::getIcon(State::STATE_WAIT)?>) จำนวน <?=$stm->rowCount()?> ทีม</h2>
  <table width="100%" border="2" cellpadding="2" cellspacing="0">
    <tr>
      <th scope="col">ID</th>
      <th scope="col">Email</th>
      <th scope="col">Team's name</th>
      <th scope="col">type</th>
      <th scope="col">ตรวจ</th>
      <th scope="col">แก้ไข</th>
      <th scope="col">ลบ</th>
    </tr>
<?php while($row=$stm->fetch(PDO::FETCH_OBJ)): ?>
    <tr>
      <td><?=$row->id?></td>
      <td><?=$row->email?></td>
      <td><?=$row->team_name?></td>
      <td><?=($row->type)?'อิสระ':'ร.ร.'?></td>
      <td><a href="team_info.php?act=check&amp;id=<?=$row->id?>" title="ตรวจทีม <?=$row->team_name?>" target="_blank" class="open">ตรวจ</a></td>
      <td><a href="team_info.php?id=<?=$row->id?>" title="แก้ไขทีม <?=$row->team_name?>" target="_blank" class="open">แก้ไข</a></td>
      <td><a href="team_info.php?act=del&amp;id=<?=$row->id?>" title="ลบทีม <?=$row->team_name?>" target="_blank" class="cnf">ลบ</a></td>
    </tr>
<?php endwhile;?>
  </table>
<?
}catch(Exception $e){
	echo "<div>".nl2br($e)."</div>";
}
?>  
  <p>&nbsp;</p>
<?php
try{
	$stm=$db->query('SELECT team_info.id,team_info.email,team_name,type, CONCAT(team_info.is_pass,\',\',GROUP_CONCAT(DISTINCT participant_info.is_pass,\',\', is_upload SEPARATOR \',\')) AS state FROM team_info LEFT JOIN participant_info ON participant_info.team_id=team_info.id GROUP BY team_info.id');
?>
  <h2>ผู้แข่งขันทุกทีม จำนวน <?=$stm->rowCount()?> ทีม</h2>
  <table width="100%" border="2" cellpadding="2" cellspacing="0">
    <tr>
      <th scope="col">ID</th>
      <th scope="col">Email</th>
      <th scope="col">Team's name</th>
      <th scope="col">type</th>
      <th scope="col">state</th>
      <th scope="col">แก้ไข</th>
      <th scope="col">ลบ</th>
    </tr>
<?php while($row=$stm->fetch(PDO::FETCH_OBJ)): ?>
    <tr>
      <td><?=$row->id?></td>
      <td><?=$row->email?></td>
      <td><?=$row->team_name?></td>
      <td><?=($row->type)?'อิสระ':'ร.ร.'?></td>
      <td><? foreach(explode(',',$row->state) as $v) echo State::getIcon($v).' ';?></td>
      <td><a href="team_info.php?id=<?=$row->id?>" title="แก้ไขทีม <?=$row->team_name?>" target="_blank" class="open">แก้ไข</a></td>
      <td><a href="team_info.php?act=del&amp;id=<?=$row->id?>" title="ลบทีม <?=$row->team_name?>" target="_blank" class="cnf">ลบ</a></td>
    </tr>
<?php endwhile;?>
  </table>
<?
}catch(Exception $e){
	echo "<div>".nl2br($e)."</div>";
}
?>  
  <p>&nbsp;</p>
<?php
try{
	$stm=$db->query('SELECT id,email,type,create_time FROM new_account');
?>
  <h2>ผู้แข่งขันที่ยังไม่ได้ยืนยัน email จำนวน <?=$stm->rowCount()?> ทีม</h2>
  <table width="100%" border="2" cellpadding="2" cellspacing="0">
    <tr>
      <th scope="col">ID</th>
      <th scope="col">Email</th>
      <th scope="col">type</th>
      <th scope="col">สมัครเมื่อ</th>
      <th scope="col">ส่ง confirm code</th>
      <th scope="col">ลบ</th>
      </tr>
<? while($row=$stm->fetch(PDO::FETCH_OBJ)): ?>
    <tr>
      <td><?=$row->id?></td>
      <td><?=$row->email?></td>
      <td><?=($row->type)?'อิสระ':'ร.ร.'?></td>
      <td><?=$row->create_time?></td>
      <td><a href="edit_user.php?act=send&amp;id=<?=$row->id?>" title="ส่ง confirm code ให้ทีม #<?=$row->id?>" target="_blank" class="cnf">ส่ง confirm code</a></td>
      <td><a href="edit_user.php?act=del&amp;id=<?=$row->id?>" title="ลบทีม #<?=$row->id?>" target="_blank" class="cnf">ลบ</a></td>
      </tr>
<? endwhile;?>
  </table>
<?
}catch(Exception $e){
	echo "<div>".nl2br($e)."</div>";
}
?>  
  <p class="bold"><a href="edit_user.php?act=clean" title="ล้าง account หมดอายุ" target="_blank" class="cnf">ล้าง account หมดอายุ</a></p>
<?
endif;
?>