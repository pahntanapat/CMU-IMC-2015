<?php
require_once 'config.inc.php';
require_once $_ROOT.'/login/config.inc.php';
require_once 'class.Session.php'; 
header("Content-Type: text/html; charset=utf-8");
$sess=new Session();
if(Session::isLogIn(true, true, $sess->load()) && count($_POST)>0)
	require 'admin.scr.php';
$db=newPDO();
?>
<!doctype html>
<html><!-- InstanceBegin template="/Templates/mahidol_admin.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta charset="utf-8">
<!-- InstanceBeginEditable name="doctitle" -->
<title>เพิ่ม/แก้ไข Admin - Admin::Mahidol Quiz</title>
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
<!-- InstanceBeginEditable name="head" --><script src="admin_edit.js"></script>
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
  <div><!-- InstanceBeginEditable name="headline" -->เพิ่ม/แก้ไข Admin<img src="../../images/signup.png" width="60" height="60" alt="Admin"><!-- InstanceEndEditable --></div></div>
<div class="mainContent"><!-- InstanceBeginEditable name="main_content" -->
<?
if(!isset($_GET['id']) || !$sess->isAuth(Session::PMS_ADMIN)) $_GET['id']=$sess->id;
$row=false;
if($_GET['id']>0){
	$stm=$db->prepare('SELECT * FROM admin WHERE id=? LIMIT 1');
	$stm->execute(array($_GET['id']));
	if($stm->rowCount()>0)
		$row=$stm->fetch(PDO::FETCH_OBJ);
}
?>
  <form action="admin_edit.php" method="post" name="form1" target="_blank">
    <fieldset>
      <legend>เพิ่ม/แก้ไข Admin</legend>
    <div>
      <label for="std_id">Student ID:</label>
      <input name="std_id" type="text" required id="std_id" placeholder="รหัสนักศึกษา" value="<?=$row?$row->student_id:''?>">
      <input name="id" type="hidden" id="id" value="<?=$row?$row->id:0?>">
    </div>
    <div>
      <label for="nick">Nickname: </label>
      <input name="nick" type="text" required id="nick" placeholder="ชื่อเล่น" value="<?=$row?$row->nickname:''?>">
      <input name="act" type="hidden" id="act" value="edit">
    </div>
    <div>
      <label for="pw">Password: </label>
      <input name="pw" type="password" required id="pw" placeholder="รหัสผ่าน" value="<?=$row?$row->password:''?>"><br>
<button type="button" data-pw="true" id="showPW">แสดง/ซ่อน Password</button>
    </div><? if($sess->isAuth(Session::PMS_ADMIN)):?>
    <fieldset class="left">
      <legend>permission</legend>
         <input name="pms[]" type="hidden" id="pms[]" value="0">
         <?
		 $r=new ReflectionClass('Session');
		 foreach($r->getConstants() as $k=>$v):
		 if(strpos($k,'PMS_')===false) continue;
		 ?>
         <input type="checkbox" name="pms[<?=$k?>]" value="<?=$v?>" id="pms_<?=$k?>"<?=$row?(Session::isPMS($row->permission,$v)?' checked':''):''?>>
        <label for="pms_<?=$k?>"><?=Session::pms($v)?></label><br>
        <? endforeach; unset($r); ?>
    </fieldset><? else:?>
    <input name="pms[0]" type="hidden" id="pms[0]" value="<?=$row->permission?>">
    <? endif;?>
    <div class="btnset"><button type="submit">บันทึก</button><button type="reset">ยกเลิก</button></div></fieldset>
  </form>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
<!-- InstanceEndEditable --></div>
</div></div>
<div id="footer"></div>
</body>
<!-- InstanceEnd --></html>
