<?php
class SKeasySQL{
	const ROW_ID='id';
	protected $db;
	
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
			foreach(func_get_args() as $k=>$v)
				$args[]=$k.' AS '.$v;
			return ' '.implode(', ',$args).' ';
		}
		return ' '.implode(', ',func_get_args()).' '; // eg. row(self::ROW_ID,....), this function will return id,...
	}
}
?>