<?php
require_once 'config.inc.php';
require_once 'class.SesPrt.php';

$s=SesPrt::check(true,true);
if(!$s) Config::redirect('login.php','You do not log in. Please log in.');

require_once 'class.SKAjaxReg.php';
$ajax=new SKAjaxReg();
$uploadAjax=new SKAjaxReg();

$db=$config->PDO();

if(Config::isFile() && $_POST['part_no']>0 && $_POST['part_no']<=$config->REG_PARTICIPANT_NUM){
	$uploadAjax->msgID='uploadMsg';
	$uploadAjax->result=false;
	try{
		require_once 'class.UploadImage.php';
		$upload=new UploadImage();
		$upload->team_id=$s->id;
		// $upload->minResolutionArray=array(720,435); // Student Card 3.583*2.165 in 200dpi
		// Use A4
		$upload->minFileSize=60000;
		$upload->quality=50;
		if($upload->uploadPartStudentCard($_POST['part_no'])){
			$uploadAjax->message='Upload your image complete'."<br/><br/>".$upload->toImgPartStudentCard($_POST['part_no']);
			$uploadAjax->result=true;
		}else{
			$uploadAjax->message='Fail to upload your image. Please try again.';
		}
	}catch(UploadImageException $e){
		$uploadAjax->result=false;
		$uploadAjax->message=$e->getMessage();
	}catch(Exception $e){
		$uploadAjax->result=false;
		$uploadAjax->message=Config::e($e);;
	}
	unset($upload);
}elseif(Config::isPost()){
	if(!State::is($s->teamState,State::ST_EDITABLE) || time()>strtotime($config->REG_END_REG) || time()<strtotime($config->REG_START_REG)){
		$ajax->message='You are not allowed to change the information. Please contact administrators.';
	}elseif(!(Config::isBlank($_POST,'email') || Config::checkEmail($_POST['email'],$e))){
		$ajax->message=$e;
	}elseif(!(Config::isBlank($_POST,'birth') || Config::isDate($_POST['birth'],$e))){
		$ajax->message=$e;
	}elseif(!(Config::isBlank($_POST,'birth') || strtotime($_POST['birth'],time())<time())){
		$ajax->message='Date of birth is greater than today. Please fill out the correct date.';
	}elseif(!($_POST['part_no']==0 || Config::isBlank($_POST,'std_y') || is_numeric($_POST['std_y']))){
		$ajax->message='Please fill out "Medical student year" in numeric format.';
	}else{
		try{
			require_once 'class.Participant.php';
			$member=Config::assocToObjProp($_POST,$_POST['part_no']==0?new Observer($db):new Participant($db));
			$member->team_id=$s->id;
			$member->beginTransaction();
		//	require_once 'class.Observer.php';
		//	$member=new Observer();
			
			if($member->id==0){
				$member->id=$member->add();
				$ajax->result=true;
				$ajax->message='Add new team\'s member success ';
			}else{
				$ajax->result=$member->update()>0;
				$ajax->message=$ajax->result?'Update the information success.':'No any information change.';
			}
			if($ajax->result){
				$ajax->setFormDefault((array) $member);
				$s->setParticipantInfoState($_POST['part_no'],$member->info_state);
				$ajax->updateMenuState($s);
			}
			$member->commit();
		}catch(Exception $e){
			$member->rollBack();
			$ajax->message=Config::e($e);
			$ajax->result=false;
		}
	}
}

if(Config::isAjax()) Config::JSON(Config::isFile()?$uploadAjax:$ajax,true);
?>