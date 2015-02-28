<?php
class State{
	const
		ST_NOT_START=-1,
		ST_TIME_UP=-2,
		
		ST_LOCKED=0, ST_EDITABLE=1, STB_CONFIRM=2, STB_PASS=4, //Basic State
		ST_WAIT=2,				// STB_CONFIRM
		ST_NOT_PASS=3,	// ST_EDITABLE|STB_CONFIRM
		ST_PASS=6,				// STB_CONFIRM|STB_PASS
		ST_OK=7			// STB_CONFIRM|STB_PASS| ST_EDITABLE
		
		;
	public static function is($st1,$st2,$startTime=false,$endTime=false){
		return (($st1&$st2)!=0)
			&&($startTime?strtotime($startTime,time())<=time():true)
			&&($endTime?strtotime($endTime,time())>time():true);
	}
	public static function inTime($state,$startTime,$endTime,$toClass=false){
		if(self::is($state, self::ST_EDITABLE)){
			if(strtotime($startTime,time())>=time()) $state=self::ST_NOT_START;
			elseif(strtotime($endTime,time())<time()) $state=$state==self::ST_OK?self::ST_PASS:self::ST_TIME_UP;
		}
		return $toClass?self::toClass($state):$state;
	}
	public static function toClass($state){
		switch($state){
			case self::ST_TIME_UP:
			case self::ST_NOT_START:
			case self::ST_LOCKED: return 'locked';
			case self::ST_EDITABLE: return 'editable';
			case self::ST_WAIT: return 'waiting';
			case self::ST_NOT_PASS: return 'not_pass';
			case self::ST_PASS: return 'pass';
			case self::ST_OK: return 'ok';
			default: return '';
		}
	}
	public static function toSQL($col){
		$sql='';
		$r=new ReflectionClass(__CLASS__);
		foreach($r->getConstants() as $state=>$v){
			if(strpos($state,'ST_')===false) continue;
			$sql.=' WHEN '.$v.' THEN "'.self::toClass($v).' (code: '.$v.')" ';
		}
		return ' (CASE '.$col.' '.$sql.' ELSE "" END) AS '.$col.' ';
	}
	
	public static function toDivClass($state){
		switch($state){
			case self::ST_TIME_UP:
			case self::ST_NOT_START:
			case self::ST_LOCKED: return 'secondary';
			case self::ST_EDITABLE: return 'info';
			case self::ST_WAIT: return 'warning';
			case self::ST_NOT_PASS: return 'alert';
			case self::ST_PASS:
			case self::ST_OK: return 'success';
			default: return '';
		}
	}
	
	public static function toHTML($state, $addInfo=NULL){
		$html=self::img($state, false).'This section ';
		switch($state){
			case self::ST_NOT_START:
				$html.='is "not opened" to be edited. Please visit this page at "'.(is_array($addInfo)?$addInfo[0]:$addInfo).'".';
				break;
			case self::ST_TIME_UP:
				$html=self::img($state, false).'This section\'s deadline is "'.(is_array($addInfo)?$addInfo[1]:$addInfo).'", so you are not allowed to edit any information.';
				break;
			case self::ST_LOCKED:
				$html.='is "locked". You are not allowed to edit any information.';
				break;
			case self::ST_EDITABLE:
				$html.='is "editable". You are allowed to edit your information.';
				break;
			case self::ST_WAIT:
				$html.='is "waiting" for approval.';
				break;
			case self::ST_NOT_PASS:
				$html=self::img($state, false).'The information given in this section is not permitted  because of some reason.';
				break;
			case self::ST_PASS:
				$html.='is successfully completed. If you want to edit any information, please contact administrator.';
				break;
			case self::ST_OK:
				$html=self::img($state, false).'The information given in this section is "permitted" and "editable", so you can edit it anytime.';
				break;
			//default: return '';
		}
		return "<div data-alert class=\"alert-box ".self::toDivClass($state)." radius\">".$html."<a href=\"#\" class=\"close\">&times;</a></div>";
	}
	public static function img($state, $color=true){
		if($color){
			$state=self::toClass($state);
			return $state?"<i class=\"fa ".$state." fa-lg\"></i> ":NULL;
		}
		switch($state){
			case self::ST_TIME_UP:
			case self::ST_NOT_START:
			case self::ST_LOCKED: $state='lock';break;
			case self::ST_EDITABLE: $state='pencil';break;
			case self::ST_WAIT: $state='refresh';break;
			case self::ST_NOT_PASS: $state='times';break;
			case self::ST_PASS: $state='check';break;
			case self::ST_OK: $state='check-square-o';break;
			default: return '';
		}
		return $state?"<i class=\"fa fa-".$state." fa-lg\"></i> ":NULL;
	}
	
	public static function css($withHeader=false,$withTag=false){
		if($withHeader) header("Content-type: text/css;charset=utf-8");
		ob_start();
		if(!$withTag) echo '@charset "utf-8";'.PHP_EOL;
		/*
		*/
		$mainCSS=<<<CSS
font-family: FontAwesome;display: inline-block;padding-right: 0.25em;
CSS;
		$list=array(
			self::ST_LOCKED=>array("f023",'#999'),
			self::ST_EDITABLE=>array("f040",'#39F'),
			self::ST_WAIT=>array("f021",'#F60'),
			self::ST_NOT_PASS=>array("f00d",'#F00'),
			self::ST_PASS=>array("f00c",'#0F0'),
			self::ST_OK=>array("f046",'#C0F')
		);
		foreach($list as $k=>$v):
?>i.<?=self::toClass($k)?>:before, li.<?=self::toClass($k)?>>a:before{content:"\<?=$v[0]?>";color:<?=$v[1]?>;<?=$mainCSS?>}<?php
		endforeach;
		return $withTag&&!$withHeader?"<style>".ob_get_clean()."</style>":ob_get_clean();
	}
	
	public static function stateList(){
		ob_start();?>
        <h3 id="stateList">The Status of each steps of the CMU-IMC Registration System</h3>
        <div><?php	
		$tmp=array('[start datetime]', '[end datetime]');
		$r=new ReflectionClass(__CLASS__);
		foreach($r->getConstants() as $state=>$v){
			if(strpos($state,'ST_')===false) continue;
			echo self::toHTML($v, $tmp).PHP_EOL;
		}
		?></div><?php	
		return ob_get_clean();
	}
}
if(isset($_GET['css']))
	if($_GET['css']==1)	exit(State::css(true));
?>