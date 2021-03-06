<?php
require_once 'class.Session.php';
require_once 'config.inc.php';
class SesPrt extends Session{
	protected $SESSION_NAME=__CLASS__;
	public $id,
		$teamName,
		$institution, $university, $country,
		$teamState, $payState, $postRegState,
		$cfInfoState, $cfPostRegState;
		
	protected $memberInfoState=array(), $progress=0; // Status of Information of Members

	public function changeID($force=false){
		global $config;
		if($this->checkSession() && ($force||($this->time+self::ID_EXP<time()))){
			require_once 'class.Team.php';
			if($this->id!=NULL){
				$t=new Team($config->PDO());
				$t->id=$this->id;
				
				if(!$t->auth()) Config::redirect('logout.php','Update authenicated session error, please log in again');
				$this->institution=$t->institution;
				$this->university=$t->university;
				$this->country=$t->country;
				$this->teamState=$t->team_state;
				$this->teamName=$t->team_name;
				$this->payState=$t->pay_state;
				$this->postRegState=$t->post_reg_state;
				
				$this->memberInfoState=$t->getInfoState();
			}
			$this->setProgression();
		}
		return parent::changeID($force);
	}
	public function setInfoState($s){
		$this->memberInfoState=$s;
	}

	public function setParticipantInfoState($i,$state){
		global $config;
		if($i>$config->REG_PARTICIPANT_NUM || $i<0) return false;
		$this->memberInfoState[$i]=$state;
		return true;
	}
	
	public function getParticipantInfoState($i){
		global $config;
		if($i>$config->REG_PARTICIPANT_NUM || $i<0) return false;
		return isset($this->memberInfoState[$i])?$this->memberInfoState[$i]:$this->memberInfoState[1];
	}
	
	public function setObserverInfoState($state){
		return $this->setParticipantInfoState(0,$state);
	}
	
	public function getObserverInfoState(){
		return $this->getParticipantInfoState(0);
	}
	
	public function setProgression(){
		global $config;
		require_once 'class.State.php';
		$this->cfInfoState=$this->teamState;
		$this->cfPostRegState=$this->postRegState;
		
		$score=$this->payState + $this->postRegState + $this->teamState;
		$full=State::ST_PASS+State::ST_OK+State::ST_PASS;
		
		for($i=0;$i<=$config->REG_PARTICIPANT_NUM && $i<count($this->memberInfoState);$i++){
			$score+=$this->memberInfoState[$i];
			$full+=State::ST_PASS;
			
			if($this->memberInfoState[$i]<$this->cfInfoState) $this->cfInfoState=$this->memberInfoState[$i];
		}
		$this->progress=(100*$score)/$full;
		if($this->cfInfoState==State::ST_LOCKED) $this->cfInfoState=State::ST_EDITABLE;
	}
	
	public function getProgression(){
		return $this->progress;
	}
	
	public function checkSession(){
		return $this->sid==session_id() && !(is_null($this->institution) || is_null($this->university) || is_null($this->country));
	}
	
	public static function check($returnObj=true,$autoWrite=true){
		$sess=new self($autoWrite&&$returnObj);
		return $sess->checkSession()?($returnObj?$sess:true):false;
	}
}
?>