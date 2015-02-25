<?php
require_once 'config.inc.php';
require_once $_ROOT.'/login/config.inc.php';
require_once 'class.Session.php';
require_once $_ROOT.'/login/upload_img_excp.php';
$sess=new Session();
if(Session::isLogIn(true,true,$sess->load()) && (count($_POST)>0||isset($_GET['act']))) require 'team_message.scr.php';

$db=newPDO();
?>
<!doctype html>
<html><!-- InstanceBegin template="/Templates/mahidol_admin.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta charset="utf-8">
<!-- InstanceBeginEditable name="doctitle" -->
<title>ส่งข้อความถึงผู้แข่งขัน - Admin::Mahidol Quiz</title>
<!-- InstanceEndEditable -->
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="login_not_used/js/jquery-ui-1.10.4.custom.min.js"></script>
<script src="login_not_used/js/mahidol_ajax.js"></script>
<link rel="stylesheet" href="mahidol_quiz.css">
<link href="mahidol.css" rel="stylesheet" type="text/css">

<script src="../login_not_used/js/mahidol_ajax.js"></script>
<link rel="stylesheet" type="text/css" href="admin.css">
<link rel="stylesheet" type="text/css" href="../login_not_used/css/jquery-ui-1.10.4.custom.css">
<!-- InstanceBeginEditable name="head" -->
<script src="team_message.js"></script>
<!-- InstanceEndEditable -->

</head>

<body>
<div id="header"></div>
<div id="menu"></div>
<div id="content"><nav>
  <ul class="quiz_bar">
    <li><a href="../../index.html" title="หน้าแรกเว็บมหิดล">หน้าแรกเว็บมหิดล</a></li>
    <li><a href="../../mahidol_quiz.html" title="หน้ารายละเอียดการแข่งขัน">หน้ารายละเอียดการแข่งขัน</a></li>
    <li><a href="../login_not_used/search.php" title="ค้นหาใน Mahidol Quiz" target="_blank">Search</a></li>
    <li><a href="index.php" title="หน้าหลัก Admin">หน้าหลัก Admin</a></li>
    <li><a href="admin_edit.php" title="แก้ไขข้อมูลส่วนตัว">แก้ไขข้อมูลส่วนตัว</a></li>
    <li><a href="admin.php" title="จัดการ admin">จัดการ admin</a></li>
    <li><a href="config.php" title="ตั้งค่าระบบ">ตั้งค่าระบบ</a></li>
    <li><a href="edit_user.php" title="จัดการผู้แข่งขัน">จัดการผู้แข่งขัน</a></li>
    <li><a href="coach.php" title="ตรวจ Quiz/อนุมัติเข้ารอบ">ตรวจ Quiz/อนุมัติเข้ารอบ</a></li>
    <li><a href="pay.php" title="ตรวจหลักฐานการโอนเงิน">ตรวจหลักฐานการโอนเงิน</a></li>
    <li><a href="team_message.php" title="ส่งข้อความถึงผู้แข่งขัน">ส่งข้อความถึงผู้แข่งขัน</a></li>
    <li><a href="give_sorted_id.php" title="จัดการรหัสผู้แข่งขันและห้องสอบ">จัดการรหัสผู้แข่งขันและห้องสอบ</a></li>
    <li><a href="logout.php" title="Log out">log out</a></li>
  </ul>
</nav>
<div class="content">
<div class="heading">
  <div><!-- InstanceBeginEditable name="headline" -->ส่งข้อความถึงผู้แข่งขัน<img src="../../images/news.png" width="60" height="60" alt="Log in ผู้แข่งขัน"><!-- InstanceEndEditable --></div></div>
<div class="mainContent"><!-- InstanceBeginEditable name="main_content" -->
<?
if(!isset($_REQUEST['id'])):
$stm=$db->query('SELECT team_info.id AS id, COUNT(team_message.id) AS count, team_info.team_name AS name FROM team_info LEFT JOIN team_message ON team_message.team_id=team_info.id  GROUP BY team_info.id ORDER BY name ASC');
?>
  <table width="100%" border="2" cellpadding="2" cellspacing="0">
    <tr>
      <th scope="col">Team's name</th>
      <th scope="col">จำนวนข้อความ</th>
      </tr>
<? while($row=$stm->fetch(PDO::FETCH_ASSOC)): ?>
    <tr>
      <td><?=$row['name']?></td>
      <td><?=$row['count']?> (<a href="team_message.php?id=<?=$row['id']?>" title="ดู" target="_blank" class="open">ดู | แก้ไข</a><? if($row['count']>0):?><a href="team_message.scr.php?act=clear&id=<?=$row['id']?>" title="ล้างข้อความทั้งหมดของ <?=$row['name']?>" class="ajax"> | ล้าง</a><? endif;?>)</td>
      </tr>
<? endwhile;?>
  </table>
<?
elseif(!isset($_REQUEST['msg'])):
$stm=$db->prepare('SELECT team_name FROM team_info WHERE id=?');
$stm->execute(array($_GET['id']));
?>
  <h2>ทีม <?=$stm->fetchColumn()?></h2>
<?
try{
	/*$stm=new ReflectionClass('State');
	$reflex=array(':tid'=>false);
	foreach($stm->getConstants() as $k=>$page)
		if(substr_compare($k,'STATE_',0,6,true)!=0)
			$reflex[':'.$k]=$page;*/
	foreach(State::pageList(false,':') as $page):
	$sql='SELECT team_message.id AS id, team_message.title AS title, team_message.sender_id AS sender_id, team_message.time AS time, admin.nickname AS name, admin.student_id AS std_id FROM team_message LEFT JOIN admin ON admin.id=sender_id WHERE team_message.team_id=:tid AND team_message.show_page';
	if($page===false){
		$reflex[':tid']=$_GET['id'];
		$sql.=' NOT IN('.implode(array_keys($reflex),', ').')';
	}else
		$sql.='=:sp';
	$sql.=' ORDER BY time ASC';
	
	$stm=$db->prepare($sql);
	if($page!==false){
		$stm->bindParam(':tid',$_GET['id']);
		$stm->bindValue(':sp',$page,PDO::PARAM_INT);
		$stm->execute();
	}else{
		$page=-1;
		$stm->execute($reflex);
	}
?>
  <h3 id="<?=$page?>"><?=State::getPage($page)?> (<?=$stm->rowCount()?> ข้อความ)</h3>
  <table width="100%" border="2" cellpadding="2" cellspacing="0">
    <tr>
      <th scope="col">Title</th>
      <th scope="col">Sent time</th>
      <th scope="col">sent by</th>
      </tr>
<? while($row=$stm->fetch(PDO::FETCH_ASSOC)):?>
    <tr>
      <td><a href="team_message.php?id=<?=$_GET['id']?>&msg=<?=$row['id']?>" target="_blank" class="open"><?=$row['title']?></a></td>
      <td><?=$row['time']?></td>
      <td><?=($row['sender_id']>0)?$row['name'].': '.$row['std_id']:'0: root'?></td>
      </tr>
<? endwhile;?>
    <tr>
      <td colspan="2"><a href="team_message.php?id=<?=$_GET['id']?>&msg=0&page=<?=$page?>" target="_blank" class="bold open">เพิ่มข้อความใหม่</a></td>
      <td><?=$sess->std_id.': '.$sess->nick?></td>
      </tr>
  </table>
<? 
endforeach;
}catch(Exception $e){
	echo nl2br($e."\n".$sql."\n".print_r($reflex,true));
}
else:
if($_GET['msg']>0){
	$stm=$db->prepare('SELECT title, detail, show_page AS page FROM team_message WHERE id=:id AND team_id=:tid');
	$stm->bindValue(':id',$_GET['msg']);
	$stm->bindValue(':tid',$_GET['id']);
	$stm->execute();
	$row=$stm->fetch(PDO::FETCH_ASSOC);
}else $row=array('page'=>$_GET['page']);
?>
  <form action="team_message.scr.php?<?=$_SERVER['QUERY_STRING']?>" method="post" name="form" target="_blank" id="form">
    <fieldset class="left">
      <legend>เพิ่ม/แก้ไขข้อความ</legend>
    <div>
      <label for="title">Title</label>
      <input name="title" type="text" id="title" value="<?=@$row['title']?>">
    </div>
    <div>
      <label for="page: ">แสดงใน</label>
      <select name="page" id="page">
<? foreach(State::pageList() as $k=>$r):?>
<optgroup label="<?=$k?>">
<? foreach($r as $v):?>
<option value="<?=$v?>"<?=$v==$row['page']?' selected':''?>><?=State::getPage($v)?></option>
<? endforeach;/*Group*/ ?>
</optgroup>
<? endforeach;/*Group*/ ?>
      </select>
    </div>
    <div>
      <label for="detail">Detail</label><br>
      <textarea name="detail" cols="50%" rows="10" id="detail"><?=@$row['detail']?></textarea>
    </div>
    <div class="btnset">
      <input type="submit" name="submit" id="button" value="Submit">
      <input type="reset" name="reset" id="reset" value="Reset">
      <? if(isset($row)):?>
      <a href="team_message.scr.php?<?=$_SERVER['QUERY_STRING']?>&act=del" title="ลบข้อความ" target="_blank" class="ajax">ลบ</a> <a href="team_message.scr.php?<?=$_SERVER['QUERY_STRING']?>&act=hide" title="ซ่อนข้อความ" target="_blank" class="ajax">ซ่อน</a><? endif;?></div>
    </fieldset>
  </form>
<? endif;?>
<!-- InstanceEndEditable --></div>
</div></div>
<div id="footer"></div>
</body>
<!-- InstanceEnd --></html>