<?php
require_once 'config.inc.php';
require_once $_ROOT.'/login/config.inc.php';
require_once 'class.Session.php'; 
require_once $_ROOT.'/login/json_ajax.php';
//  require_once '../login_not_used/json_ajax.php';require_once '../../login/config.inc.php'; require_once '../login_not_used/main_function.php';
  
$sess=new Session();
$json=new jsonAjax();
$json->result=false;
$db=newPDO();
if(Session::isLogIn(true,true,$sess->load()) && @$_REQUEST['act']=='edit'): // Edit Admin
	ob_start();
	try{
		$db->beginTransaction();
		$sql=$_POST['id']<=0
			?'INSERT INTO `admin`(`student_id`, `password`, `nickname`, `permission`) VALUES (:s,:pw,:n,:p)'
			:'UPDATE `admin` SET `student_id`=:s,`password`=:pw,`nickname`=:n,`permission`=:p WHERE `id`=:id';
		$stm=$db->prepare($sql);
		if($_POST['id']>0) $stm->bindValue(':id',$_POST['id']);
		$stm->bindValue(':s',$_POST['std_id']);
		$stm->bindValue(':pw',$_POST['pw']);
		$stm->bindValue(':n',$_POST['nick']);
		$stm->bindValue(':p',array_sum($_POST['pms']),PDO::PARAM_INT);
		$json->result=$stm->execute();
		if($_POST['id']==$sess->id){
			$sess->nick=$_POST['nick'];
			$sess->pms=array_sum($_POST['pms']);
			$sess->std_id=$_POST['std_id'];
			$sess->renew()->load();
		}
		echo 'บันทึกข้อมูลแล้ว id = '.($_POST['id']<=0?$db->lastInsertId():$_POST['id']).' จำนวน = '.$stm->rowCount().PHP_EOL;
		$db->commit();
	}catch(Exception $e){
		$db->rollBack();
		echo errMsg($e,$sql);
	}
	$json->addAction(jsonAjax::ALERT,ob_get_clean());
	$json->export();
elseif(Session::isLogIn(true,Session::PMS_ADMIN,$sess) && count(@$_POST['del'])>0): // Delete Admin
	ob_start();
	try{
		$sql='DELETE FROM admin WHERE id IN (';
		$sql.=implode(',',array_fill(0,count($_POST['del']),'?')).')';
		$stm=$db->prepare($sql);
		$json->result=$stm->execute($_POST['del']);
		echo 'ลบ admin แล้ว '.$stm->rowCount().' คน'.PHP_EOL;
	}catch(Exception $e){
		echo errMsg($e,$sql).PHP_EOL;
	}
	$json->addAction(jsonAjax::ALERT,ob_get_clean());
	$json->export();
else: /*reload Admin*/ ?>
<table width="100%" border="0">
  <tr>
    <th scope="col">ลบ</th>
    <th scope="col">Student ID</th>
    <th scope="col">Nickname</th>
    <th scope="col">แก้ไข</th>
  </tr>
<?
foreach($db->query('SELECT id, student_id, nickname FROM admin ORDER BY student_id ASC, nickname ASC, permission DESC, id ASC') as $row):
?>
  <tr>
    <th scope="row"><input name="del[]" type="checkbox" id="del_<?=$row[0]?>" value="<?=$row[0]?>" title="ลบ"></th>
    <td><?=$row[1]?></td>
    <td><?=$row[2]?></td>
    <td class="center"><a href="admin_edit.php?id=<?=$row[0]?>" title="แก้ไข" target="_blank" class="open">แก้ไข</a></td>
  </tr><? endforeach;?>
</table>
<? endif;?>