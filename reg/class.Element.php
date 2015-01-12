<?php
class Element extends ArrayObject{
	public function val(){
		if(func_num_args()<1) return NULL;
		$k=func_get_arg(0);
		if(isset($this->$k)) return $this->$k;
		if(isset($_POST[$k])) return $_POST[$k];
		if(isset($_GET[$k])) return $_GET[$k];
		if(isset($_REQUEST[$k])) return $_REQUEST[$k];
		if(func_num_args()>1) return func_get_arg(1);
		return NULL;
	}
	public function offsetGet($i){
		return $this->offsetExists($i)? parent::offsetGet($i):NULL;
	}
	public function __get($i){
		return $this->offsetGet($i);
	}
}
?>