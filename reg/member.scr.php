<?php
require_once 'config.inc.php';
require_once 'class.SesPrt.php';

$s=SesPrt::check(true,true);
if(!$s) Config::redirect('login.php','You do not log in. Please log in.');

require_once 'class.SKAjaxReg.php';
$ajax=new SKAjaxReg();

$uploadAjax=new SKAjaxReg();
$uploadAjax->msgID='uploadMsg';

$db=$config->PDO();

if(Config::isPost() && !State::is($s->getParticipantInfoState($_POST['part_no']),State::ST_EDITABLE,$config->REG_START_REG,$config->REG_END_REG)){
	$ajax->message='You are not allowed to change the information. Please contact administrators.';
	$ajax->result=false;
	
	$uploadAjax->message=$ajax->message;
	$uploadAjax->result=false;
}elseif(Config::isFile() && $_POST['part_no']>0 && $_POST['part_no']<=$config->REG_PARTICIPANT_NUM){
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
			if($_POST['id']!=''){
				require_once 'class.Member.php';
				require_once 'class.State.php';
				
				$p=new Participant($db);
				$p->id=$_POST['id'];
				$p->info_state=State::ST_EDITABLE;
				$p->setState();
				
				$s->setParticipantInfoState($_POST['part_no'], $p->info_state);
				$s->setProgression();
				$uploadAjax->updateMenuState($s);
				$uploadAjax->addAction(SKAjaxReg::RESET_FORM);
			}
			$uploadAjax->message='Upload complete'."<br/><br/>".$upload->toImgPartStudentCard($_POST['part_no']);
			$uploadAjax->result=true;
		}else{
			$uploadAjax->message='Fail to upload. Please try again.';
		}
	}catch(UploadImageException $e){
		$uploadAjax->result=false;
		$uploadAjax->message=$e->getMessage();
	}catch(Exception $e){
		$uploadAjax->result=false;
		$uploadAjax->message=Config::e($e);
	}
	unset($upload);
}elseif(Config::isPost()){
	if(!(Config::isBlank($_POST,'email') || Config::checkEmail($_POST['email'],$e))){
		$ajax->message=$e;
	}elseif(!(Config::isBlank($_POST,'birth') || Config::isDate($_POST['birth'],$e))){
		$ajax->message=$e;
	}elseif(!(Config::isBlank($_POST,'birth') || strtotime($_POST['birth'],time())<time())){
		$ajax->message='Date of birth is greater than today. Please fill out the correct date.';
	}elseif(!($_POST['part_no']==0 || Config::isBlank($_POST,'std_y') || is_numeric($_POST['std_y']))){
		$ajax->message='Please fill out "medical student year" in numeric format.';
	}else{
		try{
			require_once 'class.Member.php';
			require_once 'class.State.php';
			
			$member=Config::assocToObjProp(
				Config::trimArray($_POST),
				$_POST['part_no']==0?new Observer($db):new Participant($db)
			);
			$member->team_id=$s->id;
			$member->beginTransaction();
		//	require_once 'class.Member.php';
		//	$member=new Observer();
			
			if($member->id==0){
				$member->id=$member->add();
				$ajax->result=true;
				$ajax->message='Successfully add new team\'s '.(isset($member->part_no)?'member':'advisor');
			}else{
				$ajax->result=$member->update()>0;
				$ajax->message='Successfully update the information';
			}
			if($ajax->result){
				$ajax->setFormDefault((array) $member, array(Observer::ROW_GENDER, Observer::ROW_SHIRT_SIZE));
				$s->setParticipantInfoState($_POST['part_no'], State::ST_EDITABLE);
				$s->setProgression();
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

if(Config::isAjax()) Config::JSON(Config::isFile()?$uploadAjax:$ajax);
?>