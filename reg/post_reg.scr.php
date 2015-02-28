<?php
require_once 'config.inc.php';
require_once 'class.SesPrt.php';

$s=SesPrt::check(true,true);
if(!$s) Config::redirect('login.php','You do not log in. Please log in.');

require_once 'class.SKAjaxReg.php';
$ajax=new SKAjaxReg();
$ajax->result=false;

if(Config::isPost() && !State::is($s->postRegState,State::ST_EDITABLE,$config->REG_START_PAY,$config->REG_END_INFO)){
	$ajax->message='You are not allowed to change the information. Please contact administrators.';
	$ajax->result=false;
	
	$teamAjax=$ajax;
	$teamAjax->msgID='teamAjax';
	
	$ticketAjax=$ajax;
	$ticketAjax->msgID='ticketAjax';
}elseif(Config::isFile()){
	require_once 'class.UploadImage.php';
	
	$img=new UploadImage();
	$img->team_id=$s->id;
	try{
	  switch($_POST['upload']){
		  case 'photo':
			  $img->minFileSize=307200;
			  $img->minResolutionArray=array(900,1600); // 1.44 Mpx
			  $img->uploadTeamPhoto();
			  
			  $ajax->message=$img->toImgTeamPhoto();
			  $ajax->msgID='teamAjax';
			  break;
		  case 'ticket':
			  $img->uploadTicket();
			  $ajax->message=$img->toImgTicket();
			  $ajax->msgID='ticketAjax';
			  break;
			default: throw new Exception('Unknown action: '.$_POST['upload'],0);
	  }
	  
		require_once 'class.Team.php';
		require_once 'class.State.php';
		$t=new Team($config->PDO());
		$t->id=$s->id;
		$t->post_reg_state=State::ST_EDITABLE;
		$t->setState(Team::ROW_POST_REG_STATE);
		
		$s->postRegState=$t->post_reg_state;
		$s->setProgression();
		$ajax->result=true;
		$ajax->message="<b>Upload complete</b><br/>".$ajax->message;
	}catch(UploadImageException $e){
		$ajax->result=false;
		$uploadAjax->message=$e->getMessage();
	}catch(Exception $e){
		$ajax->result=false;
		$ajax->message=Config::e($e);
	}
	if(!Config::isAjax()){
		switch($_POST['upload']){
			case 'photo':
				$teamAjax=$ajax;
				unset($ajax, $t);
				break;
			case 'ticket':
				$ticketAjax=$ajax;
				unset($ajax, $t);
				break;
		}
	}else{
		$ajax->updateMenuState($s);
	}
}elseif(Config::isPost()){
	try{
		require_once 'class.Team.php';
		$t=Config::assocToObjProp(Config::trimArray($_POST), new Team($config->PDO()));
		$t->id=$s->id;
		
		switch($_POST['form']){
			case 'ticket':
				$route=$t->countRoute();
				if($route[$t->route][1]>=$t->maxRoute()){
					$ajax->message="<b>Your chosen route is full. Please select the others.</b>";
					break;
				}
				$t->updatePostReg(true);
				if(Config::isAjax())
					$ajax->setFormDefault((array) $t);
				break;
			case 'route':
				$t->updatePostReg(false);
				if(Config::isAjax()){
					if($ajax->result=true) $route=$t->countRoute();
					$name=$t->getRoute();
					$mx=$t->maxRoute();
					foreach($route as $k=>$v)
						if(isset($name[$k]))
							$ajax->addHtmlTextVal(SKAjaxReg::SET_HTML, 'label[for="route_'.$k.'"]', $name[$k].' ('.$v[0].'/'.$mx.')');
				}
				break;
		}

		require_once 'class.State.php';
		$s->postRegState=State::ST_EDITABLE;
		$s->setProgression();
		if(Config::isAjax()) $ajax->updateMenuState($s);
		
		$ajax->result=true;
		$ajax->message="<b>Successfully update the information</b>";
	}catch(Exception $e){
		$ajax->result=false;
		$ajax->message=Config::e($e);
	}
}

if(Config::isAjax()) Config::JSON($ajax);
?>