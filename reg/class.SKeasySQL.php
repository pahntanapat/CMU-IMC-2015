<?php
abstract class SKeasySQL{
	const ROW_ID='id';
	
	public $id;
	protected $db, $TABLE;
	
	public function __construct($db){
		$this->setPDO($db);
	}
	
	public function setPDO($db){
		$this->db=$db;
	}
	
	public function beginTransaction(){
		return $this->db->beginTransaction();
	}
	
	public function commit(){
		return $this->db->commit();
	}
	
	public function rollBack(){
		return $this->db->rollBack();
	}
	
	public function inTransaction(){
		return $this->db->inTransaction();
	}

	public static function row(){
		if(func_num_args()==0) return ' * '; // If there are no argument, this function will return *.
		if(func_num_args()==1 && is_array(func_get_arg(0))){
			// If argument is array eg. row(array(self::ROW_ID=>'ID',..)
			// This function will return id AS ID, ....
			$args=array();
			foreach(func_get_arg(0) as $k=>$v)
				$args[]=$k.' AS '.$v;
			return ' '.implode(', ',$args).' ';
		}
		return ' '.implode(', ',func_get_args()).' '; // eg. row(self::ROW_ID,....), this function will return id,...
	}
	
	public static function equal($rowList,$table=false){ //$rowList=array('row'=>':arg', ...)
		$args=array();
		foreach($rowList as $k=>$v)
			$args[]=($table===false?'':$table.'.').$k.' = '.$v;
		return ' '.implode(', ',$args).' ';
	}
	
	public function insert($rowList, $table=false){
		if($table===false) $table=$this->TABLE;
		return 'INSERT INTO '.$table.' ('.implode(', ',array_keys($rowList)).') VALUES ('.implode(', ',$rowList).')';
	}
	
	abstract public function load();
	
	abstract public function getList();
	/**
	  * Load object from $_POST if the page was submit
	  * In other way, Load from DB
	  *
	  */
	public function submitLoad(){
		require_once 'config.inc.php';
		if(Config::isPost())
			return Config::assocToObjProp($_POST,$this);
		else
			return $this->load();
	}
	
	public static function IN($list){
		return ' IN ('.implode(',',array_fill(0,count($list),'?')).')';
	}
}
?>