<?php
require_once 'class.SKeasySQL.php';
require_once 'config.inc.php';
class Team extends SKeasySQL{
	const
		ROW_EMAIL='email',
		ROW_PW='password',
		ROW_INSTITUTION='institution',
		ROW_COUNTRY='country',
		ROW_TEAM_STATE='team_state',
		ROW_PAY_STATE='pay_state',
		ROW_TICKET_STATE='ticket_state'
		;
	
	public $email, $pw, $institution, $country, $team_state, $pay_state, $ticket_state;
	protected $TABLE='team_info';
	protected $member_info_state; // for authenication only
	
	public function add(){
//		$this->db=new PDO();
		$stm=$this->db->prepare($this->insert(array(
			self::ROW_EMAIL=>':e',
			self::ROW_COUNTRY=>':c',
			self::ROW_INSTITUTION=>':i',
			self::ROW_PW=>':p'
			)));
		$stm->bindValue(':e',$this->email);
		$stm->bindValue(':p',$this->pw);
		$stm->bindValue(':i',$this->institution);
		$stm->bindValue(':c',$this->country);
		$stm->execute();
		$this->id=$this->db->lastInsertId();
		return $this->id;
	}
	
	/**
	* Prepare SQL command for function that select data for session
	*/
	private function SQLforSession($withID=false){
		require_once 'class.Participant.php';
		require_once 'class.Observer.php';
		$tmp=array(new Observer(NULL),new Participant(NULL));
		$rows=array(
			$this->TABLE.'.'.self::ROW_INSTITUTION=>self::ROW_INSTITUTION,
			$this->TABLE.'.'.self::ROW_COUNTRY=>self::ROW_COUNTRY,
			$this->TABLE.'.'.self::ROW_TEAM_STATE=>self::ROW_TEAM_STATE,
			$this->TABLE.'.'.self::ROW_PAY_STATE=>self::ROW_PAY_STATE,
			$this->TABLE.'.'.self::ROW_TICKET_STATE=>self::ROW_TICKET_STATE,
			$tmp[0]->TABLE.'.'.Observer::ROW_INFO_STATE=>'obsv_state',
			$tmp[1]->TABLE.'.'.Participant::ROW_INFO_STATE=>'part_state'
		);
		if($withID) $rows[$this->TABLE.'.'.self::ROW_ID]=self::ROW_ID;
		return self::row($rows);
	}
	// convert PDOStatement to $this
	private function prepareSession(PDOStatement $stm){
		if($stm->rowCount()<=0) return false;
		$i=0;
		while($row=$stm->fetch(PDO::FETCH_OBJ)){
			$i++;
			if($i==1){
				foreach($this as $k=>$v)
					if(isset($this->$k)) $this->$k=$v;
				$this->member_info_state[0]=$row->obsv_state;
			}
			$this->member_info_state[$i]=$row->part_state;
		}
		return true;
	}
	// Get Participant's or Observer's (if $i=0) Info State after auth()
	public function getParticipantInfoState(){
		global $config;
		if($i<0 || $i>$config->REG_PARTICIPANT_NUM) return false;
		return $this->member_info_state[$i];
	}
	// Get Observer's Info State after auth()
	public function getObserverInfoState(){
		return $this->member_info_state[0];
	}
	
	public function auth($checkPW=false){
		require_once 'class.Participant.php';
		require_once 'class.Observer.php';
		$tmp=array(new Observer(NULL),new Participant(NULL));
		
		$stm=($this->db->prepare(
			'SELECT '.$this->SQLforSession($checkPW)
			.' FROM '.$this->TABLE
			.'LEFT JOIN '.$tmp[0]->TABLE.' ON '.$tmp[0]->TABLE.'.'.Observer::ROW_TEAM_ID.'='.$this->TABLE.'.'.self::ROW_ID
			.'LEFT JOIN '.$tmp[1]->TABLE.' ON '.$tmp[1]->TABLE.'.'.Participant::ROW_TEAM_ID.'='.$this->TABLE.'.'.self::ROW_ID
			.' WHERE '.($checkPW?self::ROW_EMAIL.' = :e AND '.self::ROW_PW.' = :pw':self::ROW_ID.' = :i')
		));
		if($checkPW){
			$stm->bindValue(':e',$this->email);
			$stm->bindValue(':pw',$this->password);
		}else{
			$stm->bindValue(':i',$this->id);
		}
		$stm->execute();
		
		return $this->prepareSession($stm);
	}
	
	public function del($list){
		$stm=$this->db->prepare('DELETE FROM '.$this->TABLE
			.' WHERE '.self::ROW_ID.self::IN($list));
		$stm->execute($list);
		return $stm->rowCount();
	}
	
	public function changePW($oldPassword){
		$stm=$this->db->prepare('UPDATE '.$this->TABLE.' SET'.self::ROW_PW.'=:n '.' WHERE '.self::ROW_ID.'=:i AND '.self::ROW_PW.'=:o');
		$stm->bindValue(':i',$this->id,PDO::PARAM_INT);
		$stm->bindValue(':o',$oldPassword);
		$stm->bindValue(':n',$this->password);
		$stm->execute();
		return $stm->rowCount();
	}
	
	public function update(){ // For Admin
		$stm=$this->db->prepare('UPDATE '.$this->TABLE	.' SET '.
			self::equal(array(
				self::ROW_EMAIL=>':e',
				self::ROW_PW=>':pw',
				self::ROW_INSTITUTION=>':in',
				self::ROW_COUNTRY=>':c'
			)).' WHERE '.self::ROW_ID.'=:i');
		$stm->bindValue(':i',$this->id,PDO::PARAM_INT);
		$stm->bindValue(':e',$this->email);
		$stm->bindValue(':pw',$this->pw);
		$stm->bindValue(':in',$this->institution);
		$stm->bindValue(':c',$this->country);
		return $stm->execute();
	}
	
	public function updateInfo(){ // For Participant
		require_once 'class.State.php';
		$stm=$this->db->prepare('UPDATE '.$this->TABLE	.' SET '.
			self::equal(array(
				self::ROW_EMAIL=>':e',
				self::ROW_TEAM_STATE=>':s',
				self::ROW_INSTITUTION=>':in',
				self::ROW_COUNTRY=>':c'
			)).' WHERE '.self::ROW_ID.'=:i');
		$stm->bindValue(':i',$this->id,PDO::PARAM_INT);
		$stm->bindValue(':e',$this->email);
		$stm->bindValue(':in',$this->institution);
		$stm->bindValue(':s',State::ST_EDITABLE,PDO::PARAM_INT);
		$stm->bindValue(':c',$this->country);
		$stm->execute();
		$this->team_state=State::ST_EDITABLE;
		return $stm->rowCount();
	}
	
	public function setState($st){
		switch($st){
			case self::ROW_PAY_STATE;
			case self::ROW_TEAM_STATE:
			case self::ROW_TICKET_STATE:
				break;
			default: return;
		}
		$stm=$this->db->prepare('UPDATE '.$this->TABLE.' SET '.$st.'=:s WHERE '.self::ROW_ID.'=:i');
		$stm->bindValue(':i',$this->id,PDO::PARAM_INT);
		$stm->bindValue(':s',$this->$st,PDO::PARAM_INT);
		$stm->execute();
		return $stm->rowCount();
	}
	
	public function load(){
		$stm=$this->db->prepare('SELECT '.self::row().' FROM '.$this->TABLE.' WHERE '.self::ROW_ID.'=:i limit 1');
		$stm->bindValue(':i',$this->id,PDO::PARAM_INT);
		$stm->execute();
		if($stm->rowCount()>0){
			$row=$stm->fetch(PDO::FETCH_OBJ);
			foreach($row as $k=>$v) $this->$k=$v;
		}
		return $stm->rowCount();
	}
}
?>