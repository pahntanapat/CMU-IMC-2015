<?php
require_once 'config.inc.php';
require_once 'class.SKAjax.php';
require_once 'class.SesPrt.php';
require_once 'class.State.php';

class SKAjaxReg extends SKAjax{
	public function updateMenuState(SesPrt $s){
		global $config;
		
		$param=array();
		$c=new ReflectionClass('State');
		foreach($c->getConstants() as $v)
			$param[0][]=State::toClass($v);
		
		$param[1]["#menuTeamInfo"]=State::toClass($s->teamState);
		$param[1]["#menuObsvInfo"]=State::toClass($s->getObserverInfoState());
		$param[1]["#menuCfInfo"]=State::toClass($s->cfInfoState);
		$param[1]["#menuPay"]=State::toClass($s->payState);
		
		$param[1][".menuPostReg"]=State::toClass($s->postRegState);
		$param[1]["#cfPostReg"]=State::toClass($s->cfPostRegState);
		
		for($i=1;$i<=$config->REG_PARTICIPANT_NUM;$i++)
			$param[1]["#menuPartInfo".$i]=State::toClass($s->getParticipantInfoState($i));
		
		$param[2]=$s->getProgression();
		return $this->addAction(self::EVALUTE,'$.updateMenuState('.json_encode($param).');');
	}
}
?>