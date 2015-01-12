<?php
class Session{
	public $id=0, $pms, $nick='root', $std_id=0,$sid='';
	const SESS_ASSC='mahidol_admin',
		PMS_ADMIN=1,
		PMS_STUDENT=2,
		PMS_AUDIT=4,
		PMS_QUIZ=8,
		PMS_STD_ID=16,
		PMS_WEB_MTR=32;
	public function __construct(){
		self::start();
		$this->pms=self::PMS_ADMIN|self::PMS_WEB_MTR;
		$this->sid=session_id();
	}
	public function __toString(){
		return $this->std_id.': '.$this->nick;
	}
	public function load(){
		foreach(get_class_vars(__CLASS__) as $k=>$v)
			$this->$k=isset($_SESSION[self::SESS_ASSC][$k])?$_SESSION[self::SESS_ASSC][$k]:$v;
		return $this;
	}
	public function save(){
		$_SESSION[self::SESS_ASSC]=get_object_vars($this);
		return $this;
	}
	public function renew(){
		if(!session_regenerate_id(true)) return false;
		$this->sid=session_id();
		return $this->save();
	}
	public function check(){
		return isset($_SESSION[self::SESS_ASSC])?$this->sid==session_id() && $this->sid==$_SESSION[self::SESS_ASSC]['sid']:false;
	}
	public function isAuth($pms){
		return self::isPMS($this->pms,$pms);
	}
	public static function start(){
		return session_id()==''?session_start():true;
	}
	public static function destroy(){
		self::start();
		unset($_SESSION);
		return session_destroy();
	}
	public static function isLogIn($needLogIn=NULL,$pms=true,self $sess=NULL){
		if($sess===NULL) $sess=new self();
		$t=$sess->load()->check();
		require_once 'config.inc.php';
		require_once $GLOBALS['_ROOT'].'/login/json_ajax.php';
		if($t && $needLogIn===true && !$sess->isAuth($pms)){
			if(isset($_GET['ajax'])){
				$r=new jsonAjax();
				$r->addAction(jsonAjax::REDIRECT,'index.php');
				$r->export();
			}else{
				header('Location: index.php');
				exit('You are forbidden here.');
			}
		}else	if(!$t && $needLogIn===true){
			if(isset($_GET['ajax'])){
				$r=new jsonAjax();
				$r->addAction(jsonAjax::REDIRECT,'login.php');
				$r->export();
			}else{
				header('Location: login.php');
				exit('You must log in.');
			}
		}elseif($t && $needLogIn===false){
			if(isset($_GET['ajax'])){
				$r=new jsonAjax();
				$r->addAction(jsonAjax::REDIRECT,'index.php');
				$r->export();
			}else{
				header('Location: index.php');
				exit('You have logged in.');
			}
		}else return $t;
	}
	public static function pms($pms){
		switch($pms){
			case self::PMS_ADMIN: return 'จัดการ Admin (กรรมการการแข่งขัน)';
			case self::PMS_AUDIT: return 'ตรวจสอบหลักฐานการโอนเงิน';
			case self::PMS_QUIZ: return 'ตรวจ Quiz ของทีมอิสระ, อนุมัติทีมทุกประเภทให้ผ่านเข้ารอบ';
			case self::PMS_STD_ID: return 'จัดการรหัสผู้แข่งขัน และห้องสอบ';
			case self::PMS_STUDENT: return 'แก้ไข, ตรวจสอบหลักฐานการสมัคร';
			case self::PMS_WEB_MTR: return "ตั้งค่าระบบ แก้ไขข้อมูลเว็บ ฐานข้อมูล และระบบรับสมัคร";
			default: return '';
		}
	}
	public static function isPMS($myPMS,$testPMS){
		return $testPMS===true?$testPMS:($myPMS&$testPMS)!=0;
	}
}
?>