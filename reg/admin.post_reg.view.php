<?php
require_once 'config.inc.php';
require_once 'class.SesAdm.php';

$sess=SesAdm::check();
if(!$sess) Config::redirect('admin.php','you are not log in.');
if(!$sess->checkPMS(SesAdm::PMS_PARTC)) Config::redirect('home.php','you don\'t have permission here.');

require_once 'class.Message.php';
function showPostReg(Message $msg, $message=''){
	ob_start();
		
	require_once 'class.State.php';
	require_once 'class.Team.php';
	require_once 'class.UploadImage.php';

	$t=new Team($msg->getDB());
	$t->id=$msg->team_id;
	$t->load();
	
	$route=$t->getRoute();
	$count=$t->countRoute();
	$mx=$t->maxRoute();
	
	$img=new UploadImage();
	$img->team_id=$t->id;
	?>
<h3>Team's name: <?=$t->team_name?><small>
<a href="admin.team.php?id=<?=$t->id?>" target="_blank">Edit their information</a></small></h3>
<div class="panel"><h4>Routes of Chiang Mai Tour<small>
<a href="admin.config.php" target="_blank">Edit route</a></small></h4>
  <ul><? foreach($route as $k=>$v):?>
  <li><b><?=$v?></b> routeCode = <?=$k?> (<?=$count[$k][0].'/'.$mx?> teams)</li><? endforeach;?></ul>
</div>
<h4>Team's information</h4>
<?=$img->toImgTeamPhoto()?>
<table width="100%" border="0">
  <tr>
    <th scope="col" class="require">Form</th>
    <th scope="col">Detail</th>
  </tr>
  <tr>
    <th scope="row" class="require">Route</th>
    <td><?=$route[$t->route]?> (routeCode = <?=$t->route?>)</td>
  </tr>
  <tr>
    <th scope="row" class="require">Arrival time</th>
    <td><?=$t->arrive_time?></td>
  </tr>
  <tr>
    <th scope="row" class="require">Method to Arrive</th>
    <td><?=$t->arrive_by?></td>
  </tr>
  <tr>
    <th scope="row">Depart time</th>
    <td><?=$t->depart_time?></td>
  </tr>
  <tr>
    <th scope="row">Method to Depart</th>
    <td><?=$t->depart_by?></td>
  </tr>
</table>
<h4>Ticket</h4><?=$img->toImgTicket().$msg->load()->toForm('admin.post_reg.php?id='.$t->id, array(State::ST_WAIT, State::ST_OK, State::ST_NOT_PASS), $t->post_reg_state)?>
<div id="msg" class="alert-box alert radius"><?=$message?></div>
<?php
	return ob_get_clean();
}
?>