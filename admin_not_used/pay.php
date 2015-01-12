<?php
require_once 'config.inc.php';
require_once $_ROOT.'/login/config.inc.php';
require_once 'class.Session.php';
require_once $_ROOT.'/login/upload_img_excp.php';
header("Content-Type: text/html; charset=utf-8");
$sess=new Session();
$db=newPDO();
if(Session::isLogIn(true,Session::PMS_AUDIT,$sess) && count($_POST)>0)
	require 'pay.scr.php';
?>
<!doctype html>
<html><!-- InstanceBegin template="/Templates/mahidol_admin.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta charset="utf-8">
<!-- InstanceBeginEditable name="doctitle" -->
<title>ตรวจหลักฐานการโอนเงิน - Admin::Mahidol Quiz</title>
<!-- InstanceEndEditable -->
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="../login_not_used/js/jquery-ui-1.10.4.custom.min.js"></script>
<script src="../login_not_used/js/mahidol_ajax.js"></script>
<link rel="stylesheet" href="../mahidol_quiz.css">
<link href="../mahidol.css" rel="stylesheet" type="text/css">

<script src="../login_not_used/js/mahidol_ajax.js"></script>
<link rel="stylesheet" type="text/css" href="admin.css">
<link rel="stylesheet" type="text/css" href="../login_not_used/css/jquery-ui-1.10.4.custom.css">
<!-- InstanceBeginEditable name="head" -->
<script src="pay.js"></script>
<style>
.payment{
	border:#F00 thick groove;
	border-radius:5px;
	padding:5px;
}
</style>
<!-- InstanceEndEditable -->

</head>

<body>
<div id="header"></div>
<div id="menu"></div>
<div id="content"><nav>
  <ul class="quiz_bar">
    <li><a href="../index.html" title="หน้าแรกเว็บมหิดล">หน้าแรกเว็บมหิดล</a></li>
    <li><a href="../mahidol_quiz.html" title="หน้ารายละเอียดการแข่งขัน">หน้ารายละเอียดการแข่งขัน</a></li>
    <li><a href="../login_not_used/search.php" title="ค้นหาใน Mahidol Quiz" target="_blank">Search</a></li>
    <li><a href="index.php" title="หน้าหลัก Admin">หน้าหลัก Admin</a></li>
    <li><a href="admin_edit.php" title="แก้ไขข้อมูลส่วนตัว">แก้ไขข้อมูลส่วนตัว</a></li>
    <li><a href="admin.php" title="จัดการ admin">จัดการ admin</a></li>
    <li><a href="config.php" title="ตั้งค่าระบบ">ตั้งค่าระบบ</a></li>
    <li><a href="edit_user.php" title="จัดการผู้แข่งขัน">จัดการผู้แข่งขัน</a></li>
    <li><a href="../admin/quiz.php" title="ตรวจ Quiz/อนุมัติเข้ารอบ">ตรวจ Quiz/อนุมัติเข้ารอบ</a></li>
    <li><a href="pay.php" title="ตรวจหลักฐานการโอนเงิน">ตรวจหลักฐานการโอนเงิน</a></li>
    <li><a href="team_message.php" title="ส่งข้อความถึงผู้แข่งขัน">ส่งข้อความถึงผู้แข่งขัน</a></li>
    <li><a href="give_sorted_id.php" title="จัดการรหัสผู้แข่งขันและห้องสอบ">จัดการรหัสผู้แข่งขันและห้องสอบ</a></li>
    <li><a href="logout.php" title="Log out">log out</a></li>
  </ul>
</nav>
<div class="content">
<div class="heading">
  <div><!-- InstanceBeginEditable name="headline" -->ตรวจหลักฐานการโอนเงิน<img src="../images/signin.png" width="60" height="60" alt="Log in ผู้แข่งขัน"><!-- InstanceEndEditable --></div></div>
<div class="mainContent"><!-- InstanceBeginEditable name="main_content" -->
<?
if(isset($_GET['id'])):
require_once $_ROOT.'/login/upload_img_excp.php';
$stm=$db->prepare('SELECT team_name, type, is_pay FROM team_info WHERE id=? LIMIT 1');
$stm->execute(array($_GET['id']));
$row=$stm->fetch(PDO::FETCH_ASSOC);
?>
<form action="pay.scr.php?id=<?=$_GET['id']?>" method="post" name="form" target="_blank" id="form">
  <fieldset>
    <legend>ตรวจหลักฐานการโอนเงินทีม <?=$row['team_name']?></legend>
  <div class="center bold payment">
    <?
$f=imgPay($_REQUEST['id'],$row['team_name'],$row['type']);
if(is_file($_ROOT.$f)): ?>
    <img src="<?=$f?>" title="ปพ.1">
    <input name="img" type="hidden" id="img" value="<?=$f?>">
    <? else: ?>ไม่พบหลักฐานการจ่ายเงิน<? endif;?></div>
  <div class="btnset">
    <input type="radio" name="pass" value="<?=State::STATE_PASS?>" id="pass_0"<?=($row['is_pay']==State::STATE_PASS)?' checked':''?>>
    <label for="pass_0"><?=State::getIcon(State::STATE_PASS)?> ผ่าน</label>
    <input type="radio" name="pass" value="<?=State::STATE_LOCKED?>" id="pass_1"<?=($row['is_pay']==State::STATE_LOCKED)?' checked':''?>>
    <label for="pass_1"><?=State::getIcon(State::STATE_LOCKED)?>  ล็อก</label>
    <input type="radio" name="pass" value="<?=State::STATE_WAIT?>" id="pass_2"<?=($row['is_pay']==State::STATE_WAIT)?' checked':''?>>
    <label for="pass_2"><?=State::getIcon(State::STATE_WAIT)?>  รอ</label>
    <input type="radio" name="pass" value="<?=State::STATE_MUST_CHANGE?>" id="pass_3"<?=($row['is_pay']==State::STATE_MUST_CHANGE)?' checked':''?>>
    <label for="pass_3"><?=State::getIcon(State::STATE_MUST_CHANGE)?>  ไม่ผ่าน</label></div>
  <ol class="left">
    <li><a href="team_message.php?id=<?=$_GET['id']?>#<?=State::PAGE_PAY?>" title="เหตุผลที่ไม่ผ่าน (คร้งที่แล้ว ถ้ามี) ถ้าไม่ผ่านกรุณระบุเหตุผล" target="_blank">เหตุผลที่ไม่ผ่าน (คร้งที่แล้ว ถ้ามี)</a>  </li>
    <li><a href="team_message.php?id=<?=$_GET['id']?>#<?=State::PAGE_PAY?>" title="เหตุผลที่ไม่ผ่าน (คร้งที่แล้ว ถ้ามี) ถ้าไม่ผ่านกรุณระบุเหตุผล" target="_blank">ถ้าไม่ผ่านกรุณระบุเหตุผล</a>  </li>
  </ol>
    <div class="btnset">
      <input type="submit" name="button" id="button" value="Submit">    
      <input type="reset" name="button2" id="button2" value="Reset"> </div>
  </fieldset>
</form>

<? else:?>
  <h2>ทีมที่จ่ายเงินแล้ว</h2>
<?
try{
	$stm=$db->prepare('SELECT id, team_name, is_pay FROM team_info WHERE is_pay!=:lock ORDER BY (1+is_pay)%:mx, id ASC');
	$stm->bindValue(':lock',State::STATE_LOCKED,PDO::PARAM_INT);
	$stm->bindValue(':mx',State::STATE_MUST_CHANGE,PDO::PARAM_INT);
	$stm->execute();
?>
  <ul>
<? while($row=$stm->fetch(PDO::FETCH_ASSOC)): ?>    <li><a href="pay.php?id=<?=$row['id']?>" target="_blank" class="open"><?=State::getIcon($row['is_pay']).' '.$row['team_name']?></a></li><? endwhile;?>
  </ul>
<?
}catch(Exception $e){
	echo nl2br($e);
}
endif;
?>
<form name="form1" method="post" action="">
</form>
<p>&nbsp;</p>
<!-- InstanceEndEditable --></div>
</div></div>
<div id="footer"></div>
</body>
<!-- InstanceEnd --></html>