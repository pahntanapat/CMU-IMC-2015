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
	
	public function reset(){
		return $this->db->prepare('DELETE FROM '.$this->TABLE)->execute();
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
		$args=func_get_args(); //For PHP<5.3.0
		return ' '.implode(', ',$args).' '; // eg. row(self::ROW_ID,....), this function will return id,...
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
	  * Get rows and count it
	  * @param ROW of table, ...
	  * @return PDOStatement
	  */
	public function countField($field='*', $renderField=false){
		if($field=='*' || strlen(trim($field))==0)
			return  $this->db->query('SELECT COUNT(*) AS c FROM '.$this->TABLE)->fetchColumn(0);

		if($renderField===false) $renderField=$field;
		return $this->db->query('SELECT '.$renderField.', COUNT('.$field.') AS c FROM '.$this->TABLE.' GROUP BY '.$field);
	}
	
	public static function IN($list){
		if(count($list)==0) return ' IN(0) ';
		return ' IN ('.implode(',',array_fill(0,count($list),'?')).')';
	}
}
?>