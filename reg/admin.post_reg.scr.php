<?php
require_once 'config.inc.php';
require_once 'class.SesAdm.php';

$sess=SesAdm::check();
if(!$sess) Config::redirect('admin.php','you are not log in.');
if(!$sess->checkPMS(SesAdm::PMS_PARTC)) Config::redirect('home.php','you don\'t have permission here.');

$db=$config->PDO();
require_once 'class.State.php';
require_once 'class.SKAjax.php';
require_once 'class.Team.php';

$ajax=new SKAjax();
$ajax->result=false;

if(Config::isPost()){ // Submit

}elseif(isset($_GET['id'])){ //Show info of team ID: $_GET['id']
	$ajax->msgID='approveForm';
	$ajax->message=showPay($_GET['id'], $sess->id, new Team($db), new Message($db), new UploadImage());
}else{ // Show all table
	require_once 'admin.team.view.php';
	$ajax->msgID='teamList';
	$ajax->message=teamList(new Team($db), 'admin.post_reg.php', Team::ROW_POST_REG_STATE);
}
if(Config::isAjax()) Config::JSON($ajax);
?>