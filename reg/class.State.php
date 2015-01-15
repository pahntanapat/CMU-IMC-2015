<?php
class State{
	const
		ST_LOCKED=0, ST_EDITABLE=1, ST_CONFIRM=2, ST_B_PASS=4,
		ST_WAIT=2,				// ST_CONFIRM
		ST_NOT_PASS=3,	// ST_EDITABLE|ST_CONFIRM
		ST_PASS=6,				// ST_CONFIRM|ST_B_PASS
		ST_OK=7				// ST_CONFIRM|ST_B_PASS| ST_EDITABLE
		;
	public static function is($st1,$st2){
		return ($st1&$st2)!=0;
	}
}
?>