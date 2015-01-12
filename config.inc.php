<?php
$conf=getConfig();
//Root of This site
$_ROOT=$conf['ROOT'];
$_PATH=$conf['PATH'];
// Schedule last day of ...
$_END_REGISTER=strtotime($conf['END_REGISTER'],time());
$_END_EDIT_INFO=strtotime($conf['END_EDIT_INFO'],$_END_REGISTER);
$_START_PAY=strtotime($conf['START_PAY'],time());
$_END_PAY=strtotime($conf['END_PAY'],$_START_PAY);
$_START_PRINT=strtotime($conf['START_PRINT'],$_END_PAY);

$DB_HOST=$conf['DB_HOST'];
$DB_NAME=$conf['DB_NAME'];
$DB_USER=$conf['DB_USERNAME'];
$DB_PW=$conf['DB_PW'];
// PDO config at main_function.php
function newPDO(){
	global $DB_HOST, $DB_NAME, $DB_USER, $DB_PW;
	$dbh=new PDO(
		'mysql:host='.$DB_HOST.';dbname='.$DB_NAME.';', // DSN
		$DB_USER, //Username
		$DB_PW //Password
	);
	$dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	return $dbh;
}
// Mail config at mail.php
$SMTP_HOST= $conf['SMTP_HOST'];
$SMTP_PORT= $conf['SMTP_PORT'];
$SMTP_USER= $conf['SMTP_USER'];
$SMTP_PASS= $conf['SMTP_PW'];
$SMTP_SECR= $conf['SMTP_SECR'];
	
$MAIL_FROM=$conf['MAIL_FROM'];
$MAIL_REPLY_TO=$conf['MAIL_REPLY_TO'];
unset($conf);
//Normal Error Message
function errMsg($e,$sql=NULL,$report="กรุณาแจ้งกรรมการการแข่งขันด่วน"){
	if($sql)
		return "Error! ระบบเกิดความผิดพลาดเนื่องจาก\n$e\nSQL = $sql\n$report";
	else
		return "Error! ระบบเกิดความผิดพลาดเนื่องจาก\n$e\n$report";
}
function configFile($custom=true){
	return $_SERVER['DOCUMENT_ROOT'].'/'.($custom?'config.inc':'config.default').'.php';
}
function getConfig($original=false){
	return ($original)?(require configFile(false)):array_merge((require configFile(false)),(require configFile()));
}
function saveConfig($newConfig=NULL){
	if(is_array($newConfig)){
		$conf=getConfig(true);
		foreach($newConfig as $k=>$v)
			if($v==$conf[$k]) unset($newConfig[$k]);
		$val=$newConfig;
	}else $val=array();
	return file_put_contents(configFile(),'<?php return '.var_export($val,true).';?>');
}
?>