<?php
session_start();
require_once '../config.inc.php';
require_once "check_session.php";
$sess=checkSession::mustLogIn();

ob_start();
require_once 'result_box.php';
require_once 'upload_img_excp.php';
$db=newPDO();
$result=new resultCon(false);
if(count($_POST)+count($_FILES)<=0)
	$result->result=NULL;
elseif($sess->sTeamPay<=CheckSession::STATE_LOCKED || $_END_PAY<=time() || $_START_PAY>time())
	echo "ไม่อนุญาตให้อัพโหลด กรุณาอัพโหลดในช่วง ".date('Y-m-d H:i:s',$_START_PAY)." ถึง ".date('Y-m-d H:i:s',$_END_PAY);
else
	try{
		$db->beginTransaction();
		if($_FILES['pay']['error']!=UPLOAD_ERR_OK)
			  throw new UploadImageException('',$_FILES['pay']['error'],UploadImageException::TYPE_UPLOAD_ERROR);
		$file=imgPay($sess->ID,$sess->teamName(),$sess->type);
		$move=false;
		switch($_FILES['pay']['type']){
			case 'image/pjpeg':
			case 'image/jpeg':
				$img=imagecreatefromjpeg($_FILES['pay']['tmp_name']);
				$move=($_FILES['pay']['size']<=10240);
				break;
			case 'image/gif':
				$img=imagecreatefromgif($_FILES['pay']['tmp_name']);break;
			case 'image/png':
				$img=imagecreatefrompng($_FILES['pay']['tmp_name']);break;
			default:
				throw new UploadImageException(
					$_FILES['pay']['name'].' ('.$_FILES['pay']['type'].') ',
					UploadImageException::CODE_UNSUPPORT_EXT,
					UploadImageException::TYPE_IMG_ERROR);
		}
		if($img===false) throw new UploadImageException(
				$_FILES['pay']['name'].' ('.$_FILES['pay']['type'].') ',
				UploadImageException::CODE_WRONG_FORMAT,
				UploadImageException::TYPE_IMG_ERROR);
		elseif($move){
			if(!move_uploaded_file($_FILES['pay']['tmp_name'],$_ROOT.$file))
				throw new UploadImageException(
					$file.', moved from '.$_FILES['pay']['name'].' ('.$_FILES['pay']['type'].') ',
					UploadImageException::CODE_UNWRITTABLE,
					UploadImageException::TYPE_IMG_ERROR);
			else echo "Upload complete! Image was moved.\n";
	  }else{//resize
		$size=array(1850,850); // min size letter No.9 9.25*4.25 300 dpi =2775,1275, 200 dpi=1850,850
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
		if(!imagejpeg($resize,$_ROOT.$file,60)){
			throw new UploadImageException(
				$file.', the resized resoure of '.$_FILES['pay']['name'].' ('.$_FILES['pay']['type'].')',
				UploadImageException::CODE_UNWRITTABLE,
				UploadImageException::TYPE_IMG_ERROR);
		}else{
			echo "Upload complete! Image was compressed.\n";
		}
		imagedestroy($img);
		unset($h,$w,$img,$imgH,$imgW,$resize,$size);
	  }
		
		$stm=$db->prepare('UPDATE team_info SET is_pay=:wait WHERE id=:id AND is_pay>:lock');
		$stm->bindValue(':id',$sess->ID);
		$stm->bindValue(':wait',CheckSession::STATE_WAIT,PDO::PARAM_INT);
		$stm->bindValue(':lock',CheckSession::STATE_LOCKED,PDO::PARAM_INT);
		$result->result=$stm->execute();
		$_SESSION=$sess->updateData($db,$_SESSION,0);
		unset($stm,$move);
		$db->commit();
	}catch(Exception $e){
		$db->rollBack();
		echo "ไม่สามารถอัพโหลดไฟล์ได้\n".errMsg($e);
	}
$_SESSION=$sess->updateData($db,$_SESSION);
$result->addIfisStr(nl2br(ob_get_clean()));

if(isset($_GET['ajax'])):
	require_once 'json_ajax.php';
	$json=new jsonAjax();
	$json->setResult($result);
	if($result->result){
		$json->addHtmlTextVal(jsonAjax::SET_TEXT,"form",'');
		$html=<<<HTM
<a href="$file" title="ดูหลักฐานที่อัพโหลด" target="_blank">ดูหลักฐานที่อัพโหลด</a>
HTM;
		$json->addHtmlTextVal(jsonAjax::SET_HTML,'p[id|=command]',$html);
		$html=<<<JS
$("p[id|='command']").buttonset();
JS;
		$json->addAction(jsonAjax::EVALUTE,$html);
		unset($html);
	}
	echo $json;
else:
?>
<!doctype html>
<html><!-- InstanceBegin template="/Templates/mahidol_login.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta charset="utf-8">
<!-- InstanceBeginEditable name="doctitle" -->
<title>Upload หลักฐานการโอนเงิน - Mahidol Quiz 2014: การแข่งขันตอบปัญหาวิทยาศาสตร์สุขภาพ ชิงถ้วยสมเด็จพระเทพรัตนราชสุดา สยามบรมราชกุมารี เนื่องในวันมหิดล ประจำปี พ.ศ. 2557</title>
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
  <div><!-- InstanceBeginEditable name="headline" -->Upload หลักฐานการโอนเงิน<img src="../../images/document.png" width="60" height="60" alt="Log in ผู้แข่งขัน"><!-- InstanceEndEditable --></div></div>
<div class="main_content"><!-- InstanceBeginEditable name="main_content" -->
  <h3>ตัวอย่างหลักฐานการโอนเงินที่ถูกต้อง</h3>
  <p>&nbsp;</p>
  <h3>อัพโหลดหลักฐานการโอนเงิน</h3>
<? echo $sess->showState(CheckSession::PAGE_PAY).$sess->teamMessage($db,CheckSession::PAGE_PAY,true);?>
  <p id="command"><?
$file=imgPay($sess->ID,$sess->teamName(),$sess->type);
if(is_file($_ROOT.$file)):
 ?><a href="<?=$file?>" title="ดูหลักฐานที่อัพโหลด" target="_blank">ดูหลักฐานที่อัพโหลด</a><? else: ?>
    <a href="#payForm" title="ไม่พบไฟล์">ไม่พบไฟล์</a>
    <? endif; ?></p><? if($sess->sTeamPay>CheckSession::STATE_LOCKED): ?>
  <form action="payment.php" method="post" enctype="multipart/form-data" name="payForm" id="payForm">
    <fieldset>
      <legend>อัพโหลดหลักฐานการโอนเงิน</legend>
      <div>
        <label for="pay">หลักฐานการโอนเงิน</label>
        <input type="file" name="pay" id="pay" data-validation="mime size required" data-validation-allowing="jpg, png, gif" data-validation-max-size="2M" accept="image/jpeg,image/gif,image/x-png" required>
      </div>
      <div class="btnset">
        <input type="submit" name="submit" id="submit" value="Submit">
        <input type="reset" name="cancel" id="cancel" value="Cancel">
      </div>
    </fieldset>
  </form><? endif;?>
  <div class="ui-widget">
    <div class="ui-state-error ui-corner-all" style="padding: 0 .7em;">
      <p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span> <strong>อ่านก่อนอัพโหลด:</strong> </p>
      <ol>
        <li>เมื่อกด Submit แล้ว ระบบจะส่งหลักฐานให้กรรมการตรวจสอบโดยอัตโนมัติ ไม่อนุญาตให้อัพโหลดไฟล์ซ้ำอีกรอบ</li>
        <li>ความละเอียดการ scan ภาพควรเป็น 200 dpi  ขึ้นไป (แนะนำ: 200 dpi)</li>
        <li>ประเภทไฟล์ JPEG, PNG, GIF (แนะนำ: JPEG)</li>
        <li>ขนาดไฟล์ ไม่เกิน 2 MB (แนะนำ: ควรต่ำว่า 250 KB)</li>
        <li><em><strong>ภาพสีเท่านั้น</strong></em></li>
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
<!-- InstanceEnd --></html><? endif; ?>