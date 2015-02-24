<?php
require_once 'config.inc.php';
require_once 'class.SesPrt.php';

$s=SesPrt::check(true,true);
if(!$s) Config::redirect('login.php','You do not log in. Please log in.');

if(Config::isFile()){
	require_once 'class.SKAjaxReg.php';
	require_once 'class.UploadImage.php';
	require_once 'class.Team.php';
	require_once 'class.State.php';
	
	$ajax=new SKAjaxReg();
	$ajax->msgID='uploadMsg';
	$ajax->result=false;
	
	$t=new Team($config->PDO());
	$t->id=$s->id;
	$t->load();
	$pay=$t->countPay();
	
	$img=new UploadImage();
	$img->team_id=$t->id;
	
	if(!State::is($t->pay_state, State::ST_EDITABLE, $config->REG_START_PAY, $config->REG_END_PAY)){
		$ajax->message="<div class=\"alert-box alert radius\">You are not allowed to upload your transaction.</div>";
	}elseif($pay[1]>=$config->REG_MAX_TEAM){
		$ajax->message="<div class=\"alert-box alert radius\">Sorry! The team uploading their transactions are full (".$config->REG_MAX_TEAM." teams).</div>";
	}else{
		try{
			$img->minFileSize=10240;
			$img->minResolutionArray=array(1850,850); // min size letter No.9 9.25*4.25 300 dpi =2775,1275, 200 dpi=1850,850
			$img->quality=50;
			
			if($img->uploadPay()){
				$t->pay_state=State::ST_WAIT;
				$t->setState(Team::ROW_PAY_STATE);
				
				$s->payState=$t->pay_state;
				$s->setProgression();
				$ajax->addAction(SKAjaxReg::RESET_FORM);
				$ajax->updateMenuState($s);
				
				$ajax->result=true;
				$ajax->message="<div class=\"alert-box success radius\">Upload your transaction complete. Please wait for transaction approval.</div>";
			}
		}catch(UploadImageException $e){
			$ajax->result=false;
			$ajax->message="<div class=\"alert-box alert radius\">".$e->getMessage()."</div>";
		}catch(Exception $e){
			$ajax->result=false;
			$ajax->message=Config::e($e);
		}
	}
	
	$ajax->message.=$img->toImgPay();
}
if(Config::isAjax()) Config::JSON($ajax);
?>