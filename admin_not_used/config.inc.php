<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/login/config.inc.php';
function checkCAPTCHA(){
	global $_ROOT;
	if(!isset($_POST['captcha'])) return false;
	require_once($_ROOT.'/securimage/securimage.php');
	$cp=new Securimage();
	return ($cp->check($_POST['captcha']));
}
class State{
	const STATE_NOT_PASS=-3, STATE_PASS=-2, STATE_WAIT=-1, STATE_LOCKED=0,
		STATE_NOT_FINISHED=1, STATE_EDITTABLE=2, STATE_MUST_CHANGE=3;
		const
			NOT_SHOWN=0,
			PAGE_TEAM_INFO=10, 
				SECT_INFO_STD_1=11,
				SECT_INFO_STD_2=12,
				SECT_INFO_STD_3=13,
				SECT_INFO_TEAM=14,
			PAGE_UPLOAD_TSP=20,
				SECT_TSP_STD_1=21,
				SECT_TSP_STD_2=22,
				SECT_TSP_STD_3=23,
			PAGE_QUIZ=30,
			PAGE_CONFIRM=40,
			PAGE_PAY=50,
			PAGE_RECEIVE_ID=60;
		
	public static function getIcon($state=self::LOCKED){
		$folder="../login/image/";
		switch($state){
			case self::STATE_NOT_PASS: return <<<HTML
<img src="{$folder}wrong.png" alt="ไม่ผ่าน">
HTML;
			case self::STATE_PASS: return <<<HTML
<img src="{$folder}correct.png" alt="ผ่าน">
HTML;
			case self::STATE_WAIT: return <<<HTML
<img src="{$folder}refresh.png" alt="รอการตรวจสอบ">
HTML;
			case self::STATE_LOCKED: return <<<HTML
<img src="{$folder}lock.png" alt="lock">
HTML;
			case self::STATE_NOT_FINISHED: return <<<HTML
<img src="{$folder}q.png" alt="กรุณากรอกข้อมูล">
HTML;
			case self::STATE_EDITTABLE: return <<<HTML
<img src="{$folder}pencil.png" alt="แก้ไข">
HTML;
			case self::STATE_MUST_CHANGE: return <<<HTML
<img src="{$folder}error.png" alt="กรุณาแก้ไขด่วน">
HTML;
		}
	}
	public static function getPage($page=self::PAGE_TEAM_INFO){
		$folder="../login/image/";
		switch($page){
			case self::PAGE_TEAM_INFO: return "หน้ากรอกข้อมูลทีม";
			case self::PAGE_UPLOAD_TSP: return "หน้าอัพโหลดปพ.1";
			case self::PAGE_QUIZ: return "หน้า quiz";
			case self::PAGE_CONFIRM: return "หน้ายืนยันข้อมูล";
			case self::PAGE_PAY: return "หน้าอัพโหลดใบโอนเงิน";
			case self::PAGE_RECEIVE_ID: return "หน้าพิมพ์ใบห้องสอบ";
			case self::SECT_INFO_TEAM:
				return "ส่วนกรอกข้อมูลทีม";
			case self::SECT_INFO_STD_1:
			case self::SECT_INFO_STD_2:
			case self::SECT_INFO_STD_3:
				return "ส่วนกรอกข้อมูลผู้แข่งขันคนที่ ".($page-self::PAGE_TEAM_INFO);
			case self::SECT_TSP_STD_1:
			case self::SECT_TSP_STD_2:
			case self::SECT_TSP_STD_3:
				return "ส่วนอัพโหลดใบปพ.1ผู้แข่งขันคนที่ ".($page-self::PAGE_UPLOAD_TSP);
			case self::NOT_SHOWN:	return "ไม่แสดงในหน้าใดๆ";
			default: return "หน้าแรก";
		}
	}
	public static function pageList($group=true,$prefix=''){
		$rf=new ReflectionClass(__CLASS__);
		$r=array();
		if($group){
			$g=array();
			foreach($rf->getConstants() as $k=>$v)
				if(strpos($k,'PAGE_')!==false || strpos($k,'NOT_SHOWN')!==false)	$g[$v]=self::getPage($v);
		}
		foreach($rf->getConstants() as $k=>$v){
			if(strpos($k,'PAGE_')===false && strpos($k,'SECT_')===false && strpos($k,'NOT_SHOWN')===false) continue;
			if($group)
				$r[$g[$v-($v%10)]][$prefix.$k]=$v;
			else
				$r[$prefix.$k]=$v;
		}
		return $r;
	}
}
?>