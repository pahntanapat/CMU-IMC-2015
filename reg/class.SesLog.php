<?php
require_once 'class.Session.php';
class SesLog extends Session{
	public static function check($returnObj=true,$autoWrite=true){
		return self::checkSession(new self($returnObj&&$autoWrite));
	}
}
?>