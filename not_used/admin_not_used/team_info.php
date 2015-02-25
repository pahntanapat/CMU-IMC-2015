<?php
require_once 'config.inc.php';
require_once $_ROOT.'/login/config.inc.php';
require_once 'class.Session.php';

require_once $_ROOT.'/login/upload_img_excp.php';
$sess=new Session();
if(Session::isLogIn(true,Session::PMS_STUDENT,$sess->load()) && isset($_REQUEST['act']) && 'check'!=@$_REQUEST['act'])
	require_once 'team_info.scr.php';
$db=newPDO();
?>
<!doctype html>
<html><!-- InstanceBegin template="/Templates/mahidol_admin.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta charset="utf-8">
<!-- InstanceBeginEditable name="doctitle" -->
<title>แก้ไขข้อมูลทีม - Admin::Mahidol Quiz</title>
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
<script src="team_info.js"></script>
<style>
.tick>*{display:inline-block}
.tick>div{width:200px}
.tick>div:last-child{
	width:100px;
	font-weight:bold;
}
</style>
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
  <div><!-- InstanceBeginEditable name="headline" -->แก้ไขข้อมูลทีม<img src="../../images/signin.png" width="60" height="60" alt="Log in ผู้แข่งขัน"><!-- InstanceEndEditable --></div></div>
<div class="mainContent"><!-- InstanceBeginEditable name="main_content" -->
<?php
if(@$_REQUEST['act']=='check'):
$stm=$db->prepare('SELECT email AS Email, team_name AS "Team\'s name", type, t_firstname AS "Teacher\'s firstname", t_lastname AS "Teacher\'s lastname", t_phone AS "Teacher\'s phone" FROM team_info WHERE id=? LIMIT 1');
$stm->execute(array($_REQUEST['id']));
$row=$stm->fetch(PDO::FETCH_ASSOC);
?>
  <form action="team_info.php?act=0&id=<?=$_REQUEST['id']?>" method="post" name="check" target="_blank" id="check">
    <fieldset class="left">
      <legend>Check list ตรวจข้อมูลทีม</legend>
	  <? foreach($row as $k=>$v):?>
    <div class="tick"><label for="pass_<?=$k?>"><?=$k?></label>
    <div><?=$k=='type'?'ทีม'.($v?'โรงเรียน':'อิสระ'):$v?></div>
      <div><input name="pass[<?=$k?>]" type="checkbox" id="pass_<?=$k?>" value="1"> ผ่าน</div></div>
<? endforeach;?>
  <ol>
    <li><a href="team_message.php?id=<?=$_REQUEST['id']?>#<?=State::SECT_INFO_TEAM?>" title="เหตุผลที่ไม่ผ่าน (ครั้งก่อน ถ้ามี)" target="_blank">เหตุผลที่ไม่ผ่าน (ครั้งก่อน ถ้ามี)</a>    </li>
    <li><a href="team_message.php?id=<?=$_REQUEST['id']?>#<?=State::SECT_INFO_TEAM?>" title="เหตุผลที่ไม่ผ่าน (ครั้งก่อน ถ้ามี)" target="_blank">ถ้าไม่ผ่านกรุณาระบุเหตุผลที่นี่</a>    </li>
    <li>ระบบจะลบข้อความแจ้งเตือนทีมส่วนข้อมูลทีมออกจากระบบโดยอัตโนมัติ ถ้ากรรมการอนุมัติว่าผ่าน</li>
</ol>
<div class="btnset">
  <input name="pass[]" type="hidden" id="pass[]" value="<?=count($row)+1?>">
  <input type="submit" name="submit" id="submit" value="Submit">
  <input type="reset" name="reset" id="reset" value="Reset">
</div>
    </fieldset>
  </form>
<?php
$stm=$db->prepare('SELECT id, email AS Email, title AS "คำนำหน้า", gender, firstname, lastname, phone, school, sci_grade AS "เกรดวิทย์" FROM participant_info WHERE team_id=?');
$stm->execute(array($_REQUEST['id']));

$i=1;
while($row=$stm->fetch(PDO::FETCH_ASSOC)):
?> <br>
    <form action="team_info.php?act=<?=$i?>&id=<?=$_REQUEST['id']?>" method="post" name="check" target="_blank" id="check"><fieldset class="left"><legend>Check list ข้อมูลนักเรียนคนที่ <?=$i?></legend>
      <? foreach($row as $k=>$v):?>
    <div class="tick"><label for="pass_<?=$k?>"><?=$k?></label>
    <div><?=$k=='gender'?($v?'ชาย':'หญิง'):$v?></div>
     <div><input name="pass[<?=$k?>]" type="checkbox" id="pass_<?=$k?>" value="1">   ผ่าน</div></div>
<? endforeach;?>
<p><?
$f=imgTSP($_REQUEST['id'],$row['id']);
if(is_file($_ROOT.$f)): ?><img src="<?=$f?>" title="ปพ.1"><? else: ?> <strong>ไม่พบใบปพ.1</strong><? endif;?></p>
<div class="tick"><label for="tsp_<?=$i?>">ปพ.1</label><div><input name="tsp" type="checkbox" id="tsp_<?=$i?>" value="1"> ผ่าน</div></div>
<ol>
        <li><a href="team_message.php?id=<?=$_REQUEST['id']?>#<?=State::SECT_INFO_STD_1-1+$i?>" title="เหตุผลที่ไม่ผ่าน (ครั้งก่อน ถ้ามี)" target="_blank">เหตุผลที่ข้อมูลผู้แข่งขันไม่ผ่าน (ครั้งก่อน ถ้ามี)</a></li>
        <li><a href="team_message.php?id=<?=$_REQUEST['id']?>#<?=State::SECT_INFO_STD_1-1+$i?>" title="เหตุผลที่ไม่ผ่าน (ครั้งก่อน ถ้ามี)" target="_blank">ถ้าข้อมูลผู้แข่งขันไม่ผ่านกรุณาระบุเหตุผลที่นี่</a></li>
        <li><a href="team_message.php?id=<?=$_REQUEST['id']?>#<?=State::SECT_TSP_STD_1-1+$i?>" title="เหตุผลที่ไม่ผ่าน (ครั้งก่อน ถ้ามี)" target="_blank">เหตุผลที่ปพ.1ไม่ผ่าน (ครั้งก่อน ถ้ามี)</a></li>
        <li><a href="team_message.php?id=<?=$_REQUEST['id']?>#<?=State::SECT_TSP_STD_1-1+$i?>" title="เหตุผลที่ไม่ผ่าน (ครั้งก่อน ถ้ามี)" target="_blank">ถ้าปพ.1ไม่ผ่านกรุณาระบุเหตุผลที่นี่</a></li>
        <li>ระบบจะลบข้อความแจ้งเตือนทีมส่วนข้อมูลผู้แข่งขันคนที่ 
            <?=$i?>  และใบปพ.1 ออกจากระบบโดยอัตโนมัติ ถ้ากรรมการอนุมัติว่าผ่าน</li>
</ol>
  <div class="btnset">
    <input name="sid" type="hidden" id="sid" value="<?=$row['id']?>">
    <input name="pass[]" type="hidden" id="pass[]" value="<?=count($row)+1?>">
    <input type="submit" name="submit" id="submit" value="Submit">
    <input type="reset" name="reset" id="reset" value="Reset">
  </div>
      </fieldset>
    </form>
<?php
$i++;
endwhile;
else:
$stm=$db->prepare('SELECT email,password,team_name,type,t_firstname,t_lastname,t_phone FROM team_info WHERE id=? LIMIT 1');
$stm->execute(array($_REQUEST['id']));
$row=$stm->fetch(PDO::FETCH_ASSOC);
?>
  <form action="team_info.php?act=edit&id=<?=$_REQUEST['id']?>" method="post" name="check" target="_blank" id="check">
    <fieldset>
      <legend>แก้ไขข้อมูลทีม</legend>
<? foreach($row as $k=>$v):?>
<div><label for="edit[:<?=$k?>]"><?=$k?><?=$k=='type'?' (0 = ร.ร., 1 = อิสระ)':(strpos($k,'t_')===false?'':' (t_ = Teacher\'s)')?></label><input name="edit[:<?=$k?>]" type="text" id="edit[:<?=$k?>]" value="<?=$v?>"></div>
<? endforeach;?>
<div class="btnset">
      <input type="submit" name="submit2" id="submit2" value="Submit">
      <input type="reset" name="reset2" id="reset2" value="Reset">
</div>
    </fieldset>
  </form>
<?
$stm=$db->prepare('SELECT id, title, firstname, lastname, gender, phone, email, school, sci_grade FROM participant_info WHERE team_id=?');
$stm->execute(array($_REQUEST['id']));
$i=1;
while($row=$stm->fetch(PDO::FETCH_ASSOC)):
?><br>
  <form action="team_info.php?act=edit&id=<?=$_REQUEST['id']?>" method="post" name="form1" target="_blank">
  <fieldset>
    <legend>แก้ไขข้อมูลผู้สมัครคนที่  <?=$i?></legend>
  <? foreach($row as $k=>$v): if($k=='id') continue;?>
<div>
      <label for="edit[:<?=$k?>]"><?=$k?><?=$k=='gender'?' (0 = หญิง, 1 = ชาย)':($k=='sci_grade'?' เกรดวิทย์':'')?></label>
      <input name="edit[:<?=$k?>]" type="text" id="edit[:<?=$k?>]" value="<?=$v?>">
</div>
<? endforeach;?>
<div class="btnset">
      <input type="submit" name="submit2" id="submit2" value="Submit">
      <input name="id" type="hidden" id="id" value="<?=$row['id']?>">
      <input type="reset" name="reset2" id="reset2" value="Reset">
</div>
  </fieldset>
  </form>
<?
$i++;
endwhile;
endif;
?>
  <p>&nbsp;</p>
<!-- InstanceEndEditable --></div>
</div></div>
<div id="footer"></div>
</body>
<!-- InstanceEnd --></html>