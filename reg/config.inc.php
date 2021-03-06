<?php
// require_once 'class.MyConfig.php'; // comment this line to avoid late state binding problem debug for PHP version <5.3.0
class Config extends stdClass /* MyConfig*/{
	const // Config variables
		DB_USER="root", DB_PW="DB_PW",
		DB_NAME="imc", DB_HOST='localhost',
		UPLOAD_FOLDER='images',
		
		REG_START_REG='2015-01-01 00:00:00',
		REG_END_REG='2015-02-21 00:00:00',
		REG_START_PAY='2015-01-01 00:00:00',
		REG_END_PAY='2015-02-18 00:00:00',
		REG_END_INFO='2015-02-28 00:00:00',
		
		REG_PARTICIPANT_NUM=4,
		REG_MAX_TEAM=50,
		
		REG_PAY_PER_PART_US=100,
		REG_PAY_PER_PART_TH=3200,
		
		INFO_ROUTE="Doi Suthep\nMae Hia\nMae Rim",
		INFO_SHIRT_SIZE="SS\nS\nM\nL\nXL\nXXL"
		;
	
	// For PHP version that < 5.3.0 (not support last state binding)
	// If PHP version >=5.3.0 you can remove this line until ..
	public static $SAVE_CONFIG='config.save.php';
	
	public function __get($n){
		if(defined("self::$n")) return constant("self::$n");
		else throw new Exception('No property named '.$n.' in config');
	}
	public function save(){
		return file_put_contents(self::$SAVE_CONFIG, '<?php return '.var_export($this,true).' ; ?>');
	}
	public function reset(){
		return file_put_contents(self::$SAVE_CONFIG, '<?php return new '.get_called_class().'(); ?>');
	}
	public static function __set_state($prop){
		$obj=new self();
		foreach($prop as $k=>$v)
			$obj->$k=$v;
		return $obj;
	}
	public static function load(){
		try{
			return require self::$SAVE_CONFIG;
		}catch(Exception $e){
			$obj=new self();
			$obj->reset();
			return $obj;
		}
	}
	// Remove until this line;
	
	public function PDO($returnNullIfError=false){
		try{
			$dbh=new PDO(
				"mysql:host=".$this->DB_HOST.";dbname=".$this->DB_NAME.";", // DSN
				$this->DB_USER,$this->DB_PW
			);
			$dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		}catch(Exception $e){
			if($returnNullIfError && !$dbh) return $dbh;
			else throw $e;
		}
		return $dbh;
	}
	
	// Form processing
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
	public static function isDate($date,&$msg){
		if(preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date)){
			return true;
		}else{
			$msg='Date format must be YYYY-MM-DD only, for example, 2015-01-31, or 1990-12-01.';
			return false;
		}
	}
	public static function checkCAPTCHA(){
		if(!isset($_POST['captcha'])) return false;
		require_once('securimage/securimage.php');
		$cp=new Securimage();
		return ($cp->check($_POST['captcha']));
	}
	public static function checkPW($password,&$msg){
		if(preg_match_all('/^[[:alnum:]_:;]{6,32}$/',$password,$msg)==0){
			$msg='Password must be 6-32 characters with letters, digits, _ (underscore), : (colon), or ; (semicolon).';
			return false;
		}else return true;
	}
	public static function checkEmail($email, &$msg){
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			$msg='The pattern of your email is wrong.';
			return false;
		}else return true;
	}
	
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
	
	public static function trimArray($arr,$exceptKey=array()){
		foreach($arr as $k=>$v){
			if(!in_array($k,$exceptKey) && is_string($v))
				$arr[$k]=trim($v);
		}
		return $arr;
	}
	// Protocol method & export data
	public static function isAjax(){ //Check the request if it is from AJAX.
		return isset($_GET['ajax']);
	}
	public static function isPost(){ //Check the request if its method is post.
		return isset($_POST)?count($_POST)>0:false;
	}
	public static function isFile(){ //Check the request if file(s) is sent.
		return isset($_FILES)?count($_FILES)>0:false;
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
	
	public static function JSON($json=false,$exit=true){
		header("Content-type: text/json;charset=utf-8");
		if($json===false) return;
		elseif($exit) exit($json);
		else echo $json;
	}
	public static function HTML(){
		header("Content-type: text/html;charset=utf-8");
	}
	
	public static function e(Exception $e){
		return "<pre>Some problem occurs in the registration system. Please contact administrator.\n\n$e</pre>";
	}
	
	
	//Miscellenous function
	public static function ordinal($num,$supScript=true){
		$sup='th';
		if(!($num>=11 && $num<=13)){
			switch($num%10){
				case 1:
					$sup='st';
					break;
				case 2:
					$sup='nd';
					break;
				case 3:
					$sup='rd';
					break;
			}
		}
		return $num.($supScript?"<sup>".$sup."</sup>":$sup);
	}
	
	public static function readonly(){
		if(func_num_args()>0)
			if(!func_get_arg(0)) return '';
		return ' readonly="readonly"';				
	}
	
	/**
	 *$data is array of objects or arrays that contains data
	 *$keyPair=array('head row'=>'key of $data', ...);
	 *$withDel=add delete row
	 */
	public static function toTable($data, $keyPair, $withDel=false, $msg=false){
		ob_start();
		if($msg!==false):?>
<div id="msgTable" class="alert-box info"><?=$msg?><br/><small>Last update: <?=date('Y-m-d H:i:s e')?></small></div>
<? endif;?>
<table width="100%" border="0">
  <tr>
<? if($withDel):?><th scope="col">Delete</th>
<?php
		endif;
		foreach(array_keys($keyPair) as $k):?>
<th scope="col"><?=$k?></th>
<?		endforeach;?>
	</tr>
<?php
		foreach($data as $i):
			$i=(array) $i;
?>
	<tr>
 		<? if($withDel):?><td><input name="del[]" type="checkbox" class="del" value="<?=$i['id']?>" title="delete"></td><? endif;?>
		<? foreach($keyPair as $v):?>
    	<td><?=isset($i[$v])?$i[$v]:''?></td>
		<? endforeach;?>
    </tr>
<? endforeach;?>
</table>
<?php
		return ob_get_clean();
	}
	
}
$config=Config::load();
?>