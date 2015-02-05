<?php
require_once 'config.inc.php';
require_once $_ROOT.'/login/config.inc.php';
require_once $_ROOT.'/login/upload_img_excp.php';
require_once 'class.Session.php';
header("Content-Type: text/html; charset=utf-8");
$sess=new Session();
Session::isLogIn(true,Session::PMS_STD_ID,$sess);
$db=newPDO();
function uconv(&$str,$key,$toCSV){ //แปลง charset
	$str=($toCSV)?iconv('utf-8','windows-874',$str):iconv('windows-874','utf-8',$str);
}
function conv($arr,$toCSV=false){ //แปลง charset
	array_walk($arr,'uconv',$toCSV);
	return $arr;
}
if(count($_POST)+count($_FILES)>0): // มีการอัพโหลดไฟล์
	try{
		// เรียง $_FILES ใหม่  http://th1.php.net/manual/en/reserved.variables.files.php
		$tmp=array();
		foreach($_FILES['file'] as $k=>$file)
			foreach($file as $i=>$v)
				$tmp[$i][$k]=$v;
		$_FILES=$tmp;
		// วน loop แต่ละไฟล์
		foreach($_FILES as $file){
			if($file['error']!=UPLOAD_ERR_OK) // ตรวจ error code
				throw new UploadImageException('',$file['error'],UploadImageException::TYPE_UPLOAD_ERROR);
			if(!in_array($file['type'],array('application/vnd.ms-excel','text/plain','text/csv','text/tsv','application/download')))
				throw new UploadImageException('Unsupport EXT',UploadImageException::CODE_UNSUPPORT_EXT, UploadImageException::TYPE_IMG_ERROR);
			
			$db->beginTransaction();
			$fp=fopen($file['tmp_name'],'r'); //read file
			if(!$fp) throw new UploadImageException('Can not open',UploadImageException::CODE_UNSUPPORT_EXT,UploadImageException::TYPE_IMG_ERROR);
			
			$sql='UPDATE '; //SQL query
			$tmp=fgetcsv($fp);
			/*
			รูปแบบไฟล์รายชื่อทีมหรือรายชื่อผู้แข่งขัน
			แถวแรก: table,[ประเภทข้อมูลในไฟล์ team (ทีม)/student (ผขข. = ผู้แข่งขัน)], "create at",  [เวลาสร้างไฟล์]
			แถวที่สอง: คำอธิบาย column ข้อมูล
			ถ้าเป็น team: id , name, type, school, sorted_id
			ถ้าเป็น student: id, title, fname (firstname), lname (lastname), school, t_sid (รหัสทีม), s_sid (รหัสผู้แข่งขัน), exam_room (ที่นั่งสอบ)
			แถวที่ 3+: ข้อมูลตาม column
			*/
			if($tmp[0]=='table'){
				//อ่านแถว 1 และตรวจสอบประเภทข้อมูลในไฟล์ 
				if($tmp[1]=='team')	$isTeam=true;
				elseif($tmp[1]=='student')	$isTeam=false;
				else continue;
				$sql.=($isTeam)?'team_info':'participant_info'; //table
				$sql.=' SET sorted_id=:sid';
				if(!$isTeam) $sql.=', exam_room=:xr'; //ถ้าเป็นไฟล์ข้อมูล ผขข.
				$sql.=' WHERE id=:id';
				
				$tmp=fgetcsv($fp); // อ่าน แถวที่ 2 ทิ้งเฉยๆ
				while($tmp=fgetcsv($fp)){ // อ่านแถวที่ 3+
					$stm=$db->prepare($sql);
					$arr=array(
						':id'=>$tmp[0], // column index 0 = id
						':sid'=>$tmp[($isTeam)?4:6] // column index = 4 ในไฟล์ทีม, 6 ในไฟล์ผขข
					);
					if(!$isTeam) $arr[':xr']=$tmp[7]; // exam_room ในไฟล์ผขข
					$stm->execute(conv($arr));
					// team: id, team_name, type, school, sorted_id
					// student: id, title, firstname, lastname, school, team_info.sorted_id, sorted_id
				}
			}
			fclose($fp);
			unset($fp,$tmp,$arr);
			echo "บันทึกรหัสประจำ".($isTeam?'ทีม':'ตัวผู้แข่งขัน')." file: ".$file['name']."<br/>\n";
			$db->commit();
		}
		unset($fp,$sql,$stm,$tmp);
	}catch(Exception $e){
		if($db->inTransaction())	$db->rollBack();
		if($fp!=NULL) fclose($fp);
		echo nl2br($e);
	}
elseif(isset($_GET['type'])):
	try{
		$sql=$_GET['type']=='team'
?'SELECT
	team_info.id, team_info.team_name AS "ชื่อทีม",
	IF(team_info.type,"อิสระ","โรงเรียน") AS "ประเภททีม",
	GROUP_CONCAT(DISTINCT
		participant_info.school
		ORDER BY participant_info.id ASC
		SEPARATOR :s) AS school,
	team_info.sorted_id AS "รหัสทีม"
FROM team_info
INNER JOIN participant_info
ON team_info.id=participant_info.team_id AND participant_info.is_pass=:p AND participant_info.is_upload=:p
WHERE team_info.is_pass=:p AND team_info.is_pay=:p
GROUP BY team_info.id
ORDER BY team_info.sorted_id, type, school, team_info.team_name, id ASC;'
:'SELECT
	participant_info.id AS id, participant_info.title, participant_info.firstname, participant_info.lastname ,
	participant_info.school, team_info.sorted_id AS "รหัสทีม", participant_info.sorted_id AS "รหัสผู้แข่งขัน",
	participant_info.exam_room AS "ห้องสอบ"
FROM participant_info
INNER JOIN team_info ON team_info.id=participant_info.team_id AND team_info.is_pass=:p AND team_info.is_pay=:p
WHERE participant_info.is_pass=:p AND participant_info.is_upload=:p
ORDER BY team_info.sorted_id, participant_info.sorted_id, team_info.team_name, team_info.id, id ASC;';
		$stm=$db->prepare($sql);
		if($_GET['type']=='team')	$stm->bindValue(':s','/'); //separator เอาไว้เชื่อมชื่อโรงเรียนในแต่ละทีม
		$stm->bindValue(':p',State::STATE_PASS,PDO::PARAM_INT); //ต้องเป็นทีมที่ข้อมูลผ่าน จ่ายเงินแล้ว
		$stm->execute();
		
		$fp=fopen("php://memory", "w"); //แสดงออกมาเป็น csv file คำอธิบายอยู่ด้านบนนะ
		fputcsv($fp,array('table',$_GET['type'],'create at',date('Y-m-d'),date('H:i:s'))); // row 1
		foreach($stm->fetchAll(PDO::FETCH_ASSOC) as $i=>$row){
			if($i==0) fputcsv($fp,conv(array_keys($row),true)); // row 2
			fputcsv($fp,conv($row,true)); // row 3+
		}
		
		header("Pragma: public");
		header("Expires: 0"); // set expiration time
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
		
		header("Content-Type: application/force-download");
		header("Content-Type: text/csv;charset=window-874");
		header("Content-Type: application/download");
		header("Content-Disposition: attachment; filename=mahidol_".$_GET['type'].".csv;");
		rewind($fp);
		while(($row=fgets($fp))!==false)	echo $row;
		fclose($fp);
	}catch(Exception $e){
		echo nl2br($e."\n".$sql."\n\n".ob_get_clean());
	}
else:
?>
<!doctype html>
<html><!-- InstanceBegin template="/Templates/mahidol_admin.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta charset="utf-8">
<!-- InstanceBeginEditable name="doctitle" -->
<title>จัดการรหัสผู้แข่งขันและห้องสอบ - Admin::Mahidol Quiz</title>
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
<style>
#me{
	border:thick #90F dashed;
	width:90%;
	height:150px;
	margin:10px auto;
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
  <div><!-- InstanceBeginEditable name="headline" -->จัดการรหัสผู้แข่งขันและห้องสอบ<img src="../../images/trophy.png" width="60" height="60" alt="Log in ผู้แข่งขัน"><!-- InstanceEndEditable --></div></div>
<div class="mainContent"><!-- InstanceBeginEditable name="main_content" -->
  <h3>ดาวน์โหลด</h3>
  <ol>
    <li><a href="give_sorted_id.php?type=team" title="ดาวน์โหลดไฟล์ชื่อทีม" target="me">ดาวน์โหลดไฟล์ชื่อทีม</a></li>
    <li><a href="give_sorted_id.php?type=student" title="ดาวน์โหลดไฟล์ชื่อผู้แข่งขัน" target="me">ดาวน์โหลดไฟล์ชื่อผู้แข่งขัน</a></li>
  </ol>
  <form action="give_sorted_id.php" method="post" enctype="multipart/form-data" name="form" target="me" id="form"><fieldset><legend>Upload</legend>
    <div>
      <label for="file[]">อัพโหลดไฟล์ที่กรอกแล้ว (*.csv)</label>
      <input type="file" name="file[]" id="file[]" multiple required>
    </div>
    <div class="btnset">
      <input type="submit" name="button" id="button" value="Submit">
      <input type="reset" name="button2" id="button2" value="Reset">
    </div>
    <iframe name="me" id="me"></iframe></fieldset>
  </form>
  
<!-- InstanceEndEditable --></div>
</div></div>
<div id="footer"></div>
</body>
<!-- InstanceEnd --></html><? endif;?>