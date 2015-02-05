<?php
session_start();
require_once "check_session.php";
$sess=checkSession::mustLogIn();
if($sess->type==0){
	if(isset($_GET['ajax'])){
		header('Content-type: application/json');
		$res=new jsonAjax();
		$res->addAction(jsonAjax::REDIRECT,"./");
		exit($res->__toString());
	}else{
		header("Location: ./");
		exit("You are not allowed to be here.");
	}
}
ob_start();
require_once 'main_function.php';
require_once 'result_box.php';
$db=newPDO();
$result=new resultCon();
if(!isset($_GET['act']))
	$result->result=NULL;
else switch($_GET['act']){
	case 'start':
		try{
			$result->result=false;
			if(count($_POST['ch'])<4){
				echo "ยังไม่เริ่มทำแบบทดสอบ";
				break;
			}
			$db->beginTransaction();
			$stm=$db->prepare('SELECT COUNT(*) FROM coach_info WHERE team_id=:tid');
			$stm->execute(array(':tid'=>$sess->ID));
			if($stm->fetchColumn()>0){
				echo "คุณได้เริ่มทำแบบทดสอบไปแล้ว";
				break;
			}
			$stm=$db->prepare('INSERT INTO coach_info (team_id, state,start_time) VALUES (:id,:st,NOW())');
			$stm->bindParam(':id',$sess->ID);
			$stm->bindValue(':st',CheckSession::STATE_NOT_FINISHED,PDO::PARAM_INT);
			$result->result=$stm->execute();
			$_SESSION['quiz_id']=$db->lastInsertId();
			$db->commit();
		}catch(Exception $e){
			$db->rollBack();
			echo "ไม่สามารถ load คำถามได้ เนื่องจาก ".$e;
			$result->result=false;
		}
		unset($stm);
		$_SESSION=$sess->updateData($db,$_SESSION,0);
		break;
	case 'stop':
		try{
			$db->beginTransaction();
			$stm=$db->prepare('UPDATE coach_info SET used_time=:ut/1000, answer=:ans, state=:wait WHERE id=:id AND team_id=:tid AND state>:lock');
			$stm->bindParam(':ut',$_POST['used_time'],PDO::PARAM_INT);
			$stm->bindParam(':ans',$_POST['answer']);
			$stm->bindParam(':tid',$sess->ID);
			$stm->bindParam(':id',$_SESSION['quiz_id']);
			$stm->bindValue(':wait',CheckSession::STATE_WAIT,PDO::PARAM_INT);
			$stm->bindValue(':lock',CheckSession::STATE_LOCKED,PDO::PARAM_INT);
			$result->result=$stm->execute();
			echo "บันทึกคำตอบเรียบร้อยแล้ว";
			unset($i);
			$db->commit();
		}catch(Exception $e){
			$db->rollBack();
			echo "ไม่สามารถบันทึกคำตอบได้ กรุณาติดต่อกรรมการการแข่งขันด่วน\n$e";
		}
		unset($stm);
		break;
	default:
		if(!isset($_GET['ajax'])) break;
		elseif($_POST['used_time']=='') break;
		try{
			$db->beginTransaction();
			$stm=$db->prepare('UPDATE coach_info SET used_time=:ut/1000, answer=:ans, state=:ed WHERE id=:id AND team_id=:tid AND state>:lock;');
			$stm->bindParam(':ut',$_POST['used_time'],PDO::PARAM_INT);
			$stm->bindValue(':lock',CheckSession::STATE_LOCKED,PDO::PARAM_INT);
			$stm->bindValue(':ed',CheckSession::STATE_EDITTABLE,PDO::PARAM_INT);
			$stm->bindValue(':ans',"##This is auto-saved answer.##\r\n".$_POST['answer']);
			$stm->bindParam(':tid',$sess->ID);
			$stm->bindParam(':id',$_SESSION['quiz_id']);
			$result->result=$stm->execute();
			echo "ติดต่อ hosting server สำเร็จ @".date("Y-m-d H:i:s");
			$db->commit();
		}catch(Exception $e){
			$db->rollBack();
			echo "Server error: กรุณาติดต่อกรรมการการแข่งขัน $e";
			$result->result=false;
		}
		unset($stm);
}
$_SESSION=$sess->updateData($db,$_SESSION,0);
$result->addIfisStr(nl2br(ob_get_clean()));

if(isset($_GET['ajax'])):
	echo $result->message;
else:
?>
<!doctype html>
<html><!-- InstanceBegin template="/Templates/mahidol_login.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta charset="utf-8">
<!-- InstanceBeginEditable name="doctitle" -->
<title>ทำแบบทดสอบ - Mahidol Quiz 2014: การแข่งขันตอบปัญหาวิทยาศาสตร์สุขภาพ ชิงถ้วยสมเด็จพระเทพรัตนราชสุดา สยามบรมราชกุมารี เนื่องในวันมหิดล ประจำปี พ.ศ. 2557</title>
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
<script src="js/quiz.js"></script>
<!-- InstanceEndEditable -->

</head>

<body>
<div id="header"></div>
<div id="menu"></div>
<div id="content">
<div class="content">
<div class="heading">
  <div><!-- InstanceBeginEditable name="headline" -->ทำแบบทดสอบ<img src="../../images/signin.png" width="60" height="60" alt="Log in ผู้แข่งขัน"><!-- InstanceEndEditable --></div></div>
<div class="main_content"><!-- InstanceBeginEditable name="main_content" -->
<?php
echo $sess->showState(CheckSession::PAGE_QUIZ);
if($sess->sQuiz==CheckSession::STATE_LOCKED || ($sess->sQuiz==CheckSession::STATE_NOT_FINISHED && @$_GET['act']!='start')):
	echo $sess->teamMessage($db,CheckSession::PAGE_QUIZ);
?>
  <form action="coach_info.php?act=start" method="post" name="startForm" id="startForm">
    <fieldset>
      <legend>ก่อนเริ่มทำแบบทดสอบ</legend>
      <div>
          <input type="checkbox" name="ch[]" value="1" id="ch_0">
          <label for="ch_0">ฉันทราบแล้วว่า ให้เวลาทำแบบทดสอบ 20 นาที</label> <br>
        
          <input type="checkbox" name="ch[]" value="1" id="ch_1">
           <label for="ch_1">ฉันทราบแล้วว่า เมื่อเริ่มแบบทดสอบแล้วไม่สามารถหยุดได้</label>
        <br>
        
          <input type="checkbox" name="ch[]" value="1" id="ch_2">
          <label for="ch_2"> ฉันได้ใช้ <a href="http://jquery.com/browser-support/" title="ตรวจสอบ web browser" target="_blank">web browser ที่รองรับ HTML 5 และ JavaScript</a></label>
        <br>
        
          <input type="checkbox" name="ch[]" value="1" id="ch_3">
          <label for="ch_3"> ฉันพร้อมทำแบบทดสอบแล้ว</label>
        </div>
      <div class="btnset">
        <input type="submit" name="start" id="start" value="เริ่มทำแบบทดสอบ!" disabled>
      </div>
    </fieldset>
  </form>
<? elseif($sess->sQuiz>0 && $result->result==true && $_END_EDIT_INFO>time()): ?>
  <form action="coach_info.php?act=stop" method="post" name="quizForm" id="quizForm">
    <div class="right"><span>กำลังจะเริ่มในอีก</span> <span id="remain" class="bold">1.234567890×10<sup>10000000</sup></span> s</div>
<input name="used_time" type="hidden" id="used_time" value="0">
<div id="connection" class="right"></div>
    <p class="bold">ในความคิดของน้องคิดว่าปัจจุบันนี้โรค หรือความผิดปกติใดที่กำลังเป็นปัญหาหนักที่สุดสำหรับประเทศไทยเรา แล้วในฐานะที่น้องเป็นเยาวชนคนรุ่นใหม่ น้องมีแนวทาง หรือวิธีการใดที่เป็นไปได้ เพื่อที่จะลด หรือช่วยให้สิ่งเหล่านี้ดีขึ้นกว่าปัจจุบัน ?    </p>
    <div>
      <textarea name="answer" cols="90%" rows="20" autofocus readonly id="answer" placeholder="คำตอบ"></textarea>
  </div>
    <div class="btnset">
      <input type="submit" name="send" id="send" value="ส่งคำตอบ" disabled>
    </div>
  </form>
<? elseif($_END_EDIT_INFO<=time()): ?>
	<p>หมดเขตตอบแบบทดสอบแล้ว</p>
<? else: ?>
  <p>คุณได้ส่งคำตอบแล้ว</p>
  <p>กรรมการจะตรวจคำตอบและแจ้งทีมที่ผ่านเข้ารอบในภายหลัง</p>
<?php endif;
echo $result->getIfNotNull();
?>
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
<div class="<?=$sess->menuClass(CheckSession::PAGE_QUIZ)?>"><a href="coach_info.php" title="ทำข้อสอบรอบพิเศษ">ทำข้อสอบรอบพิเศษ</a></div>
  <div class="<?=$sess->menuClass(CheckSession::PAGE_CONFIRM)?>"><a href="confirm.php" title="ยืนยันข้อมูล">ยืนยันข้อมูล</a></div>
  <div class="<?=$sess->menuClass(CheckSession::PAGE_PAY)?>"><a href="payment.php" title="Upload หลักฐานการโอนเงิน">ส่งหลักฐานการชำระเงิน</a></div>
  <div class="<?=$sess->menuClass(CheckSession::PAGE_RECEIVE_ID)?>"><a href="receive_id.php" title="พิมพ์บัตรประจำตัวผู้แข่งขัน">พิมพ์บัตรประจำตัวผู้แข่งขัน</a></div>
  </div>
  <p class="ui-widget-header ui-corner-top center">หน้าอื่นๆ</p>
  <div class="menubox ui-widget-content">
  <div>&nbsp;&nbsp;<a href="../../reg/index.php" title="main">หน้าหลัก</a></div>
  <div>&nbsp;&nbsp;<a href="../../mahidol_quiz.html" title="main" target="_blank">รายละเอียดการแข่งขัน</a></div>
  <div>&nbsp;&nbsp;<a href="../../index.html" title="main" target="_blank">หน้าหลักเว็บมหิดล</a></div>
  <div>&nbsp;&nbsp;<a href="search.php" title="ค้นหา" target="_blank">ค้นหา</a></div>
  <div>&nbsp;&nbsp;<a href="change_password.php" title="เปลี่ยน password">เปลี่ยน password</a></div>
  <div>&nbsp;&nbsp;<a href="../../reg/logout.php" title="log out">log out</a></div>
  </div>
</div></div>
<div id="footer"></div>
</body>
<!-- InstanceEnd --></html><? endif ?>