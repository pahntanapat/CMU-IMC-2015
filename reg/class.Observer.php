<?php
require_once 'class.SKeasySQL.php';
class Observer extends SKeasySQL{
	const
		ROW_TEAM_ID='team_id',
		ROW_TITLE='title',
		ROW_FIRSTNAME='firstname',
		ROW_MIDDLENAME='middlename',
		ROW_LASTNAME='lastname',
		ROW_GENDER='gender',
		ROW_SHIRT_SIZE='shirt_size',
		ROW_EMAIL='email',
		ROW_SOC_NETWORK='soc_network',
		ROW_MED_REQ='med_req',
		ROW_OTHER_REQ='other_req',
		ROW_INFO_STATE='info_state'
		
		;
	
	public $team_id, $title, $firstname, $middlename, $lastname,
		$gender, $shirt_size, $email, $soc_network,
		$med_req, $other_req, $info_state,
		$institution, $country; // for getList
	protected $TABLE='observer_info';
	
	public function add(){
		$stm=$this->db->prepare($this->insert(array(
			self::ROW_TEAM_ID=>':tid',
			self::ROW_TITLE=>':t',
			self::ROW_FIRSTNAME=>':f',
			self::ROW_MIDDLENAME=>':m',
			self::ROW_LASTNAME=>':l',
			self::ROW_GENDER=>':g',
			self::ROW_SHIRT_SIZE=>':ss',
			self::ROW_EMAIL=>':e',
			self::ROW_SOC_NETWORK=>':soc',
			self::ROW_MED_REQ=>':mreq',
			self::ROW_OTHER_REQ=>':oreq'
		)));
		$stm->bindValue(':tid',$this->team_id,PDO::PARAM_INT);
		$stm->bindValue(':t',$this->title);
		$stm->bindValue(':f',$this->firstname);
		$stm->bindValue(':m',$this->middlename);
		$stm->bindValue(':l',$this->lastname);
		$stm->bindValue(':g',$this->gender,PDO::PARAM_BOOL);
		$stm->bindValue(':ss',$this->shirt_size);
		$stm->bindValue(':e',$this->email);
		$stm->bindValue('soc:',$this->soc_network);
		$stm->bindValue('mreq:',$this->med_req);
		$stm->bindValue(':oreq',$this->other_req);
		$stm->execute();
		$this->id=$this->db->lastInsertId();
		return $this->id;
	}
	
	public function update($isAdmin=false){
		$rows=array(
			self::ROW_TEAM_ID=>':tid',
			self::ROW_TITLE=>':t',
			self::ROW_FIRSTNAME=>':f',
			self::ROW_MIDDLENAME=>':m',
			self::ROW_LASTNAME=>':l',
			self::ROW_GENDER=>':g',
			self::ROW_SHIRT_SIZE=>':ss',
			self::ROW_EMAIL=>':e',
			self::ROW_SOC_NETWORK=>':soc',
			self::ROW_MED_REQ=>':mreq',
			self::ROW_OTHER_REQ=>':oreq'	
		);
		if(!$isAdmin) $rows[self::ROW_INFO_STATE]=':state';
		$stm=$this->db->prepare('UPDATE '.$this->TABLE.' SET '
			.self::equal(
				)
			.' WHERE '.self::ROW_ID.'=:id'
		);
		$stm->bindValue(':tid',$this->team_id,PDO::PARAM_INT);
		$stm->bindValue(':t',$this->title);
		$stm->bindValue(':f',$this->firstname);
		$stm->bindValue(':m',$this->middlename);
		$stm->bindValue(':l',$this->lastname);
		$stm->bindValue(':g',$this->gender,PDO::PARAM_BOOL);
		$stm->bindValue(':ss',$this->shirt_size);
		$stm->bindValue(':e',$this->email);
		$stm->bindValue('soc:',$this->soc_network);
		$stm->bindValue('mreq:',$this->med_req);
		$stm->bindValue(':oreq',$this->other_req);
		if(!$isAdmin){
			$this->info_state=State::ST_EDITABLE;
			$stm->bindValue(':state',$this->info_state,PDO::PARAM_INT);
		}
		$stm->bindValue(':id',$this->id,PDO::PARAM_INT);
		$stm->execute();
		return $stm->rowCount();
	}
	
	public function setState(){
		$stm=$this->db->prepare('UPDATE '.$this->TABLE.' SET '.self::ROW_INFO_STATE.'=:state WHERE '.self::ROW_ID.'=:id');
		$stm->bindValue(':state',$this->info_state,PDO::PARAM_INT);
		$stm->bindValue(':id',$this->id,PDO::PARAM_INT);
	}
	
	public function del($listID, $listTeam){
		$stm=$this->db->prepare('DELETE FROM '.$this->TABLE.' WHERE '.self::ROW_ID.self::IN($listID).' OR '.self::ROW_TEAM_ID.self::IN($listTeam));
		$stm->execute(array_merge($listID,$listTeam));
		return $stm->rowCount();
	}
	
	public function load(){
		$stm=$this->db->prepare('SELECT '.self::row().' FROM '.$this->TABLE.' WHERE '
			.(isset($this->id)?self::ROW_ID:self::ROW_TEAM_ID).'=? LIMIT 1');
		$stm->bindValue(1,isset($this->id)?$this->id:$this->team_id);
		$stm->execute();
		foreach($stm->fetch(PDO::FETCH_OBJ) as $k=>$v)
			if(isset($this->$k)) $this->$k=$v;
		return $this;
	}
	protected function getPDOStm(){
		require_once 'class.Team.php';
		$tmp=new Team(NULL);
		$stm=$this->db->prepare(
			'SELECT '.$this->TABLE.'.*, '
				.$tmp->TABLE.'.'.Team::ROW_INSTITUTION.', '
				.$tmp->TABLE.'.'.Team::ROW_COUNTRY.', '
			.' FROM '.$this->TABLE
			.' LEFT JOIN '.$tmp->TABLE.' ON '.$tmp->TABLE.'.'.Team::ROW_ID.'='.$this->TABLE.'.'.self::ROW_TEAM_ID
			.' WHERE '.self::ROW_TEAM_ID.'=?'
		);
		$stm->execute(array($this->team_id));
		return $stm;
	}
	public function getList(){
		return $this->getPDOStm()->fetchAll(PDO::FETCH_CLASS,__CLASS__,array($this->db));
	}
}
?>