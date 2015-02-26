<?php
require_once 'class.Session.php';
class SesAdm extends Session{
	protected $SESSION_NAME=__CLASS__;
	const
	// Admin Permission
		PMS_ADMIN=1, // Can Add Delete Edit Admin
		PMS_WEB=2, // Can Edit web properties
		PMS_PARTC=4, // Can Add Delete Edit Check Participant
		PMS_AUDIT=8, // Can Check Payment
		PMS_GM=16 // Can view participant info
		;
		
	public $id,$nickname, $student_id, $pms;
	
	public function checkSession(){
		return $this->sid==session_id() && $this->nickname!=NULL && $this->student_id!==NULL && is_int($this->pms);
	}
	public function checkPMS($pms){
		return self::isPMS($this->pms,$pms);
	}
	
	public static function check($pms=true,$returnObj=true,$autoWrite=true){
		$sess=new self($autoWrite&&$returnObj);
		return ($sess->checkSession() &&$sess->checkPMS($pms))?($returnObj?$sess:true):false;
	}
	public static function isPMS($myPMS,$testPMS){
		return $testPMS===true?true:($myPMS&$testPMS)!=0;
	}
	public static function checkbox($pms=0, $disable=false){
		ob_start();
		$me=new ReflectionClass(__CLASS__);
		foreach($me->getConstants() as $k=>$v):
			if(strpos($k,'PMS_')===false) continue;
			?><input name="permission[<?=$v?>]" type="checkbox" value="<?=$v?>"<? if(($pms&$v)!=0):?> checked="checked"<? endif;if($disable):?> disabled="disabled"<? endif;?> />
            <label for="pms_<?=$v?>"><?=self::pms($v)?></label><br/><?php
		endforeach;
		return ob_get_clean();
	}
	public static function pms($pms){
		switch($pms){
			case self::PMS_ADMIN: return 'จัดการ Admin (กรรมการการแข่งขัน)';
			case self::PMS_AUDIT: return 'ตรวจสอบหลักฐานการโอนเงิน';
		//	case self::PMS_OBSRV: return 'แก้ไข, ตรวจสอบหลักฐานผู้สังเกตการ';
			case self::PMS_GM: return 'ฝ่ายอื่นๆ สามารถเข้าถึงข้อมูลผู้เข้าร่วมได้';
			case self::PMS_PARTC: return 'แก้ไข, ตรวจสอบหลักฐานการสมัคร';
			case self::PMS_WEB: return "ตั้งค่าระบบ แก้ไขข้อมูลเว็บ ฐานข้อมูล และระบบรับสมัคร";
			default: return '';
		}
	}
}
?>