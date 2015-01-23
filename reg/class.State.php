<?php
class State{
	const
		ST_LOCKED=0, ST_EDITABLE=1, ST_CONFIRM=2, ST_B_PASS=4,
		ST_WAIT=2,				// ST_CONFIRM
		ST_NOT_PASS=3,	// ST_EDITABLE|ST_CONFIRM
		ST_PASS=6,				// ST_CONFIRM|ST_B_PASS
		ST_OK=7				// ST_CONFIRM|ST_B_PASS| ST_EDITABLE
		;
	public static function is($st1,$st2,$strTime=true){
		return (($st1&$st2)!=0)&&(strtotime($strTime,time())<=time());
	}
	public static function css($state){
		switch($state){
			case self::ST_LOCKED: return 'locked';
			case self::ST_EDITABLE: return 'edittable';
			case self::ST_WAIT: return 'waiting';
			case self::ST_NOT_PASS: return 'not_pass';
			case self::ST_PASS: return 'pass';
			case self::ST_OK: return 'ok';
		}
	}
	public static function img($state){
		$state=self::css($state);
		return "<img src=\"/reg/image/$state.png\" alt=\"$state\" />";
	}
}
?>