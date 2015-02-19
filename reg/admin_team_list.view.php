<?php
require_once 'config.inc.php';
require_once 'class.SesAdm.php';
require_once 'class.Team.php';

$sess=SesAdm::check();
if(!$sess) Config::redirect('admin.php','you are not log in.');

function teamList(Team $t, $type='', $msg=''){
	require_once 'class.State.php';
	ob_start();
	?>
<div id="msgTable" class="alert-box info"><?=$msg?><br/><small>Last update: <?=date('Y-m-d H:i:s e')?></small></div>
<table width="100%" border="0">
  <tr>
    <? if($type==''):?><th scope="col">Delete</th><? endif;?>
    <th scope="col">Team's name</th>
    <th scope="col">Medical school</th>
    <th scope="col">University/College</th>
    <th scope="col">Country</th>
    <th scope="col">Status of payment</th>
  </tr>
  <? foreach($t->getList($type) as $row):?><tr>
    <? if($type==''):?><td><input name="del[]" type="checkbox" class="del" value="<?=$row->id?>" title="delete"></td><? endif;?>
    <td><a href="<?=$_SERVER['PHP_SELF']?>?id=<?=$row->id?>" target="_blank" class="teamDialog"><?=$row->team_name?></a></td>
    <td><?=$row->institution?></td>
    <td><?=$row->university?></td>
    <td><?=$row->country?></td>
    <td><?=State::img($row->pay_state)?></td>
  </tr><? endforeach;?>
</table>
<?php
	return ob_get_clean();
}

function teamInfo($id,$editable){
	global $config;
	$db=$config->PDO();
	$r=!$editable;
	unset($editable);
	
	require_once 'class.Participant.php';
	require_once 'class.UploadImage.php';
	
	$t=new Team($db);
	$p=new Participant($db);
	$o=new Observer($db);
	$i=new UploadImage();
	
	$t->id=$id;
	$p->team_id=$id;
	$o->team_id=$id;
	$i->team_id=$id;
	
	$t->load();
	$p->load();
	$o->load();
	
	ob_start();
	?>
<h3>Tn</h3>
<ul class="inline-list">
  <li><a href="admin_approve_info.php" target="_blank">Approve Infomation in step 1</a></li>
  <li><a href="admin_pay.php" target="_blank">Approce Transactions</a></li>
  <li><a href="admin_approve_post_reg.php" target="_blank">Approve Infomation in step 2</a></li>
</ul>
<ul class="tabs" data-tab> <li class="tab-title active"><a href="#panel1">Tab 1</a></li> <li class="tab-title"><a href="#panel2">Tab 2</a></li> <li class="tab-title"><a href="#panel3">Tab 3</a></li> <li class="tab-title"><a href="#panel4">Tab 4</a></li> </ul> <div class="tabs-content"> <div class="content active" id="panel1"> <p>This is the first panel of the basic tab example. You can place all sorts of content here including a grid.</p> </div> <div class="content" id="panel2"> <p>This is the second panel of the basic tab example. This is the second panel of the basic tab example.</p> </div> <div class="content" id="panel3"> <p>This is the third panel of the basic tab example. This is the third panel of the basic tab example.</p> </div> <div class="content" id="panel4"> <p>This is the fourth panel of the basic tab example. This is the fourth panel of the basic tab example.</p> </div> </div>
<?php
	return ob_get_clean();
}
?>
