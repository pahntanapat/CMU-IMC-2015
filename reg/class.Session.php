<?php
/*interface session{
	const SESSION_NAME='session', ID_EXP=1200;
	public function load($re=false);
	public function write();
	public function _check();
	public function changeID($force=false);
	public static function start();
	public static function destroy();
	public static function check($returnObj=true,$autoWrite=true);
}*/
abstract class Session{
	protected $SESSION_NAME=__CLASS__;
	const ID_EXP=1200;
	public $autoWrite=true;
	protected $sid=false, $time=0;
	
	public function __construct($autoWrite=true){
		self::start();
		$this->load()->changeID();
		$this->autoWrite=$autoWrite;
	}
	
	public function load($re=false){
		foreach($this as $k=>$v)
			$this->$k=isset($_SESSION[$this->SESSION_NAME][$k])?$_SESSION[$this->SESSION_NAME][$k]:$v;
		return $re?get_object_vars($this):$this;
	}
	
	public function write(){
		$this->sid=session_id();
		$this->time=time();
		$_SESSION[self::SESSION_NAME]=get_object_vars($this);
		return $this;
	}
	
	public function changeID($force=false){
		if($this->checkSession() && ($force||($this->time+self::ID_EXP<time()))){
			session_regenerate_id(true);
			$this->sid=session_id();
		}
		return $this;
	}
	
	public function __destruct(){
		if($this->autoWrite==true)
			$this->write();
	}
	
	abstract public function checkSession();
	
//	abstract public static function check();
	
	public static function start(){
		return (session_id()=='')? session_start():true;
	}
	
	public static function destroy(){
		if(self::start()){
			unset($_SESSION);
			return session_destroy();
		}
	}
	
	/*
	public function _check(){
		return $this->sid==session_id();
	}	
	protected static function checkSession(Session $session,$returnObj=true){
		return $session->_check()?($returnObj?$session:true):false;
	}
	public static function check($returnObj=true,$autoWrite=true){
		return self::checkSession(new self($returnObj&&$autoWrite));
	}
	*/
}
?>