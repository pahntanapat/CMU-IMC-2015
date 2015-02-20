<?php
require_once 'class.SKeasySQL.php';
require_once 'config.inc.php';
abstract class Member extends SKeasySQL{
	const
		ROW_TEAM_ID='team_id',
		
		ROW_TITLE='title',
		ROW_FIRSTNAME='firstname',
		ROW_MIDDLENAME='middlename',
		ROW_LASTNAME='lastname',
		
		ROW_GENDER='gender',
		ROW_BIRTH='birth',
	//	ROW_PASSPORT_NO='passport_no',
	//	ROW_PASSPORT_EXP='passport_exp',
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
		
		ROW_INFO_STATE='info_state'
		;
	
	public $team_id, $title, $firstname, $middlename, $lastname,
		$gender, $birth,
	//	$passport_no, $passport_exp,
		$religion, $nationality,
		$phone, $email, $fb, $tw,
		$shirt_size, $cuisine, $allergy, $disease, $other_req,
		$info_state,
		
		$university, $institution, $country; // for getList
	public $TABLE='observer_info';
	
	protected function rowArray(){
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
			
			self::ROW_SHIRT_SIZE=>':ss',
			self::ROW_CUISINE=>':cuisine',
			self::ROW_ALLERGY=>':allergy',
			self::ROW_DISEASE=>':disease',
			self::ROW_OTHER_REQ=>':oreq',
		);
	}
	protected function bindValue(PDOStatement $stm){
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
		
		$stm->bindValue(':ss',$this->shirt_size);
		$stm->bindValue(':cuisine',$this->cuisine);
		$stm->bindValue(':allergy',$this->allergy);
		$stm->bindValue(':disease',$this->disease);
		$stm->bindValue(':oreq',$this->other_req);
		//	return $stm;
	}
	
	public function add(){
		$stm=$this->db->prepare($this->insert($this->rowArray()));
		$this->bindValue($stm);
		$stm->execute();
		$this->id=$this->db->lastInsertId();
		return $this->id;
	}
	
	public function update($isAdmin=false){
		$rows=$this->rowArray();
		if(!$isAdmin)
			$rows[self::ROW_INFO_STATE]=':state';

		$stm=$this->db->prepare('UPDATE '.$this->TABLE
			.' SET '.self::equal($rows)
			.' WHERE '.self::ROW_ID.'=:id'
		);
		
		$this->bindValue($stm);
		if(!$isAdmin){
			require_once 'class.State.php';
			$this->info_state=State::ST_EDITABLE;
			$stm->bindValue(':state',$this->info_state,PDO::PARAM_INT);
		}
		$stm->bindValue(':id',$this->id,PDO::PARAM_INT);
		$stm->execute();
		return $stm->rowCount();
	}
	
	public function setState(){ // for Admin or Confirmation
		$stm=$this->db->prepare('UPDATE '.$this->TABLE
			.' SET '.self::ROW_INFO_STATE.'=:state'
			.' WHERE '.($this->id?self::ROW_ID:self::ROW_TEAM_ID).'=:i'
		);
		$stm->bindValue(':state',$v,PDO::PARAM_INT); // Team ID for comfirmation
		$stm->bindValue(':i',$this->id?$this->id:$this->team_id,PDO::PARAM_INT);
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
			.($this->team_id?' WHERE '.self::ROW_TEAM_ID.'=?':'')
		);
		if($this->team_id) $stm->bindValue(1,$this->team_id,PDO::PARAM_INT);
		$stm->execute();
		return $stm;
	}
	
	//Miscellenous method
	public static function gender(){
		if(func_num_args()>0) $c=func_get_arg(0);
		elseif(isset($_REQUEST['country'])) $c=$_REQUEST['gender'];
		else $c='';
		$d=func_num_args()>1?func_get_arg(1):false;
		ob_start();?>
          <div><label class="require">Gender</label>
                <input name="gender" type="radio" id="gender_1" value="1"<? if($c==1):?> checked="CHECKED"<? endif; if($d):?> disabled="disabled"<? endif;?>><label for="gender_1">Male</label>
               <input name="gender" type="radio" id="gender_0" value="0"<? if($c==0):?> checked="CHECKED"<? endif; if($d):?> disabled="disabled"<? endif;?>><label for="gender_0">Female</label>
          </div>
<?php
        return ob_get_clean();
	}
	
	public static function shirtSize(){
		global $config;
		if(func_num_args()>0) $c=func_get_arg(0);
		elseif(isset($_REQUEST['shirt_size'])) $c=$_REQUEST['shirt_size'];
		else $c='';
		$d=func_num_args()>1?func_get_arg(1):false;
		ob_start();?><div><label class="require">Shirt size</label>
<?php
		  foreach(explode("\n",$config->INFO_SHIRT_SIZE) as $v):
		  	$v=trim($v);?>
<input name="shirt_size" type="radio" id="shirt_size_<?=$v?>" value="<?=$v?>"<? if($c==$v):?> checked="CHECKED"<? endif; if($d):?> disabled="disabled"<? endif;?>><label for="shirt_size_<?=$v?>"><?=$v?></label>
<? endforeach;?></div>
<?php
        return ob_get_clean();
	}
}

class Observer extends Member{
	// get unique observer
	public function getList(){
		return $this->getPDOStm()->fetchAll(PDO::FETCH_CLASS,__CLASS__,array($this->db));
	}
}

class Participant extends Member{
	const ROW_PART_NO='part_no', ROW_EM_CNT='emerg_contact', ROW_STD_Y='std_y';
	
	public $part_no,$emerg_contact,$std_y;
	public $TABLE='participant_info';
	
	protected function rowArray(){
		global $config;
		
		$merge=array();
		$merge[self::ROW_EM_CNT]=':emrg';
		$merge[self::ROW_STD_Y]=':std_y';
		
		if($this->part_no>0 && $this->part_no<=$config->REG_PARTICIPANT_NUM)
			$merge[self::ROW_PART_NO]=':part_no';
		
		return array_merge($merge,parent::rowArray());
	}
	
	protected function bindValue(PDOStatement $stm){
		global $config;
		parent::bindValue($stm);
		if($this->part_no>0 && $this->part_no<=$config->REG_PARTICIPANT_NUM)
			$stm->bindValue(':part_no',$this->part_no,PDO::PARAM_INT);

		$stm->bindValue(':emrg',$this->emerg_contact);
		$stm->bindValue(':std_y',is_numeric($this->std_y)?$this->std_y:NULL,PDO::PARAM_INT);
	}
	
	public function load(){
		$stm=$this->db->prepare('SELECT '.self::row().' FROM '.$this->TABLE.' WHERE '
			.($this->id?self::ROW_ID:self::ROW_PART_NO.'=? AND '.self::ROW_TEAM_ID).'=? LIMIT 1');
		
		if($this->id){
			$stm->bindValue(1,$this->id);
		}else{
			$stm->bindValue(1,$this->part_no);
			$stm->bindValue(2,$this->team_id);
		}
		$stm->execute();
		if($stm->rowCount()>0)
			foreach($stm->fetch(PDO::FETCH_OBJ) as $k=>$v)
				if(property_exists($this, $k)) $this->$k=$v;
		return $this;
	}
	
	public function getList(){
		return $this->getPDOStm()->fetchAll(PDO::FETCH_CLASS,__CLASS__,array($this->db));
	}
}
?>