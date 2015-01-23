<?php
require_once 'config.inc.php';
require_once 'class.SKeasySQL.php';
require_once 'class.Observer.php';
class Participant extends Observer{
	const ROW_PART_NO='part_no', ROW_EM_CNT='emerg_contact', ROW_STD_Y='std_y';
	
	public $part_no,$emerg_contact,$std_y;
	public $TABLE='participant_info';
	
	protected function rowArray($postReg=false){
		global $config;
		$merge=array();
		if(!$postReg){
			$merge[self::ROW_EM_CNT]=':emrg';
			$merge[self::ROW_STD_Y]=':std_y';
		}
		if($this->part_no>0 && $this->part_no<=$config->REG_PARTICIPANT_NUM)
			$merge[self::ROW_PART_NO]=':part_no';
		return array_merge($merge,parent::rowArray($postReg));
	}
	
	protected function bindValue(PDOStatement $stm,$postReg=false){
		global $config;
		parent::bindValue($stm,$postReg);
		if($this->part_no>0 && $this->part_no<=$config->REG_PARTICIPANT_NUM)
			$stm->bindValue(':part_no',$this->part_no,PDO::PARAM_INT);
		if(!$postReg){
			$stm->bindValue(':emrg',$this->emerg_contact);
			$stm->bindValue(':std_y',$this->std_y,PDO::PARAM_INT);
		}
	}
	
	public function getList(){
		return $this->getPDOStm()->fetchAll(PDO::FETCH_CLASS,__CLASS__,array($this->db));
	}
}
?>