<?php
require_once 'class.Session.php';
require_once 'config.inc.php';
class SesPrt extends Session{
	protected $SESSION_NAME=__CLASS__;
	public $id, $institution, $country, $teamState, $payState, $ticketState;
	private $memState=array();
	
	public function changeID($force=false){
		global $config;
		require_once 'class.Team.php';
		$t=new Team($config->PDO());
		if(!$t->auth()) Config::redirect('logout.php','Update authenicated session error, please log in again');
		$this->id=$t->id;
		$this->institution=$t->institution;
		$this->country=$t->country;
		$this->teamState=$t->team_state;
		$this->payState=$t->pay_state;
		$this->ticketState=$t->ticket_state;
		$this->setObserverInfoState($t->getObserverInfoState());
		for($i=1;$i<=$config->REG_PARTICIPANT_NUM;$i++)
			$this->setParticipantInfoState($i,$t->getParticipantInfoState($i));
		return parent::changeID($force);
	}
	public function setParticipantInfoState($i,$state){
		global $config;
		if($i>$config->REG_PARTICIPANT_NUM || $i<0) return false;
		$this->memState[$i]=$state;
		return true;
	}
	
	public function getParticipantInfoState($i){
		global $config;
		if($i>$config->REG_PARTICIPANT_NUM || $i<0) return false;
		return $this->memState[$i];
	}
	
	public function setObserverInfoState($state){
		return $this->setParticipantState(0,$state);
	}
	
	public function getObserverInfoState(){
		return $this->getParticipantState(0);
	}
	
	public static function check($returnObj=true,$autoWrite=true){
		return self::checkSession(new self($returnObj&&$autoWrite));
	}
}
?>