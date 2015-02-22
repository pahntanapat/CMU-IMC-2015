<?php
require_once 'config.inc.php';
require_once 'class.SesAdm.php';

$sess=SesAdm::check();
if(!$sess) Config::redirect('admin.php','you are not log in.');
if(!$sess->checkPMS(SesAdm::PMS_PARTC)) Config::redirect('home.php','you don\'t have permission here.');

require_once 'admin_approve_info.view.php';
require_once 'class.Team.php';

$db=$config->PDO();

require_once 'class.SKAjax.php';
$ajax=new SKAjax();

if(Config::isPost()){
	require_once 'class.Message.php';
	
}elseif(isset($_GET['id'])){
	
}else{
	require_once 'admin_team_list.view.php';
	$ajax->msgID='teamList';
	$ajax->message=teamList(new Team($db), 'admin_approve_info.php', Team::ROW_TEAM_STATE);
}
if(Config::isAjax()) Config::JSON($ajax,true);
?>