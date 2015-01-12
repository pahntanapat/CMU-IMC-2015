<?php
session_start();
require_once '../config.inc.php';
require_once "main_function.php";
require_once "check_session.php";
$sess=checkSession::mustLogIn();

ob_start();
require_once 'result_box.php';
$db=newPDO();
$result=new resultCon(false);
if(count($_POST)<=0)
	$result->result=NULL;
elseif(isset($_POST['id'])){ // Student ID
	if($sess->sStdInfo[$_POST['i']]<CheckSession::STATE_LOCKED ||
		($sess->sStdInfo[$_POST['i']]==CheckSession::STATE_NOT_FINISHED &&
		$_END_EDIT_INFO<time()))
		echo "ไม่อนุญาตให้บันทึกรายละเอียดผู้แข่งขัน ";
	elseif(!(strlen(trim($_POST['firstname']))>0 && strlen(trim($_POST['lastname']))>0 && strlen(trim($_POST['title']))>0))
		echo "กรุณากรอกคำนำหน้าชื่อ ชื่อ และนามสกุล";
	elseif(!isset($_POST['gender']))
		echo "กรุณาเลือกเพศ";
	elseif(!preg_match_all('/^\d{9,10}$/',$_POST['phone'],$stm))
		echo "เบอร์โทรศัพท์ต้องเป็นตัวเลข 9-10 หลัก";
	elseif(!is_numeric($_POST['sci_grade']))
		echo "เกรดเฉลี่ยต้องเป็นตัวเลข";
	elseif($_POST['sci_grade']<3.25)
		echo "เกรดเฉลี่ยต่ำกว่า 3.25 ต่ำกว่ากติกากำหนด";
	elseif(strlen(trim(@$_POST['school']))<=0 && ($_POST['id']=='' || $sess->type==1))
		echo "กรุณากรอกโรงเรียน";
	elseif(strlen(trim($_POST['email']))<=0)
		echo "กรุณากรอก email";
	elseif(filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)===false)
		echo "รูปแบบ email ไม่ถูกต้อง";
	else try{
		$db->beginTransaction();
		if($_POST['id']=='') /*new student*/
			$sql='INSERT INTO participant_info (team_id,title,firstname,lastname,gender,phone,email,school,sci_grade,is_pass) VALUES (:tid,:tt,:fn,:ln,:g,:ph,:em,:sch,:sg,:p)';
		elseif($sess->type==1) /*update for independent team*/
			$sql='UPDATE participant_info SET title=:tt, firstname=:fn, lastname=:ln, phone=:ph, gender=:g, school=:sch, email=:em, sci_grade=:sg, is_pass=:p WHERE id=:id AND team_id=:tid AND is_pass>:lock';
		else /*update for school team*/
			$sql='UPDATE participant_info SET title=:tt, firstname=:fn, lastname=:ln, phone=:ph, gender=:g, email=:em, sci_grade=:sg, is_pass=:p WHERE id=:id AND team_id=:tid AND is_pass>:lock';
		
		$stm=$db->prepare($sql);
		$stm->bindParam(':tid',$sess->ID);
		$stm->bindParam(':tt',$_POST['title']);
		$stm->bindParam(':fn',$_POST['firstname']);
		$stm->bindParam(':ln',$_POST['lastname']);
		$stm->bindParam(':g',$_POST['gender'],PDO::PARAM_BOOL);
		$stm->bindParam(':ph',$_POST['phone']);
		$stm->bindParam(':em',$_POST['email']);
		$stm->bindParam(':sg',$_POST['sci_grade']);
		
		
		$stm->bindValue(':p',CheckSession::STATE_EDITTABLE,PDO::PARAM_INT);
		if($_POST['id']=='' || $sess->type==1)	$stm->bindParam(':sch',$_POST['school']);
		if($_POST['id']!=''){
			$stm->bindParam(':id',$_POST['id']);
			$stm->bindValue(':lock',CheckSession::STATE_LOCKED,PDO::PARAM_INT);
		}
		$stm->execute();

		if($sess->type==0 && $_POST['i']==0 && $_POST['id']!=''){
			$sql='UPDATE participant_info SET school=:sch, is_pass=:p WHERE team_id=:tid';
			$stm=$db->prepare($sql);
			$stm->bindParam(':sch',$_POST['school']);
			$stm->bindValue(':p',CheckSession::STATE_EDITTABLE,PDO::PARAM_INT);
			$stm->bindParam(':tid',$sess->ID);
			if(!$stm->execute())
				throw new PDOException($stm->errorInfo());
		}
		$result->result=true;
		echo "บันทึกรายละเอียดผู้แข่งขันคนที่ ".($_POST['i']+1)." สำเร็จ";
		$db->commit();
		$_SESSION=$sess->updateData($db,$_SESSION,0);
	}catch(Exception $e){
		$db->rollBack();
		echo "ไมสามารถบันทึกรายละเอียดผู้แข่งขันคนที่ ".($_POST['i']+1)." ได้เนื่องจาก\n$e\n";
		echo $e->getMessage()."\n".$stm->errorCode().": ".$stm->errorInfo()."\n".$e->getLine()."\n"."SQL = $sql";
		$result->result=false;
	}
}else{
	if($sess->sTeamInfo<=CheckSession::STATE_LOCKED ||
		($sess->sTeamInfo==CheckSession::STATE_NOT_FINISHED &&
		$_END_EDIT_INFO<time()))
		echo "ไม่อนุญาตให้บันทึกรายละเอียดทีม";
	elseif(strlen(trim($_POST['team_name']))<=0)
		echo "กรุณากรอกชื่อทีม";
	elseif(strlen(trim($_POST['t_firstname']))<=0 || strlen(trim($_POST['t_lastname']))<=0)
		echo "กรุณากรอกชื่อและนามสกุลครูที่ปรึกษา";
	elseif(!preg_match_all('/^\d{9,10}$/',$_POST['t_phone'],$stm))
		echo "เบอร์โทรศัพท์ต้องเป็นตัวเลข 9-10 หลัก";
	else try{
		$db->beginTransaction();
		foreach($_POST as $k=>$v) $_POST[$k]=trim($v);
		if($sess->teamName(true)==''){
			$stm=$db->prepare('SELECT COUNT(*) FROM team_info WHERE team_name like ?');
			$stm->execute(array($_POST['team_name']));
			$k=$stm->fetchColumn();
		}else $k=0;
		if($k>0){
			echo "ชื่อทีมซ้ำ กรุณาเปลี่ยนชื่อทีม".$k;
			$db->rollBack();
		}else{
			$stm=$db->prepare('UPDATE team_info SET team_name=:tn, t_firstname=:tf, t_lastname=:tl, t_phone=:tp, is_pass=:p WHERE id=:id AND is_pass>:lock');
			$stm->bindParam(':id',$sess->ID);
			$stm->bindParam(':tn',$_POST['team_name']);
			$stm->bindParam(':tf',$_POST['t_firstname']);
			$stm->bindParam(':tl',$_POST['t_lastname']);
			$stm->bindParam(':tp',$_POST['t_phone']);
			$stm->bindValue(':p',CheckSession::STATE_EDITTABLE,PDO::PARAM_INT);
			$stm->bindValue(':lock',CheckSession::STATE_LOCKED,PDO::PARAM_INT);
			if($stm->execute()){
				$result->result=true;
				echo "บันทึกรายละเอียดทีมสำเร็จ";
				$db->commit();
			}else{
				$result->result=false;
				echo "ไม่สามารถบันทึกรายละเอียดทีมได้";
				$db->rollBack();
			}
		}
		$_SESSION=$sess->updateData($db,$_SESSION,0);
	}catch(Exception $e){
		$db->rollBack();
		echo "ไม่สามารถบันทึกรายละเอียดได้เนื่องจาก\n$e\n\n";
		echo $e->getMessage();
		$result->result=false;
	}
}

$_SESSION=$sess->updateData($db,$_SESSION,180);
$result->addIfisStr(nl2br(ob_get_clean()));

if(isset($_GET['ajax'])):
ob_start();
else:
?>
<!doctype html>
<html><!-- InstanceBegin template="/Templates/mahidol_login.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta charset="utf-8">
<!-- InstanceBeginEditable name="doctitle" -->
<title>กรอกข้อมูลผู้แข่งขัน - Mahidol Quiz 2014: การแข่งขันตอบปัญหาวิทยาศาสตร์สุขภาพ ชิงถ้วยสมเด็จพระเทพรัตนราชสุดา สยามบรมราชกุมารี เนื่องในวันมหิดล ประจำปี พ.ศ. 2557</title>
<!-- InstanceEndEditable -->
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="js/jquery-ui-1.10.4.custom.min.js"></script>
<script src="js/mahidol_ajax.js"></script>
<link rel="stylesheet" href="../mahidol_quiz.css">
<link href="../mahidol.css" rel="stylesheet" type="text/css">

<script src="js/mahidol_ajax.js"></script>
<link href="css/mahidol_quiz.css" rel="stylesheet" />
<link href="css/jquery-ui-1.10.4.custom.css" rel="stylesheet" type="text/css" />
<!-- InstanceBeginEditable name="head" -->
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.1.47/jquery.form-validator.min.js"></script>
<script src="js/team_info.js"></script>
<!-- InstanceEndEditable -->

</head>

<body>
<div id="header"></div>
<div id="menu"></div>
<div id="content">
<div class="content">
<div class="heading">
  <div><!-- InstanceBeginEditable name="headline" -->กรอกข้อมูลผู้แข่งขัน<img src="../images/info.png" width="60" height="60" alt="Log in ผู้แข่งขัน"><!-- InstanceEndEditable --></div></div>
<div class="main_content"><!-- InstanceBeginEditable name="main_content" -->
  <div id="tab"><? endif ?>
  <div id="tabs">
    <ul>
<?php
	$tabs=$sess->tabsArrangeForTeamInfo();
	if($tabs==-1): ?>
<li><a href="#tab-<?=++$tabs?>" title="เพิ่มข้อมูลทีม">เพิ่มรายละเอียดทีม</a></li>
<?
	elseif($tabs!==false):?>
<li><a href="#tab-<?=++$tabs?>" title="เพิ่มข้อมูลผู้แข่งขันคนที่ <?=$tabs?>">เพิ่มผู้แข่งขันคนที่ <?=$tabs?></a></li>
<? else: $tabs=4;endif ?>
<li><a href="#tab-conclude" title="ข้อมูลสรุป">สรุป</a></li>
<?php
if($sess->menuClass(CheckSession::PAGE_TEAM_INFO,NULL)>0)
	for($i=0;$i<$tabs;$i++){
	if($i==0): if($sess->sTeamInfo<CheckSession::STATE_LOCKED) continue;?>
<li><a href="#tab-<?=$i?>" title="แก้ไขข้อมูลทีม">แก้ไขรายละเอียดทีม</a></li>
<?
	else: if($sess->sStdInfo[$i-1]<CheckSession::STATE_LOCKED) continue;?>
<li><a href="#tab-<?=$i?>" title="แก้ไขข้อมูลผู้แข่งขันคนที่ <?=$i?>">แก้ไขผู้แข่งขันคนที่ <?=$i?></a></li> 
<? endif; } ?>
    </ul>
    <div id="tab-conclude">
      <h2>รายละเอียดทีมและผู้แข่งขัน</h2><?=$sess->showState(CheckSession::PAGE_TEAM_INFO)?>
      <?=$sess->teamMessage($db,CheckSession::PAGE_TEAM_INFO,true)?>
      <h3>ส่วนที่ 1 รายละเอียดทีม: <?=$sess->teamName()?></h3>
<?php
echo $sess->showState(CheckSession::SECT_INFO_TEAM);
try{
	$stm=$db->prepare('SELECT team_name,email,CHAR_LENGTH(password) AS pw,type,t_firstname,t_lastname,t_phone FROM team_info WHERE id=:id');
	$stm->execute(array(':id'=>$sess->ID));
	$teamInfo=$stm->fetch(PDO::FETCH_ASSOC);
?>
      <table border="0">
        <tr>
          <th scope="row">ชื่อทีม</th>
          <td><?=$teamInfo['team_name']?></td>
        </tr>
        <tr>
          <th scope="row">Email ที่ใช้ log in</th>
          <td><?=$teamInfo['email']?></td>
        </tr>
        <tr>
          <th scope="row">รหัสผ่าน</th>
          <td><? while($teamInfo['pw']>0){echo '*';$teamInfo['pw']--;} ?></td>
        </tr>
        <tr>
          <th scope="row">ประเภททีม</th>
          <td><?=($teamInfo['type'])?'ทีมอิสระ':'ทีมโรงเรียน'?></td>
        </tr>
        <tr>
          <th scope="row">ครูที่ปรึกษา</th>
          <td>คุณครู <?=$teamInfo['t_firstname'].' '.$teamInfo['t_lastname']?></td>
        </tr>
        <tr>
          <th scope="row">เบอร์โทรศัพท์ครูที่ปรึกษา</th>
          <td><?=$teamInfo['t_phone']?></td>
        </tr>
      </table>
<?php
}catch(Exception $e){
	$err=new resultCon(false);
	$err->message="ไม่สามารถแสดงข้อมูลทีมได้ได้เนื่องจาก\n$e";
	echo $err->getIfNotNull();
	unset($err);
}
try{
	$stm=$db->prepare('SELECT id,title,firstname,lastname,gender,phone,school,email,sci_grade FROM participant_info WHERE team_id=:id ORDER BY id');
	$stm->execute(array(':id'=>$sess->ID));
	$student=($stm->rowCount()>0)? $stm->fetchAll(PDO::FETCH_ASSOC):array();
	for($i=0;$i<$stm->rowCount();$i++){
?>
<h3>ส่วนที่ <?=($i+2)?> รายละเอียดผู้แข่งขันคนที่ <?=($i+1)?></h3>
<?=$sess->showState(CheckSession::SECT_INFO_STD_1+$i)?>
<table border="0">
  <tr>
    <th scope="row">ชื่อ-นามสกุล</th>
    <td><?=$student[$i]['title'].' '.$student[$i]['firstname'].' '.$student[$i]['lastname']?></td>
  </tr>
  <tr>
    <th scope="row">เพศ</th>
    <td><?=($student[$i]['gender'])?'ชาย':'หญิง'?></td>
  </tr>
  <tr>
    <th scope="row">เบอร์โทรศัพท์</th>
    <td><?=$student[$i]['phone']?></td>
  </tr>
  <tr>
    <th scope="row">Email</th>
    <td><?=$student[$i]['email']?></td>
  </tr>
  <tr>
    <th scope="row">โรงเรียน</th>
    <td><?=$student[$i]['school']?></td>
  </tr>
  <tr>
    <th scope="row">เกรดเฉลี่ยวิชาวิทยาศาสตร์ภาคเรียนล่าสุด</th>
    <td><?=$student[$i]['sci_grade']?></td>
  </tr>
  </table>
<? } }catch(Exception $e){
	$err=new resultCon(false);
	$err->message="ไม่สามารถแสดงข้อมูลผู้แข่งขันได้ได้เนื่องจาก\n$e";
	echo $err->getIfNotNull();
	unset($err);
}
unset($stm);
?></div><? if($sess->sTeamInfo>0): /*IF Editable*/ ?>
     <div id="tab-0">
     <?=$sess->showState(CheckSession::SECT_INFO_TEAM)?>
     <?=$sess->teamMessage($db,CheckSession::SECT_INFO_TEAM)?>
       <form action="team_info.php" method="post" name="team" id="team">
         <fieldset>
           <legend>แก้ไขรายละเอียดทีม</legend>
           <div>
             <label for="team_name">ชื่อทีม</label>
             <input name="team_name" type="text" id="team_name" value="<?=@$teamInfo['team_name']?>" data-validation="length" data-validation-length="1-100" required>
           </div>
           <div>
             <label for="t_firstname">ชื่อครูที่ปรึกษา</label>
             <input name="t_firstname" type="text" id="t_firstname" value="<?=@$teamInfo['t_firstname']?>" data-validation="length" data-validation-length="min1" required>
           </div>
           <div>
             <label for="t_lastname">นามสกุลครูที่ปรึกษา</label>
             <input name="t_lastname" type="text" id="t_lastname" value="<?=@$teamInfo['t_lastname']?>" data-validation="length" data-validation-length="min1" required>
           </div>
           <div>
             <label for="t_phone">เบอร์โทรศัพท์ครูที่ปรึกษา</label>
             <input name="t_phone" type="text" id="t_phone" value="<?=@$teamInfo['t_phone']?>" data-validation="length number" data-validation-length="9-10" required>
           </div><div class="btnset">
             <input type="submit" name="submit" id="submit" value="บันทึก">
             <input type="reset" name="reset" id="reset" value="ยกเลิก"><a href="change_password.php" title="เปลี่ยนรหัสผ่าน" target="_blank">เปลี่ยนรหัสผ่าน</a>
           </div>
         </fieldset>
       </form>
     </div>
<?  endif; /*ENDIF Editable*/
if($sess->tabsArrangeForTeamInfo()!=-1)
for($i=0;$i<=count($student) && $i<3;$i++):
if($sess->sStdInfo[$i]<CheckSession::STATE_LOCKED) continue;?>
     <div id="tab-<?=($i+1)?>">
     <?=$sess->showState(CheckSession::SECT_INFO_STD_1+$i,($i<count($student))? false:1)?>
     <?=$sess->teamMessage($db,CheckSession::SECT_INFO_STD_1+$i)?>
      <form name="form1" method="post" action="team_info.php">
        <fieldset>
          <legend>แก้ไขรายละอียดผู้แข่งขันคนที่ <?=($i+1)?></legend>
      <div>
        <label for="title">คำนำหน้าชื่อ</label>
        <input name="title" type="text" id="title_" value="<?=@$student[$i]['title']?>" size="20" data-validation="length" data-validation-length="1-20"  data-suggestions="นาย, นางสาว, นาง, สามเณร, เด็กหญิง, เด็กชาย, หม่อมหลวง, หม่อมราชวงศ์, หม่อมเจ้า">
      </div>
      <div>
        <label for="firstname">ชื่อ</label>
        <input name="firstname" type="text"  required id="firstname" value="<?=@$student[$i]['firstname']?>" data-validation="length" data-validation-length="min1">
      </div>
      <div>
        <label for="lastname">นามสกุล</label>
        <input name="lastname" type="text"  required id="lastname" value="<?=@$student[$i]['lastname']?>" data-validation="length" data-validation-length="min1">
      </div>
      <fieldset><legend>เพศ</legend>
          <input type="radio" name="gender" value="1" id="gender_0" <?=(1==@$student[$i]['gender'])?'checked':''?> required>
          <label for="gender_0">ชาย</label>
        <br>
          <input type="radio" name="gender" value="0" id="gender_1" <?=(0==@$student[$i]['gender'])?'checked':''?> required>
          <label for="gender_1">หญิง</label>
      </fieldset>
      <div>
        <label for="phone">เบอร์โทรศัพท์</label>
        <input name="phone" type="text" required id="phone" value="<?=@$student[$i]['phone']?>" data-validation="length number" data-validation-length="9-10">
      </div>
      <div>
        <label for="email">Email</label>
        <input name="email" type="email" id="email" value="<?=@$student[$i]['email']?>" data-validation="length email" data-validation-length="min1" required>
      </div>
      <div>
        <label for="school">โรงเรียน</label>
<? if($sess->type==1 || $i==0): /*Indy team or First student of school team*/ ?><input name="school" type="text" required id="school" value="<?=@$student[$i]['school']?>" data-validation="length" data-validation-length="min1">
<? elseif($i>=count($student)): /*Add new student for school team*/ ?><input name="school" type="hidden" id="school" value="<?=$student[0]['school']?>">
<? endif; if(!($sess->type==1 || $i==0)) echo $student[0]['school']; /*other student of school team*/ ?></div>
      <div>
        <label for="sci_grade">เกรดเฉลี่ยวิชาวิทยาศาสตร์ภาคเรียนล่าสุด</label>
        <input name="sci_grade" type="text" required id="sci_grade" value="<?=@$student[$i]['sci_grade']?>" size="8"  data-validation="number"  data-validation-allowing="range[0;4], float">
      </div>
      <div class="btnset">
        <input name="i" type="hidden" id="i" value="<?=$i?>">
        <input name="id" type="hidden" id="id" value="<?=@$student[$i]['id']?>">
        <input type="submit" name="submit2" id="submit2" value="บันทึก">
        <input type="reset" name="reset2" id="reset2" value="ยกเลิก">
      </div>
        </fieldset>
      </form>
    </div><? endfor;?>
 </div><?
if(isset($_GET['ajax'])):
	require_once 'json_ajax.php';
	$json=new jsonAjax();
	$json->setResult($result);
	if($result->result) $json->addHtmlTextVal(jsonAjax::SET_HTML,"#tab",ob_get_clean());
	else ob_end_clean();
	echo $json;
else: ?> </div> <?=$result->getIfNotNull()?>
<!-- InstanceEndEditable --></div>
</div>
<div class="sidemenu">
<div class="ui-widget-header center ui-corner-top">ข้อมูลเบื้องต้น</div>
<div class="ui-widget-content">
<div class="center bold">ทีม <?=htmlspecialchars($sess->teamName())?></div>
<div class="center bold">ความคืบหน้า</div>
<div id="progressbox" class="ui-corner-all ui-widget-content"><div id="progressbar" class="center ui-widget-header ui-corner-all"><?=$sess->progression();?>%</div></div>
</div>
  <p class="ui-widget-header ui-corner-top center">ขั้นตอนการรับสมัคร</p>
  <div class="menubox countMenu ui-widget-content">
  <div class="<?=$sess->menuClass(CheckSession::PAGE_TEAM_INFO)?>"><a href="team_info.php" title="กรอกข้อมูลผู้แข่งขัน">กรอกข้อมูลผู้แข่งขัน</a></div>
  <div class="<?=$sess->menuClass(CheckSession::PAGE_UPLOAD_TSP)?>"><a href="upload_photo.php" title="Upload ปพ.1">Upload ปพ.1</a></div>
<div class="<?=$sess->menuClass(CheckSession::PAGE_QUIZ)?>"><a href="../login/coach.php" title="ทำข้อสอบรอบพิเศษ">ทำข้อสอบรอบพิเศษ</a></div>
  <div class="<?=$sess->menuClass(CheckSession::PAGE_CONFIRM)?>"><a href="confirm.php" title="ยืนยันข้อมูล">ยืนยันข้อมูล</a></div>
  <div class="<?=$sess->menuClass(CheckSession::PAGE_PAY)?>"><a href="payment.php" title="Upload หลักฐานการโอนเงิน">ส่งหลักฐานการชำระเงิน</a></div>
  <div class="<?=$sess->menuClass(CheckSession::PAGE_RECEIVE_ID)?>"><a href="receive_id.php" title="พิมพ์บัตรประจำตัวผู้แข่งขัน">พิมพ์บัตรประจำตัวผู้แข่งขัน</a></div>
  </div>
  <p class="ui-widget-header ui-corner-top center">หน้าอื่นๆ</p>
  <div class="menubox ui-widget-content">
  <div>&nbsp;&nbsp;<a href="../reg/index.php" title="main">หน้าหลัก</a></div>
  <div>&nbsp;&nbsp;<a href="../mahidol_quiz.html" title="main" target="_blank">รายละเอียดการแข่งขัน</a></div>
  <div>&nbsp;&nbsp;<a href="../index.html" title="main" target="_blank">หน้าหลักเว็บมหิดล</a></div>
  <div>&nbsp;&nbsp;<a href="search.php" title="ค้นหา" target="_blank">ค้นหา</a></div>
  <div>&nbsp;&nbsp;<a href="change_password.php" title="เปลี่ยน password">เปลี่ยน password</a></div>
  <div>&nbsp;&nbsp;<a href="../reg/logout.php" title="log out">log out</a></div>
  </div>
</div></div>
<div id="footer"></div>
</body>
<!-- InstanceEnd --></html><? endif  ?>
