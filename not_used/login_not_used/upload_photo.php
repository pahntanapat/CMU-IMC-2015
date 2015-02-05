<?php
session_start();
require_once '../config.inc.php';
require_once "check_session.php";
$sess=checkSession::mustLogIn();

ob_start();
require_once 'main_function.php';
require_once 'result_box.php';
require_once 'upload_img_excp.php';
$db=newPDO();
$result=new resultCon();
if(isset($_GET['ajax'])){
	require_once 'json_ajax.php';
	$ajax=new jsonAjax();
}
if(count($_POST)<=0 || count($_FILES)<=0)
	$result->result=NULL;
elseif($sess->sStdDoc[$_POST['i']]<=CheckSession::STATE_LOCKED ||
	($sess->sStdDoc[$_POST['i']]==CheckSession::STATE_NOT_FINISHED &&
	$_END_EDIT_INFO<time())){
	echo "ไม่อนุญาตให้อัพโหลดใบปพ.1";
	$result->result=false;
}else{
	try{
		$db->beginTransaction();
		if($_FILES['tsp']['error']!=UPLOAD_ERR_OK)
			throw new UploadImageException('',$_FILES['tsp']['error'],UploadImageException::TYPE_UPLOAD_ERROR);
		$file=imgTSP($sess->ID,$_POST['id']);
		$move=false;
		switch($_FILES['tsp']['type']){
			case 'image/pjpeg':
			case 'image/jpeg':
				$img=imagecreatefromjpeg($_FILES['tsp']['tmp_name']);
				$move=($_FILES['tsp']['size']<=184320);
				break;
			case 'image/gif':
				$img=imagecreatefromgif($_FILES['tsp']['tmp_name']);break;
			case 'image/png':
				$img=imagecreatefrompng($_FILES['tsp']['tmp_name']);break;
			default:
				throw new UploadImageException(
					$_FILES['tsp']['name'].' ('.$_FILES['tsp']['type'].') ',
					UploadImageException::CODE_UNSUPPORT_EXT,
					UploadImageException::TYPE_IMG_ERROR);
		}
		if($img===false) throw new UploadImageException(
					$_FILES['tsp']['name'].' ('.$_FILES['tsp']['type'].') ',
					UploadImageException::CODE_WRONG_FORMAT,
					UploadImageException::TYPE_IMG_ERROR);
		elseif($move){
			if(!move_uploaded_file($_FILES['tsp']['tmp_name'],$_ROOT.$file))
				throw new UploadImageException(
					$file.', moved from '.$_FILES['tsp']['name'].' ('.$_FILES['tsp']['type'].') ',
					UploadImageException::CODE_UNWRITTABLE,
					UploadImageException::TYPE_IMG_ERROR);
			else echo "Upload complete! Image was moved.\n";
		}else{//resize
			$size=array(1754,1240); // min size A4 150 dpi
			$imgW=imagesx($img);$imgH=imagesy($img);
			if($imgH>$imgW){
				$h=max($size);
				$w=min($size);
			}else{
				$h=min($size);
				$w=max($size);
			}
			
			if($h>=$imgH || $w>=$imgW){ //SIZE <= 180 kB
				$resize=$img; // If it
			}else{
				if($imgH>$imgW) //Algorithm = H, W >= $h, $w if want H, W <= $h, $w change >/<
					$h=round($w*$imgH/$imgW);//round($w*$imgH/$imgW,0,PHP_ROUND_HALF_EVEN);
				else
					$w=round($h*$imgW/$imgH);//round($h*$imgW/$imgH,0,PHP_ROUND_HALF_EVEN);
				$resize=imagecreatetruecolor($w,$h);
				imagecopyresampled($resize,$img,0,0,0,0,$w,$h,$imgW,$imgH);
			}
			if(!imagejpeg($resize,$_ROOT.$file,25)){
				throw new UploadImageException(
					$file.', the resized resoure of '.$_FILES['tsp']['name'].' ('.$_FILES['tsp']['type'].')',
					UploadImageException::CODE_UNWRITTABLE,
					UploadImageException::TYPE_IMG_ERROR);
			}else{
				echo "Upload complete! Image was compressed.\n";
			}
			imagedestroy($img);
			unset($h,$w,$img,$imgH,$imgW,$resize,$size);
		}
		unset($move);
		//Save to DB
		$stm=$db->prepare('UPDATE participant_info SET is_upload=:u WHERE id=:id AND team_id=:tid AND is_upload>:l');
		$stm->bindParam(':id',$_POST['id']);
		$stm->bindParam(':tid',$sess->ID);
		$stm->bindValue(':u',CheckSession::STATE_EDITTABLE,PDO::PARAM_INT);
		$stm->bindValue(':l',CheckSession::STATE_LOCKED,PDO::PARAM_INT);
		if(!$stm->execute()) throw new Exception("ไม่สามารถบันทึกข้อมูลได้");
		echo "บันทึกข้อมูลสำเร็จ";
		if(isset($_GET['ajax'])){
			$html=<<<HTML
<a href="$file" title="ดู" target="_blank">ดูใบปพ.1</a>
 <a href="#suggest" title="ลบ" class="delete">คำแนะนำสำหรับการ scan และอัพโหลดไฟล์</a>
HTML;
			$ajax->addHtmlTextVal(jsonAjax::SET_HTML,'#command-'.$_POST['i'],$html);
			$html="อัพโหลดแล้ว";
			$ajax->addHtmlTextVal(jsonAjax::SET_HTML,'#td-'.$_POST['i'],$html);
			$html=<<<HTML
	$("a[id|='link']").button();
	$("p[id|='command']").buttonset();
HTML;
			$ajax->addAction(jsonAjax::EVALUTE,$html);
			unset($html);
		}
		$result->result=true;
		$_SESSION=$sess->updateData($db,$_SESSION,0);
		$db->commit();
	}catch(Exception $e){
		$db->rollBack();
		echo "ไม่สามารถอัพโหลดรูปใบปพ.1 ของผู้สมัครได้\n";
		echo errMsg($e);
		$result->result=false;
	}
}

$_SESSION=$sess->updateData($db,$_SESSION);
$result->addIfisStr(nl2br(ob_get_clean()));

if(isset($_GET['ajax'])):
$ajax->setResult($result);
$ajax->addAction(jsonAjax::SCROLL_TO,"#suggest");
if($result->result==false)
	$ajax->removeAction();
echo $ajax;
else:
?>
<!doctype html>
<html><!-- InstanceBegin template="/Templates/mahidol_login.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta charset="utf-8">
<!-- InstanceBeginEditable name="doctitle" -->
<title>Upload ใบปพ.1 - Mahidol Quiz 2014: การแข่งขันตอบปัญหาวิทยาศาสตร์สุขภาพ ชิงถ้วยสมเด็จพระเทพรัตนราชสุดา สยามบรมราชกุมารี เนื่องในวันมหิดล ประจำปี พ.ศ. 2557</title>
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
<script src="js/jquery.form.min.js"></script>
<script src="js/upload_transcript.js"></script>
<!-- InstanceEndEditable -->

</head>

<body>
<div id="header"></div>
<div id="menu"></div>
<div id="content">
<div class="content">
<div class="heading">
  <div><!-- InstanceBeginEditable name="headline" -->Upload ปพ.1<img src="../../images/download.png" width="60" height="60" alt="Log in ผู้แข่งขัน"><!-- InstanceEndEditable --></div></div>
<div class="main_content"><!-- InstanceBeginEditable name="main_content" -->
  <div id="tab">
    <div id="tabs">
      <ul>
<?php
$stm=$db->prepare('SELECT id,CONCAT_WS(:sp, title, firstname, lastname) AS name, school, sci_grade FROM participant_info WHERE team_id=:id ORDER BY id');
$stm->execute(array(':id'=>$sess->ID,':sp'=>' '));
$std=$stm->fetchAll(PDO::FETCH_ASSOC);
	foreach($sess->sStdDoc as $i=>$v)
		if($v==1){
?>
        <li><a href="#tab-<?=$i?>" title="ใบปพ.1 ผู้สมัครคนที่ <?=1+$i?>">ใบปพ.1 ผู้สมัครคนที่ <?=1+$i?></a></li>
<? } ?>
        <li><a href="#tab-c" title="ข้อมูลสรุป">ข้อมูลสรุป</a></li>
<?
	foreach($sess->sStdDoc as $i=>$v){
?>
        <li><a href="#tab-<?=$i?>" title="ใบปพ.1 ผู้สมัครคนที่ <?=1+$i?>">ใบปพ.1 ผู้สมัครคนที่ <?=1+$i?></a></li>
<? } ?>
      </ul>
      <div id="tab-c">
      <h2>ข้อมูลสรุป</h2>
<?php
echo ($sess->showState(CheckSession::PAGE_UPLOAD_TSP)).
	($sess->teamMessage($db,CheckSession::PAGE_UPLOAD_TSP,true));
foreach($std as $i=>$v){ ?>
      <h3>ผู้แข่งขันคนที่ <?=1+$i?></h3>
      <?=$sess->showState(CheckSession::SECT_TSP_STD_1+$i)?>
      <table border="0">
        <tr>
          <th scope="row">ชื่อ นามสกุล</th>
          <td><?=$v['name']?></td>
        </tr>
        <tr>
          <th scope="row">โรงเรียน</th>
          <td><?=$v['school']?></td>
        </tr>
        <tr>
          <th scope="row">เกรดเฉลี่ยวิชาวิทยาศาสตร์ภาคเรียนล่าสุด</th>
          <td><?=$v['sci_grade']?></td>
        </tr>
        <tr>
          <th scope="row">ใบ ปพ. 1</th>
          <td id="td-<?=$i?>"><? if($sess->sStdDoc[$i]>1): ?>อัพโหลดแล้ว<? else: ?> ยังไม่ได้อัพโหลด<? endif ?></td>
        </tr>
      </table>
<? } ?>
      </div>
<? foreach($std as $i=>$v){ ?>
      <div id="tab-<?=$i?>">
<h2>ผู้แข่งขันคนที่ <?=1+$i?></h2>
<?
echo $sess->teamMessage($db,CheckSession::SECT_TSP_STD_1+$i);
echo $sess->showState(CheckSession::SECT_INFO_STD_1+$i);
?>
<h3>ข้อมูลทั่วไป </h3>
<table border="0">
  <tr>
          <th scope="row">ชื่อ นามสกุล</th>
          <td><?=$v['name']?></td>
        </tr>
        <tr>
          <th scope="row">โรงเรียน</th>
          <td><?=$v['school']?></td>
        </tr>
        <tr>
          <th scope="row">เกรดเฉลี่ยวิชาวิทยาศาสตร์ภาคเรียนล่าสุด</th>
          <td><?=$v['sci_grade']?></td>
        </tr>
        </table>
      <h3>ใบ ปพ.1</h3>
<?=$sess->showState(CheckSession::SECT_TSP_STD_1+$i)?>
      <p id="command-<?=$i?>"><?
	  $file=imgTSP($sess->ID,$v['id']);
	  if(($sess->sStdDoc[$i]>CheckSession::STATE_NOT_FINISHED || $sess->sStdDoc[$i]==CheckSession::STATE_WAIT) && is_file($_ROOT.$file)): ?><a href="<?=$file?>" title="ดู" target="_blank">ดูใบปพ.1</a><? else: ?><a href="#tab-<?=$i?>">ยังไม่ได้อัพโหลด หรือไม่พบไฟล์</a>
        <? endif ?> <a href="#suggest" title="ลบ" class="delete">คำแนะนำสำหรับการ scan และอัพโหลดไฟล์</a></p><? if($sess->sStdDoc[$i]>0): ?>
      <form action="upload_photo.php" method="post" enctype="multipart/form-data" name="form1">
        <fieldset>
          <legend>อัพโหลดใบ ปพ. 1</legend>
          <div>
            <label for="tsp">ใบ ปพ.1</label>
            <input type="file" name="tsp" id="tsp" data-validation="mime size required" data-validation-allowing="jpg, png, gif" data-validation-max-size="2M" accept="image/jpeg,image/gif,image/x-png" required>
          </div>
          <div class="btnset">
            <input name="i" type="hidden" id="i" value="<?=$i?>">
            <input name="id" type="hidden" id="id" value="<?=$v['id']?>">
            <input type="submit" name="submit" id="submit" value="Upload">
            <input type="reset" name="reset" id="reset" value="Cancel">
          </div>
        </fieldset>
    </form>
<? endif;
if(@$_POST['id']==$v['id']) echo $result->getIfNotNull();
?>
      </div>
<? } ?>
    </div>
  </div>
 <div class="ui-widget" id="suggest">
	<div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;">
		<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
		<strong>คำแนะนำสำหรับการอัพโหลดไฟล์</strong><br>
		</p>
		<ol>
		  <li>ความละเอียดการ scan ภาพควรเป็น 150 dpi  ขึ้นไป (แนะนำ: 150 dpi)</li>
		  <li>ประเภทไฟล์ JPEG, PNG, GIF (แนะนำ: JPEG)</li>
		  <li>ขนาดไฟล์ ไม่เกิน 2 MB (แนะนำ: ควรต่ำว่า 500 KB)</li>
		  <li>ภาพสี, เทา (Grayscale), หรือขาวดำ ก็ได้ (แนะนำ: ภาพสี)</li>
	    </ol>
	  </div>
</div>
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