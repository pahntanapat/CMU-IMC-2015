<?php
require_once 'config.inc.php';
require_once $_ROOT.'/login/config.inc.php';
require_once 'class.Session.php';
$sess=new Session();
if(Session::isLogIn(true,Session::PMS_QUIZ,$sess) && (count($_POST)>0 || isset($_REQUEST['act'])))
	require 'quiz.scr.php';

$db=newPDO();
?>
<!doctype html>
<html><!-- InstanceBegin template="/Templates/mahidol_admin.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta charset="utf-8">
<!-- InstanceBeginEditable name="doctitle" -->
<title>ตรวจ Quiz และอนุมัติผ่านเข้ารอบ - Admin::Mahidol Quiz</title>
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
<script src="coach.js"></script>
<style>
label{min-width:0px}
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
    <li><a href="coach.php" title="ตรวจ Quiz/อนุมัติเข้ารอบ">ตรวจ Quiz/อนุมัติเข้ารอบ</a></li>
    <li><a href="pay.php" title="ตรวจหลักฐานการโอนเงิน">ตรวจหลักฐานการโอนเงิน</a></li>
    <li><a href="team_message.php" title="ส่งข้อความถึงผู้แข่งขัน">ส่งข้อความถึงผู้แข่งขัน</a></li>
    <li><a href="give_sorted_id.php" title="จัดการรหัสผู้แข่งขันและห้องสอบ">จัดการรหัสผู้แข่งขันและห้องสอบ</a></li>
    <li><a href="logout.php" title="Log out">log out</a></li>
  </ul>
</nav>
<div class="content">
<div class="heading">
  <div><!-- InstanceBeginEditable name="headline" -->ตรวจ Quiz และอนุมัติผ่านเข้ารอบ<img src="../images/document.png" width="60" height="60" alt="Log in ผู้แข่งขัน"><!-- InstanceEndEditable --></div></div>
<div class="mainContent"><!-- InstanceBeginEditable name="main_content" -->
<?
if(!isset($_REQUEST['id'])):
?>
  <h2>ตรวจ Quiz</h2>
  <form action="coach.php" method="post" target="_blank" id="quiz"><fieldset><legend>ตรวจ QUIZ</legend>
  <table width="100%" border="2" cellpadding="2" cellspacing="0">
    <tr>
      <th scope="col">state</th>
      <th scope="col">Team's Name</th>
      <th scope="col">start</th>
      <th scope="col">lasted update</th>
      <th scope="col">used time (s)</th>
      <th scope="col">score</th>
      <th scope="col">ตรวจ</th>
      <th scope="col">ลบ</th>
    </tr>
<? foreach($db->query('SELECT coach_info.id AS id, team_info.team_name AS teamName, coach_info.start_time AS start, coach_info.sent_time AS sent, coach_info.used_time AS usedTime, coach_info.state AS state, coach_info.score AS score FROM coach_info INNER JOIN team_info ON team_info.id=coach_info.team_id ORDER BY score DESC, usedTime, sent, start, id ASC') as $row):?>
    <tr>
      <td>
        <input type="radio" name="pass[<?=$row['id']?>]" value="<?=State::STATE_NOT_PASS?>" id="pass[<?=$row['id']?>]_0"<?=($row['state']==State::STATE_NOT_PASS)?' checked':''?>>
        <label for="pass[<?=$row['id']?>]_0">ไม่ผ่าน (<?=State::getIcon(State::STATE_NOT_PASS)?>)</label>
        <input type="radio" name="pass[<?=$row['id']?>]" value="<?=State::STATE_WAIT?>" id="pass[<?=$row['id']?>]_1"<?=($row['state']==State::STATE_WAIT)?' checked':''?>>
        <label for="pass[<?=$row['id']?>]_1">รอ (<?=State::getIcon(State::STATE_WAIT)?>)</label>
        <input type="radio" name="pass[<?=$row['id']?>]" value="<?=State::STATE_PASS?>" id="pass[<?=$row['id']?>]_2"<?=($row['state']==State::STATE_PASS)?' checked':''?>>
        <label for="pass[<?=$row['id']?>]_2">ผ่าน (<?=State::getIcon(State::STATE_PASS)?>)</label>
      </td>
      <td><?=$row['teamName']?></td>
      <td><?=$row['start']?></td>
      <td><?=$row['sent']?></td>
      <td><?=$row['usedTime']?></td>
      <td><?=(is_null($row['score']))?"ไม่ได้ตรวจ":$row['score']?></td>
      <td><a href="coach.php?id=<?=$row['id']?>" title="ตรวจ" target="_blank" class="open">ตรวจ</a></td>
      <td><a href="coach.php?act=del&id=<?=$row['id']?>" title="ลบคำตอบทีม <?=$row['teamName']?>" target="_blank" class="del">ลบ</a></td>
    </tr>
<? endforeach;?>
  </table>
  <div class="btnset">
    <input type="submit" name="submit" id="submit" value="Submit">
    <input type="reset" name="reset" id="reset" value="Reset">
  </div></fieldset>
  </form>
  <h2>ยืนยันทีมที่ผ่านเข้ารอบ</h2>
  <form action="coach.php" method="post" name="confirm" target="_blank" id="confirm">
    <fieldset>
      <legend>ยืนยันทีมที่ผ่านเข้ารอบ
<?
try{
	$stm=$db->prepare('SELECT
team_info.id AS id, team_info.team_name AS name, team_info.type AS type, team_info.is_pay AS state,
coach_info.score AS score
FROM team_info
LEFT JOIN coach_info ON team_info.id=coach_info.team_id AND coach_info.state=:pass
INNER JOIN participant_info ON team_info.id=participant_info.team_id AND participant_info.is_pass=:pass AND participant_info.is_upload=:pass
WHERE team_info.is_pass=:pass GROUP BY id
ORDER BY id, coach_info.used_time ASC, score, AVG(participant_info.sci_grade) DESC');
	$stm->bindValue(':pass',State::STATE_PASS,PDO::PARAM_INT);
	$stm->execute();
?>
    </legend>
      <table width="100%" border="2" cellpadding="2" cellspacing="0">
      <tr>
        <th scope="col">รอ/ไม่ผ่าน/ผ่าน</th>
        <th scope="col">Team's name</th>
        <th scope="col">ประเภท</th>
        <th scope="col">คะแนน</th>
      </tr>
<? while($row=$stm->fetch(PDO::FETCH_ASSOC)):?>
      <tr>
        <td>
        <input type="radio" name="pass[<?=$row['id']?>]" value="<?=State::STATE_LOCKED?>" id="pass[<?=$row['id']?>][0]"<?=($row['state']==State::STATE_LOCKED)?' checked':''?>>
        <label for="pass[<?=$row['id']?>][0]">รอ (<?=State::getIcon(State::STATE_WAIT)?>)</label>
         <input type="radio" name="pass[<?=$row['id']?>]" value="<?=State::STATE_NOT_PASS?>" id="pass[<?=$row['id']?>][1]"<?=($row['state']==State::STATE_NOT_PASS)?' checked':''?>>
        <label for="pass[<?=$row['id']?>][1]">ไม่ผ่าน (<?=State::getIcon(State::STATE_NOT_PASS)?>)</label>
        <input type="radio" name="pass[<?=$row['id']?>]" value="<?=State::STATE_NOT_FINISHED?>" id="pass[<?=$row['id']?>][2]"<?=($row['state']==State::STATE_NOT_FINISHED)?' checked':''?>>
        <label for="pass[<?=$row['id']?>][2]">ผ่าน (<?=State::getIcon(State::STATE_PASS)?>)</label>
      </td>
        <td><?=$row['name']?></td>
        <td><?=($row['type'])?'อิสระ':'โรงเรียน'?></td>
        <td><?=($row['type'])?$row['score']:'-'?></td>
      </tr>
<? endwhile;?>
    </table><? }catch(Exception $e){echo nl2br($e);}?>
  <div class="btnset"><input type="submit" name="button" id="button" value="Submit"><input type="reset" name="button2" id="button2" value="Reset"><input name="confirm" type="hidden" id="confirm" value="confirm"></div>
    </fieldset>
  </form>
<?
else:
$stm=$db->prepare('SELECT coach_info.used_time AS usedTime, coach_info.start_time AS start, coach_info.sent_time AS sent, coach_info.answer AS answer, coach_info.state AS state, coach_info.score AS score, coach_info.comment AS comment, team_info.team_name AS teamName FROM coach_info INNER JOIN team_info ON team_info.id=coach_info.team_id WHERE coach_info.id=?');
$stm->execute(array($_REQUEST['id']));
$row=$stm->fetch(PDO::FETCH_ASSOC);
unset($stm);
?>
  <form action="quiz.scr.php?id=<?=$_REQUEST['id']?>" method="post" name="form" target="_blank" id="form"><fieldset><legend>QUIZ</legend>
  <div class="bold">start: <?=$row['start']?> sent: <?=$row['sent']?> used time: <?=$row['usedTime']?> s</div>
  <div><label for="ans">คำตอบ</label><b>state: <?=State::getIcon($row['state'])?></b><br><textarea id="ans" cols="80%" rows="15" readonly><?=$row['answer']?></textarea></div>
  <div>
    <label for="score">score</label>
    <input name="score" type="text" id="score" value="<?=$row['score']?>">
  </div>
  <div>
    <label for="comment">comment ของ กรรมการ (ไม่แสดงให้ผู้แข่งขันเห็น)</label><br>
    <textarea name="comment" cols="80%" rows="10" id="comment"><?=$row['comment']?></textarea>
  </div>
  <div class="btnset">
    <input type="submit" name="Submit" id="Submit" value="Submit">
    <input type="reset" name="Reset" id="Reset" value="Reset">
  </div></fieldset>
  </form>
  <p>&nbsp;</p>
<? endif; ?>
<!-- InstanceEndEditable --></div>
</div></div>
<div id="footer"></div>
</body>
<!-- InstanceEnd --></html>