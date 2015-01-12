<?php
require_once 'class.SKeasySQL.php';
require_once 'config.inc.php';
class Admin extends SKeasySQL{
	const TABLE='admin',
		
		ROW_ID='id', ROW_STD_ID='student_id',
		ROW_PW='password', ROW_NICK='nickname',
		ROW_PERMISSION='permission';
		
	public $id, $student_id, $password, $nickname, $permission;
	protected $db;
	public function __construct($db){
		$this->setPDO($db);
	}
	public function setPDO($db){
		$this->db=$db;
	}
	/**
	  *Check if the given student_id & password of this person is admin
	  *@Return True if this persion is admin, False if not.
	  */
	public function auth(){
		global $config;
		if($this->student_id==$config->DB_USER && $this->password==$config->DB_PW){
			require_once 'class.SesAdm.php';
			// If DB is set.
			if($this->db){
				// Check if there are no admin who can edit admin and web prop
				$stm=($this->db->prepare('SELECT COUNT(*) FROM '.self::TABLE.
					' WHERE '.self::ROW_PERMISSION.'& ? != 0 AND '.self::ROW_PERMISSION.'& ? !=0'));
				$stm->bindValue(1,SesAdm::PMS_ADMIN,PDO::PARAM_INT);
				$stm->bindValue(2,SesAdm::PMS_WEB,PDO::PARAM_INT);
				$stm->execute();
				if($stm->fetchColumn()>0) return false;
			}
			$this->permission=SesAdm::PMS_ADMIN|SesAdm::PMS_WEB;
			$this->nickname='Root';
			$this->student_id=0;
			return true;
		}
		
		$stm=($this->db->prepare('SELECT'.self::row(self::ROW_ID,self::ROW_NICK,self::ROW_PERMISSION)
			.'FROM '.self::TABLE.' WHERE '.self::ROW_STD_ID.' = :stid AND '.self::ROW_PW.' = :pw'));
		$stm->bindValue(':stid',$this->student_id);
		$stm->bindValue(':pw',$this->password);
		$stm->execute();
		
		if($stm->rowCount()>0){
			$row=$stm->fetch(PDO::FETCH_OBJ);
			$this->id=$row->id;
			$this->nickname=$row->nickname;
			$this->permission=$row->permission;
			return true;
		}else return false;
	}
	
	public function add(){
		if(is_array($this->permission)) $this->permission=array_sum($this->permission);
		$stm=$this->db->prepare('INSERT INTO '.self::TABLE
			.'('.self::row(self::ROW_STD_ID, self::ROW_PW, self::ROW_NICK, self::ROW_PERMISSION)
			.') VALUES (:s,:pw,:n,:p)');
		$stm->bindValue(':s',$this->student_id);
		$stm->bindValue(':pw',$this->password);
		$stm->bindValue(':n',$this->nickname);
		$stm->bindValue(':p',$this->permission,PDO::PARAM_INT);
		$stm->execute();
		$this->id=$this->db->lastInsertId();
		return $this->id;
	}
	public function update(){
		if(is_array($this->permission)) $this->permission=array_sum($this->permission);
		$stm=$this->db->prepare('UPDATE '.self::TABLE
			.' SET '.self::ROW_STD_ID.'=:s, '.self::ROW_PW.'=:pw, '.self::ROW_NICK.'=:n, '.self::ROW_PERMISSION.'=:p'
			.' WHERE '.self::ROW_ID.'=:i');
		$stm->bindValue(':i',$this->id,PDO::PARAM_INT);
		$stm->bindValue(':s',$this->student_id);
		$stm->bindValue(':pw',$this->password);
		$stm->bindValue(':n',$this->nickname);
		$stm->bindValue(':p',$this->permission,PDO::PARAM_INT);
		return $stm->execute();
	}
	public function updateInfo(){
		$stm=$this->db->prepare('UPDATE '.self::TABLE
			.' SET'.self::ROW_STD_ID.'=:s, '.self::ROW_NICK.'=:n WHERE '.self::ROW_ID.'=:i');
		$stm->bindValue(':i',$this->id,PDO::PARAM_INT);
		$stm->bindValue(':s',$this->student_id);
		$stm->bindValue(':n',$this->nickname);
		$stm->execute();
		return $stm->rowCount();
	}
	
	public function changePW($oldPassword){
		$stm=$this->db->prepare('UPDATE '.self::TABLE.' SET'.self::ROW_PW.'=:n '.' WHERE '.self::ROW_ID.'=:i AND '.self::ROW_PW.'=:o');
		$stm->bindValue(':i',$this->id,PDO::PARAM_INT);
		$stm->bindValue(':o',$oldPassword);
		$stm->bindValue(':n',$this->password);
		$stm->execute();
		return $stm->rowCount();
	}
	
	public function load(){
		$stm=$this->db->prepare('SELECT '.self::row().' FROM '.self::TABLE.' WHERE '.self::ROW_ID.'=:i limit 1');
		$stm->bindValue(':i',$this->id,PDO::PARAM_INT);
		$stm->execute();
		if($stm->rowCount()>0){
			$row=$stm->fetch(PDO::FETCH_OBJ);
			foreach($row as $k=>$v) $this->$k=$v;
		}
		return $stm->rowCount();
	}
	/*
	public function del(){
		$stm=$this->db->prepare('DELETE FROM '.self::TABLE.' WHERE '.self::ROW_ID.'=:i');
		$stm->bindValue(':i',$this->id,PDO::PARAM_INT);
		$stm->execute();
		return $stm->rowCount();
	}
	*/
	public function del($list){
		$stm=$this->db->prepare('DELETE FROM '.self::TABLE
			.' WHERE '.self::ROW_ID.' IN ('
			.implode(',',array_fill(0,count($_POST['del']),'?'))
			.')');
		$stm->execute($_POST['del']);
		return $stm->rowCount();
	}
	
	public function getList(){
		$stm=$this->db->prepare('SELECT '.self::row(self::ROW_ID,self::ROW_STD_ID,self::ROW_NICK).' FROM '.self::TABLE);
		$stm->execute();
		return $stm->fetchAll(PDO::FETCH_CLASS,__CLASS__,array(NULL));
	}
}
?>