<?php
require_once 'class.SKeasySQL.php';
require_once 'config.inc.php';
class Team extends SKeasySQL{
	const
		ROW_EMAIL='email',
		ROW_PW='pw',
		
		ROW_TEAM_NAME='team_name',
		ROW_INSTITUTION='institution',
		ROW_UNIVERSITY='university',
		ROW_ADDRESS='address',
		ROW_COUNTRY='country',
		ROW_PHONE='phone',
		
		ROW_ARRIVE_BY='arrive_by',
		ROW_ARRIVE_TIME='arrive_time',
		ROW_DEPART_BY='depart_by',
		ROW_DEPART_TIME='depart_time',
		
		ROW_ROUTE='route',
		
		ROW_TEAM_STATE='team_state',
		ROW_PAY_STATE='pay_state',
		ROW_POST_REG_STATE='post_reg_state'
		;
	
	public $email, $pw,
		$team_name,
		$institution, $university, $address, $country, $phone,
		$arrive_by, $arrive_time, $depart_by, $depart_time,
		$route,
		$team_state, $pay_state, $post_reg_state;
	protected $memberInfoState; // for authenication only
	public $TABLE='team_info',
		$rows=array(
			self::ROW_EMAIL=>':e',
			self::ROW_PW=>':pw',
			
			self::ROW_TEAM_NAME=>':tmn',
			self::ROW_INSTITUTION=>':in',
			self::ROW_UNIVERSITY=>':u',
			self::ROW_COUNTRY=>':c',
			self::ROW_ADDRESS=>':ad',
			self::ROW_PHONE=>':p',
	
			self::ROW_ARRIVE_BY=>':arrive_by',
			self::ROW_ARRIVE_TIME=>':arrive_time',
			self::ROW_DEPART_BY=>':depart_by',
			self::ROW_DEPART_TIME=>':depart_time',
	
			self::ROW_ROUTE=>':route'
		);
		
	/**
	* Prepare SQL command for function that select data for session
	*/
	private function SQLforSession($withID=false){
		require_once 'class.Member.php';
		$tmp=array(new Observer(NULL), new Participant(NULL));
		$rows=array(
			$this->TABLE.'.'.self::ROW_ID=>self::ROW_ID,
			$this->TABLE.'.'.self::ROW_TEAM_NAME=>self::ROW_TEAM_NAME,
			$this->TABLE.'.'.self::ROW_INSTITUTION=>self::ROW_INSTITUTION,
			$this->TABLE.'.'.self::ROW_UNIVERSITY=>self::ROW_UNIVERSITY,
			$this->TABLE.'.'.self::ROW_COUNTRY=>self::ROW_COUNTRY,
			$this->TABLE.'.'.self::ROW_TEAM_STATE=>self::ROW_TEAM_STATE,
			$this->TABLE.'.'.self::ROW_PAY_STATE=>self::ROW_PAY_STATE,
			$this->TABLE.'.'.self::ROW_POST_REG_STATE=>self::ROW_POST_REG_STATE,
			$tmp[0]->TABLE.'.'.Observer::ROW_INFO_STATE=>'obsv_info',
		//	$tmp[0]->TABLE.'.'.Observer::ROW_POST_REG_STATE=>'obsv_prs',
			$tmp[1]->TABLE.'.'.Participant::ROW_INFO_STATE=>'part_info',
		//	$tmp[1]->TABLE.'.'.Participant::ROW_POST_REG_STATE=>'part_prs',
		);
		if($withID) $rows[$this->TABLE.'.'.self::ROW_ID]=self::ROW_ID;
		return self::row($rows);
	}
	// convert PDOStatement to $this
	private function prepareSession(PDOStatement $stm){
		require_once 'class.State.php';
		if($stm->rowCount()<=0) return false;
		$i=0;
		while($row=$stm->fetch(PDO::FETCH_OBJ)){
			$i++;
			if($i==1){
				foreach($row as $k=>$v)
					if(property_exists($this,$k)) $this->$k=$v;
				$this->memberInfoState[0]=($row->obsv_info===NULL?State::ST_EDITABLE:$row->obsv_info);
			}
			$this->memberInfoState[$i]=($row->part_info===NULL?State::ST_EDITABLE:$row->part_info);
		}
		return true;
	}
	public function getInfoState(){
		return $this->memberInfoState;
	}
	// Get Participant's or Observer's (if $i=0) Info State after auth()
	public function getParticipantInfoState($i){
		global $config;
		if($i<0 || $i>$config->REG_PARTICIPANT_NUM) return false;
		return $this->memberInfoState[$i];
	}
	// Get Observer's Info State after auth()
	public function getObserverInfoState(){
		return $this->memberInfoState[0];
	}
	
	public function auth($checkPW=false){
		require_once 'class.Member.php';
		$tmp=array(new Observer(NULL),new Participant(NULL));
		
		$stm=($this->db->prepare(
			'SELECT '.$this->SQLforSession($checkPW)
			.' FROM '.$this->TABLE
			.' LEFT JOIN '.$tmp[0]->TABLE.' ON '.$tmp[0]->TABLE.'.'.Observer::ROW_TEAM_ID.'='.$this->TABLE.'.'.self::ROW_ID
			.' LEFT JOIN '.$tmp[1]->TABLE.' ON '.$tmp[1]->TABLE.'.'.Participant::ROW_TEAM_ID.'='.$this->TABLE.'.'.self::ROW_ID
			.' WHERE '.$this->TABLE.'.'.($checkPW?self::ROW_EMAIL.' = :e AND '.$this->TABLE.'.'.self::ROW_PW.' = :pw':self::ROW_ID.' = :i')
		));
		if($checkPW){
			$stm->bindValue(':e',$this->email);
			$stm->bindValue(':pw',$this->pw);
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
		$stm=$this->db->prepare('UPDATE '.$this->TABLE.' SET '.self::ROW_PW.'=:n WHERE '.self::ROW_ID.'=:i AND '.self::ROW_PW.'=:o');
		$stm->bindValue(':i',$this->id,PDO::PARAM_INT);
		$stm->bindValue(':o',$oldPassword);
		$stm->bindValue(':n',$this->pw);
		$stm->execute();
		return $stm->rowCount();
	}
	
	public function update(){ // For Admin
		$row=$this->rowArray(true,true,array(self::ROW_EMAIL, self::ROW_PW));
		$stm=$this->db->prepare('UPDATE '.$this->TABLE
			.' SET '.	self::equal($row)
			.' WHERE '.self::ROW_ID.'=:i');
		
		$stm->bindValue(':i',$this->id,PDO::PARAM_INT);
		$this->bindValue($stm,$row);
		
		return $stm->execute();
	}
	
	public function add(){
		$row=$this->rowArray(false,false,array(
			self::ROW_EMAIL,self::ROW_PW,self::ROW_TEAM_NAME,
			self::ROW_INSTITUTION,self::ROW_UNIVERSITY,self::ROW_COUNTRY
		));
		
		$stm=$this->db->prepare($this->insert($row));
		$this->bindValue($stm,$row);
		
		$stm->execute();
		$this->id=$this->db->lastInsertId();
		return $this->id;
	}

	public function updateInfo(){ // For Participant
		require_once 'class.State.php';
		$this->team_state=State::ST_EDITABLE;
		
		$row=array_merge($this->rowArray(true,false,array(self::ROW_EMAIL)),array(self::ROW_TEAM_STATE=>':s'));
		$stm=$this->db->prepare('UPDATE '.$this->TABLE
			.' SET '.	self::equal($row)
			.' WHERE '.self::ROW_ID.'=:i');
		
		$stm->bindValue(':i',$this->id,PDO::PARAM_INT);
		$this->bindValue($stm,$row);
		$stm->bindValue(':s',$this->team_state,PDO::PARAM_INT);
		
		$stm->execute();
		return $stm->rowCount();
	}
	
	public function updatePostReg(){ //for participant
		require_once 'class.State.php';
		
		$row=array_merge($this->rowArray(false,true),array(self::ROW_POST_REG_STATE=>':s'));
		$stm=$this->db->prepare('UPDATE '.$this->TABLE
			.' SET '.	self::equal($row)
			.' WHERE '.self::ROW_ID.'=:i');
		
		$stm->bindValue(':i',$this->id,PDO::PARAM_INT);
		$this->bindValue($stm,$row);
		$stm->bindValue(':s',State::ST_EDITABLE,PDO::PARAM_INT);
		
		$stm->execute();
		$this->team_state=State::ST_EDITABLE;
		return $stm->rowCount();
	}
	
	public function setState($st){
		switch($st){
			case self::ROW_PAY_STATE;
			case self::ROW_TEAM_STATE:
			case self::ROW_POST_REG_STATE:
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
	
	public function getList($type=''){
		global $config;
		if($type==self::ROW_TEAM_STATE){
			require_once 'class.Member.php';
			$t=array(new Observer($this->db), new Participant($this->db));
			$sql='SELECT '.self::row(array(
					$this->TABLE.'.'.self::ROW_ID=>self::ROW_ID,
					$this->TABLE.'.'.self::ROW_TEAM_NAME=>self::ROW_TEAM_NAME,
					$this->TABLE.'.'.self::ROW_INSTITUTION=>self::ROW_INSTITUTION,
					$this->TABLE.'.'.self::ROW_UNIVERSITY=>self::ROW_UNIVERSITY,
					$this->TABLE.'.'.self::ROW_COUNTRY=>self::ROW_COUNTRY,
					'COUNT(*)'=>'c'
				))
				.' FROM '.$this->TABLE
				.' INNER JOIN '.$t[0]->TABLE
					.' ON '.$t[0]->TABLE.'.'.Observer::ROW_TEAM_ID.'='.$this->TABLE.'.'.self::ROW_ID
				.' INNER JOIN '.$t[1]->TABLE
					.' ON '.$t[1]->TABLE.'.'.Participant::ROW_TEAM_ID.'='.$this->TABLE.'.'.self::ROW_ID
				.' WHERE '.$this->TABLE.'.'.self::ROW_TEAM_STATE.'=:s OR '
					.$t[0]->TABLE.'.'.Observer::ROW_INFO_STATE.'=:s OR '
					.$t[1]->TABLE.'.'.Participant::ROW_INFO_STATE.'=:s'
				.' GROUP BY '.$this->TABLE.'.'.self::ROW_ID
				//.' HAVING c='.$config->REG_PARTICIPANT_NUM
				.' ORDER BY '.$this->TABLE.'.'.self::ROW_TEAM_NAME.', '.$this->TABLE.'.'.self::ROW_INSTITUTION.', '
					.$this->TABLE.'.'.self::ROW_INSTITUTION.', '.$this->TABLE.'.'.self::ROW_UNIVERSITY.', '
					.$this->TABLE.'.'.self::ROW_COUNTRY
			;
			unset($t);
		}else{
			$sql=$type;
			switch($type){
				case self::ROW_ARRIVE_TIME: // All data approved
					$sql=self::ROW_POST_REG_STATE;
				case self::ROW_PAY_STATE:
				case self::ROW_POST_REG_STATE:break;
				default: $type=''; $sql='';
			}
			$sql='SELECT '
				.self::row(
					self::ROW_ID, self::ROW_TEAM_NAME,
					self::ROW_INSTITUTION, self::ROW_UNIVERSITY,
					self::ROW_COUNTRY
				)
				.' FROM '.$this->TABLE
				.($type!=''?' WHERE '.$type.'=:s':'')
				.' ORDER BY '.self::ROW_TEAM_NAME.', '.self::ROW_INSTITUTION.', '
					.self::ROW_INSTITUTION.', '.self::ROW_UNIVERSITY.', '.self::ROW_COUNTRY
			;
		}
		$stm=$this->db->prepare($sql);
		if($type!='') $stm->bindValue(':s',
			$type==self::ROW_ARRIVE_TIME?State::ST_OK:State::ST_WAIT,
			PDO::PARAM_INT);
		$stm->execute();
		return $stm->fetchAll(PDO::FETCH_CLASS,__CLASS__,array($this->db));
	}
	
	// Route methods
	public function countRoute(){
		$stm=$this->db->prepare('SELECT '.self::ROW_ROUTE.' AS r, COUNT('.self::ROW_ROUTE.') AS c FROM '.$this->TABLE.' GROUP BY '.self::ROW_ROUTE);
		$stm->execute();
		$a=array_fill(0,$this->getRoute(true),0);
		while($row=$stm->fetch(PDO::FETCH_ASSOC))
			$a[$row['r']]=$row['c'];
		return $a;
		//SELECT COUNT(DISTINCT gender) FROM 
	}
	public function maxRoute(){
		global $config;
		return ceil(($config->REG_MAX_TEAM)/($this->getRoute(true)));
	}
	public function getRoute($count=false){
		global $config;
		if($count) return 1+substr_count($config->INFO_ROUTE,"\n");
		$a=explode("\n",$config->INFO_ROUTE);
		foreach($a as $k=>$v)
			$a[$k]=trim($v);
		return $a;
	}
	
	protected function rowArray($withUniv=false,$withPostReg=false,$row=array()){
		if($withUniv)
			$row=array_merge($row,array(
				self::ROW_INSTITUTION,
				self::ROW_UNIVERSITY,
				self::ROW_COUNTRY,
				self::ROW_ADDRESS,
				self::ROW_PHONE
			));
		if($withPostReg)
			$row=array_merge($row,array(
				self::ROW_ARRIVE_BY,
				self::ROW_ARRIVE_TIME,
				self::ROW_DEPART_BY,
				self::ROW_DEPART_TIME,
		
				self::ROW_ROUTE
			));
		$rows=array();
		foreach($row as $k)
			if(array_key_exists($k,$this->rows)) $rows[$k]=$this->rows[$k];
		return $rows;
	}
	protected function bindValue(PDOStatement $stm,$row){
		foreach($row as $k=>$v){
			switch($k){
				case self::ROW_ARRIVE_TIME:
				case self::ROW_DEPART_TIME:
					$stm->bindValue($v,$this->$k?$this->$k:NULL);
					break;
				case self::ROW_ROUTE:
					$stm->bindValue($v,$this->$k,PDO::PARAM_INT);
					break;
				default:
					$stm->bindValue($v,$this->$k);
			}
		}
	}
	// Miscellenous Function
	public function routeForm(){
		global $config;
		$d=func_num_args()>0?func_get_arg(0):false;
		
		if(is_numeric($this->route)) $this->route=intval($this->route);
		$cr=$this->countRoute();
		$mx=$this->maxRoute();
		
		ob_start();?><div><label class="require">Route of Chiang Mai Tour</label>
<?php
		  foreach(explode("\n",$config->INFO_ROUTE) as $k=>$v):
			$v=trim($v);?>
<input name="route" type="radio" id="route_<?=$k?>" value="<?=$k?>"<? if($this->route===$k):?> checked="CHECKED"<? endif; if($d||$cr[$k]>=$mx):?> disabled="disabled"<? endif;?>><label for="shirt_size_<?=$k?>"><?=$v?> (<?=$cr[$k].'/'.$mx?>)</label>
<? endforeach;?></div><?php
		return ob_get_clean();
	}
	public static function country(){
		if(func_num_args()>0) $c=func_get_arg(0);
		elseif(isset($_REQUEST['country'])) $c=$_REQUEST['country'];
		else $c='';
		$d=func_num_args()>1?func_get_arg(1):false;
			
		ob_start();
		?>
<select name="country" id="country"<? if($d):?> disabled="disabled"<? endif;?>>
       <?php
		foreach(json_decode(file_get_contents('country.json')) as $i){
			?>
	<option value="<?=$i->name?>"<? if($c==$i->name):?> selected="selected"<? endif;?>><?=$i->name?></option>
            <?php
		}
		?>
</select>
       <?php
		return ob_get_clean();
	}
}
?>