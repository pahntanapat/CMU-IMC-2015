<?php
class SKAjaxOriginal{
	private $arrayAction;
	public $result, $message;
	const ALERT="alert", REDIRECT="redirect", EVALUTE="eval", FOCUS="focus",
		SET_TEXT="setText", SET_HTML="setHTML", SET_VAL="setVal",
		RELOAD_CAPTCHA="reloadCAPTCHA", SCROLL_TO="scrollTo",
		RESET_FORM="resetForm", RECALL_SERVER="recall";
	
	public function __construct($json=NULL){
		$this->arrayAction=array();
		$this->result=false;
		$this->message='';
		if($json!=NULL)
			self::fromJSON($json);
	}
	public function __destruct(){
		unset($this->arrayAction);
	}
	public function getAction($id=NULL){
		if($id==NULL)
			return $this->arrayAction;
		elseif($id<count($this->arrayAction))
			return $this->arrayAction[$id];
		else return NULL;
	}
	public function setAction($id,$act,$how=NULL){
		$arr=array('act'=>$act);
		switch($act){
			case self::SET_HTML:
			case self::SET_TEXT:
			case self::SET_VAL:
				$arr['selector']=self::getOption($how,'selector');
			case self::ALERT:
				$arr['message']=self::getOption($how,'message');break;
			case self::REDIRECT:
				$arr['url']=self::getOption($how,'url');break;
			case self::EVALUTE:
				$arr['script']=self::getOption($how,'script');break;
			case self::RECALL_SERVER:
				$arr['call']=(boolean) self::getOption($how,'call');break;
			case self::RESET_FORM:
			case self::RELOAD_CAPTCHA: break;
			case self::SCROLL_TO:
			case self::FOCUS:
				$arr['selector']=self::getOption($how,'selector');break;
		}
		$this->arrayAction[$id]=$arr;
		return count($this->arrayAction);
	}
	
	public function addAction($act,$how=NULL){
		return $this->setAction(count($this->arrayAction),$act,$how);
	}
	
	public function addHtmlTextVal($action=jsonAjax::SET_HTML,$selector="body",$msg=""){
		return $this->addAction($action,array('selector'=>$selector,'message'=>$msg));
	}
	public function addShowDialog($dialog,$form='form'){
		return $this->addAction(self::EVALUTE,'$('.$form.').DialogAndSubmit('.$dialog.');');
	}
	public function alert($message){
		return $this->addAction(self::ALERT,$message);
	}
	
	public function removeAction($id=-1){
		if($id<0){
			$this->arrayAction=array();
			return 0;
		}
		unset($this->arrayAction[$id]);
		$this->arrayAction=array_values($this->arrayAction);
		return count($this->arrayAction);
	}
	
	public function toJSON(){
		return json_encode(array(
			'result'=>$this->result,
			'message'=>$this->message,
			'action'=>$this->arrayAction
		));
	}
	public function fromJSON($json,$assoc=false){
		$r=json_decode($json,$assoc);
		$this->result=$r['result'];
		$this->message=$r['message'];
		$this->arrayAction=$r['action'];
		unset($r);
	}
	public function __toString(){
		return $this->toJSON();
	}
	public function countAction(){
		return count($this->arrayAction);
	}
	
	public static function getOption($arr,$key=0){
		if(is_array($arr)){
			if(array_key_exists($key,$arr)) return $arr[$key];
			elseif(array_key_exists(0,$arr)) return $arr[0];
			else return current($arr);
		}elseif(is_object($arr)){
			if(isset($arr->{$key})) return $arr->{$key};
			else return $arr->__toString();
		}else return $arr;
	}
}
 
class SKAjax extends SKAjaxOriginal{
	public $msgID='msg';
	/**
	  *@return <div id="msg">{$this->message}</div>
	  *for export message to web browser that not support AJAX
	  */
	public function toMsg(){
		return <<<HTML
<div id="{$this->msgID}">{$this->message}</div>
HTML;
	}
	
	/**
	  *@return SKAjax's JSON
	  *add Command that set inner HTML of tag id msg (#msg) to $this->message
	  */
	public function toJSON($option=0,$depth=512){
		if(strlen($this->message)>0)
			$this->addHtmlTextVal(self::SET_HTML,'#'.$this->msgID,$this->message);
		$this->message=$this->msgID;
		return parent::toJSON($option,$depth);
	}

	/**
	  *@return $this
	  *add Command that set value of input to default value
	  */
	public function setFormDefault($assoc=NULL, $except=array()){
		if(!is_array($assoc))
			$assoc=$_POST;
		require_once 'class.State.php';
		foreach($assoc as $k=>$v)
			if(!in_array($k,$except)) $this->addHtmlTextVal(SKAjax::SET_VAL,'input[name=\''.$k.'\']',$v);
		return $this;
	}
}
?>