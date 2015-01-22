<?php
require_once 'class.Session.php';
require_once 'config.inc.php';
class SesPrt extends Session{
	protected $SESSION_NAME=__CLASS__;
	public $id,
		$institution, $university, $country,
		$teamState, $payState, $postRegState;
		
	private $memberInfoState=array(), $memberPostRegState=array(); // Status of Infomation of Members

	public function changeID($force=false){
		global $config;
		require_once 'class.Team.php';
		$t=new Team($config->PDO());
		if(!$t->auth()) Config::redirect('logout.php','Update authenicated session error, please log in again');
		
		$this->id=$t->id;
		$this->institution=$t->institution;
		$this->university=$t->university;
		$this->country=$t->country;
		$this->teamState=$t->team_state;
		$this->payState=$t->pay_state;
		$this->postRegState=$t->post_reg_state;
		
		for($i=0;$i<=$config->REG_PARTICIPANT_NUM;$i++){
			$this->setParticipantInfoState($i,$t->getParticipantInfoState($i));
			$this->setParticipantPostRegInfoState($i,$t->getParticipantPostRegInfoState($i));
		}
		return parent::changeID($force);
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
		return $this->memberInfoState[$i];
	}
	
	public function setObserverInfoState($state){
		return $this->setParticipantInfoState(0,$state);
	}
	
	public function getObserverInfoState(){
		return $this->getParticipantInfoState(0);
	}
	
	public function setParticipantPostRegInfoState($i,$state){
		global $config;
		if($i>$config->REG_PARTICIPANT_NUM || $i<0) return false;
		$this->memberPostRegState[$i]=$state;
		return true;
	}
	
	public function getParticipantPostRegInfoState($i){
		global $config;
		if($i>$config->REG_PARTICIPANT_NUM || $i<0) return false;
		return $this->memberPostRegState[$i];
	}
	
	public function setObserverPostRegInfoState($state){
		return $this->setParticipantPostRegInfoState(0,$state);
	}
	
	public function getObserverPostRegInfoState(){
		return $this->getParticipantPostRegInfoState(0);
	}
	
	public function getProgression(){
		global $config;
		require_once 'class.State.php';
		$score=$this->payState + $this->postRegState + $this->teamState;
		$full=State::ST_PASS+State::ST_OK+State::ST_PASS;
		for($i=0;$i<=$config->REG_PARTICIPANT_NUM;$i++){
			$score+=$this->memberInfoState[$i]+$this->memberPostRegState[$i];
			$full+=State::ST_PASS+State::ST_OK;
		}
		return $score/$full;
	}
	
	public function checkSession(){
		return $this->sid==session_id() && !(is_null($this->institution) || is_null($this->university) || is_null($this->country));
	}
	
	public static function check($returnObj=true,$autoWrite=true){
		return self::checkSession(new self($returnObj&&$autoWrite));
	}
}
?>