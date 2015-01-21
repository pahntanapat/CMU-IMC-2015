<?php
//5.2.6-1+lenny16
require_once 'class.MyConfig.php';
class Config extends MyConfig{
	const
		DB_USER="root", DB_PW="053721872",
		DB_NAME="imc", DB_HOST='localhost',
		
		REG_START_REG='2014-12-29 00:00:00',
		REG_END_REG='2014-12-29 23:59:59',
		REG_START_PAY='2014-12-29 23:59:59',
		REG_END_PAY='2014-12-29 23:59:59',
		REG_END_INFO='2014-12-30 00:00:00',
		
		REG_PARTICIPANT_NUM=4,
		REG_MAX_TEAM=50,
		
		REG_PAY_PER_PART_US=100,
		REG_PAY_PER_PART_TH=3200,
		
		OBSRV_OPEN='2014-12-29 00:00:00',
		OBSRV_CLOSED='2014-12-30 23:59:59',
		OBSRV_PAY_PER_OBSRVR=200
		;
	public function PDO($returnNullIfError=false){
		$dbh=new PDO(
			"mysql:host=".$this->DB_HOST.";dbname=".$this->DB_NAME.";", // DSN
			$this->DB_USER,$this->DB_PW
		);
		if($returnNullIfError && !$dbh) return $dbh;
		$dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		return $dbh;
	}
	
	
	//Miscellenous function
	/**
	  *public static function assocToObjProp($assoc,$obj)
	  *@return Object whose properties is set to be array items.
	  *
	  */
	public static function assocToObjProp($assoc,$obj){
		foreach($obj as $k=>$v)
			$obj->$k=isset($assoc[$k])?$assoc[$k]:$v;
		return $obj;
	}
	/**
	  *public static function isBlank(mixed Array, mixed index1, mixed index2, ...)
	  *@return true if there is not any arguments.
	  *@return true if there is one NULL argument, false if it is not NULL.
	  *@return true if there are one or more NULL index in Array, false if not.
	  */
	public static function isBlank(){
		switch(func_num_args()){
			case 0: return true;
			case 1: return func_get_arg(0)==NULL;
			default:
				$arr=func_get_arg(0);
				for($i=1;$i<func_num_args();$i++)
					if($arr[func_get_arg($i)]==NULL) return true;
				return false;
		}
	}
	
	public static function isAjax(){ //Check the request if it is from AJAX.
		return isset($_GET['ajax']);
	}
	public static function isPost(){ //Check the request if its method is post.
		return isset($_POST)?count($_POST)>0:false;
	}
	public static function redirect($url='/',$exitMsg=false){ //Redirect to $url with/without exit message & AJAX request
		if(self::isAjax()){
			require_once 'class.SKAjax.php';
			$json=new SKAjax();
			if($exitMsg) $json->alert($exitMsg);
			$json->addAction(SKAjax::REDIRECT,$url);
			self::JSON($json,true);
		}else{
			header('Location: '.$url);
			if($exitMsg!==false) exit($exitMsg);
		}
	}
	public static function checkCAPTCHA(){
		if(!isset($_POST['captcha'])) return false;
		require_once('securimage/securimage.php');
		$cp=new Securimage();
		return ($cp->check($_POST['captcha']));
	}
	
	public static function JSON($json=false,$exit=false){
		header("Content-type: text/json;charset=utf-8");
		if($json===false) return;
		elseif($exit) exit($json);
		else echo $json;
	}
	public static function HTML(){
		header("Content-type: text/html;charset=utf-8");
	}
	
	public static function checkPW($password,&$match){
		return preg_match_all('/^[[:alnum:]_:;]{6,32}$/',$password,$match);
	}
	
	public static function e(Exception $e){
		return "<pre>$e</pre>";
	}
}
$config=Config::load();
?>