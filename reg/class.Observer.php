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
		ROW_BIRTH='birth',
		ROW_PASSPORT_NO='passport_no',
		ROW_PASSPORT_EXP='passport_exp',
		ROW_RELIGION='religion',
		ROW_NATIONALITY='nationality',
		
		ROW_PHONE='phone',
		ROW_EMAIL='email',
		ROW_FB='fb',
		ROW_TW='tw',
		
		ROW_SHIRT_SIZE='shirt_size',
		ROW_CUISINE='cuisine',
		ROW_ALLERGY='allergy',
		ROW_DISEASE='disease',
		ROW_OTHER_REQ='other_req',
		
		ROW_INFO_STATE='info_state',
		ROW_POST_REG_STATE='post_reg_state'
		;
	
	public $team_id, $title, $firstname, $middlename, $lastname,
		$gender, $birth, $passport_no, $passport_exp, $religion, $nationality,
		$phone, $email, $fb, $tw,
		$shirt_size, $cuisine, $allergy, $disease, $other_req,
		$info_state,$post_reg_state,
		
		$university, $institution, $country; // for getList
	public $TABLE='observer_info';
	
	protected function rowArray($postReg=false){
		if($postReg)
			return array(
				self::ROW_SHIRT_SIZE=>':ss',
				self::ROW_PASSPORT_NO=>':pno',
				self::ROW_PASSPORT_EXP=>':pexp'
			);
		else
			return array(
				self::ROW_TEAM_ID=>':tid',
				
				self::ROW_TITLE=>':t',
				self::ROW_FIRSTNAME=>':f',
				self::ROW_MIDDLENAME=>':m',
				self::ROW_LASTNAME=>':l',
				
				self::ROW_GENDER=>':g',
				self::ROW_BIRTH=>':bth',
	//			self::ROW_PASSPORT_NO=>':pno',
	//			self::ROW_PASSPORT_EXP=>':pexp',
				self::ROW_RELIGION=>':religion',
				self::ROW_NATIONALITY=>':nationality',
				
				self::ROW_PHONE=>':phone',
				self::ROW_EMAIL=>':e',
				self::ROW_FB=>':fb',
				self::ROW_TW=>':tw',
				
	//			self::ROW_SHIRT_SIZE=>':ss',
				self::ROW_CUISINE=>':cuisine',
				self::ROW_ALLERGY=>':allergy',
				self::ROW_DISEASE=>':disease',
				self::ROW_OTHER_REQ=>':oreq',
			);
	}
	protected function bindValue(PDOStatement $stm,$postReg=false){
		if($postReg){
			$stm->bindValue(':ss',$this->shirt_size);
			$stm->bindValue(':pno',$this->passport_no);
			$stm->bindValue(':pexp',$this->passport_exp);
		}else{
			$stm->bindValue(':tid',$this->team_id,PDO::PARAM_INT);
			
			$stm->bindValue(':t',$this->title);
			$stm->bindValue(':f',$this->firstname);
			$stm->bindValue(':m',$this->middlename);
			$stm->bindValue(':l',$this->lastname);
			
			$stm->bindValue(':g',$this->gender,PDO::PARAM_BOOL);
			$stm->bindValue(':bth',$this->birth?$this->birth:NULL);
	//		$stm->bindValue(':pno',$this->passport_no);
	//		$stm->bindValue(':pexp',$this->passport_exp);
			$stm->bindValue(':religion',$this->religion);
			$stm->bindValue(':nationality',$this->nationality);
			
			$stm->bindValue(':e',$this->email);
			$stm->bindValue(':phone',$this->phone);
			$stm->bindValue(':fb',$this->fb);
			$stm->bindValue(':tw',$this->tw);
			
	//		$stm->bindValue(':ss',$this->shirt_size);
			$stm->bindValue(':cuisine',$this->cuisine);
			$stm->bindValue(':allergy',$this->allergy);
			$stm->bindValue(':disease',$this->disease);
			$stm->bindValue(':oreq',$this->other_req);
		}
	//	return $stm;
	}
	
	public function add(){
		$stm=$this->db->prepare($this->insert($this->rowArray()));
		$this->bindValue($stm);
		$stm->execute();
		$this->id=$this->db->lastInsertId();
		return $this->id;
	}
	
	public function update($isAdmin=false,$postReg=false){
		if($isAdmin){
			$rows=array_merge($this->rowArray(),$this->rowArray(true));
		}else{
			$rows=$this->rowArray($postReg);
			$rows[($postReg?self::ROW_POST_REG_STATE:self::ROW_INFO_STATE)]=':state';
		}
		$stm=$this->db->prepare('UPDATE '.$this->TABLE
			.' SET '.self::equal($rows)
			.' WHERE '.self::ROW_ID.'=:id'
		);
		$this->bindValue($stm,$postReg);
		if($isAdmin) $this->bindValue($stm,!$postReg);
		else{
			require_once 'class.State.php';
			$this->info_state=State::ST_EDITABLE;
			$stm->bindValue(':state',$this->info_state,PDO::PARAM_INT);
		}
		
		$stm->bindValue(':id',$this->id,PDO::PARAM_INT);
		$stm->execute();
		return $stm->rowCount();
	}
	
	public function setState($state){
		switch($state){
			case self::ROW_POST_REG_STATE:
				$v=$this->post_reg_state;
				break;
			case self::ROW_INFO_STATE:
				$v=$this->info_state;
				break;
			default: return false;
		}
		$stm=$this->db->prepare('UPDATE '.$this->TABLE.' SET '.$state.'=:state WHERE '.self::ROW_ID.'=:id');
		$stm->bindValue(':state',$v,PDO::PARAM_INT);
		$stm->bindValue(':id',$this->id,PDO::PARAM_INT);
		$stm->execute();
		return $stm->rowCount();
	}
	
	public function del($listID, $listTeam){
		$stm=$this->db->prepare('DELETE FROM '.$this->TABLE.' WHERE '.self::ROW_ID.self::IN($listID).' OR '.self::ROW_TEAM_ID.self::IN($listTeam));
		$stm->execute(array_merge($listID,$listTeam));
		return $stm->rowCount();
	}
	
	public function load(){
		$stm=$this->db->prepare('SELECT '.self::row().' FROM '.$this->TABLE.' WHERE '
			.($this->id?self::ROW_ID:self::ROW_TEAM_ID).'=? LIMIT 1');
		$stm->bindValue(1,$this->id?$this->id:$this->team_id);
		$stm->execute();
		if($stm->rowCount()>0)
			foreach($stm->fetch(PDO::FETCH_OBJ) as $k=>$v)
				if(property_exists($this, $k)) $this->$k=$v;
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