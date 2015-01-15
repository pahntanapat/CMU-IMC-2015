<?php
require_once 'config.inc.php';
require_once 'class.SKeasySQL.php';
require_once 'class.Observer.php';
class Participant extends Observer{
	const ROW_PART_NO='part_no';
	
	public $part_no;
	protected $TABLE='participant_info';
	
	public function add(){
		global $config;
		if(!isset($this->part_no) || $this->part_no>$config->REG_PARTICIPANT_NUM || $this->part_no<0) return false;
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
			self::ROW_OTHER_REQ=>':oreq',
			self::ROW_PART_NO=>':no'
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
		$stm->bindValue(':no',$this->part_no);
		
		$stm->execute();
		$this->id=$this->db->lastInsertId();
		return $this->id;
	}
	
	public function update($isAdmin=false){
		global $config;
		if(!isset($this->part_no) || $this->part_no>$config->REG_PARTICIPANT_NUM || $this->part_no<0) return false;
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
			self::ROW_OTHER_REQ=>':oreq'	,
			self::ROW_PART_NO=>':no'
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
		$stm->bindValue(':no',$this->part_no);
		
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
		$stm->bindValue(':id',$this->id,vPDO::PARAM_INT);
	}
	
	public function del($listID, $listTeam){
		$stm=$this->db->prepare('DELETE FROM '.$this->TABLE.' WHERE '.self::ROW_ID.self::IN($listID).' OR '.self::ROW_TEAM_ID.self::IN($listTeam));
		$stm->execute(array_merge($listID,$listTeam));
		return $stm->rowCount();
	}
	
	public function load(){
		$stm=$this->db->prepare('SELECT '.self::row().' FROM '.$this->TABLE.' WHERE '
			.(isset($this->id)?self::ROW_ID:self::ROW_TEAM_ID.'=? AND '.self::ROW_PART_NO).'=? LIMIT 1');
		
		$stm->bindValue(1,isset($this->id)?$this->id:$this->team_id,PDO::PARAM_INT);
		if(!isset($this->id)) $stm->bindValue(2,$this->part_no,PDO::PARAM_INT);
		
		$stm->execute();
		foreach($stm->fetch(PDO::FETCH_OBJ) as $k=>$v)
			if(isset($this->$k)) $this->$k=$v;
		return $this;
	}
	
	public function getList(){
		return $this->getPDOStm()->fetchAll(PDO::FETCH_CLASS,__CLASS__,array($this->db));
	}
}
?>