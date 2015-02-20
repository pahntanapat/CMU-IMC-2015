<?php
require_once 'config.inc.php';
require_once 'class.SesAdm.php';
require_once 'class.Team.php';
require_once 'class.State.php';

$sess=SesAdm::check();
if(!$sess) Config::redirect('admin.php','you are not log in.');

function fullList(PDO $db, $msg=''){
	require_once 'class.Member.php';
	$arr=array(
		'title'=>Participant::ROW_TITLE,
		'firstname'=>Participant::ROW_FIRSTNAME,
		'middlename'=>Participant::ROW_MIDDLENAME,
		'lastname'=>Participant::ROW_LASTNAME,
		'gender (0 = female, 1 = male)'=>Participant::ROW_GENDER,
		'team\'s name'=>Team::ROW_TEAM_NAME,
		'medical school'=>Team::ROW_INSTITUTION,
		'university'=>Team::ROW_UNIVERSITY,
		'country'=>Team::ROW_COUNTRY
	);
	ob_start();?>
<ul class="tabs" data-tab>
 <li class="tab-title active"><a href="#panel1">Teams</a></li>
 <li class="tab-title"><a href="#panel2">Advisors</a></li>
 <li class="tab-title"><a href="#panel3">Participants</a></li>
</ul>
<div class="tabs-content">
 <div class="content active" id="panel1"><?php $tmp=new Team($db); echo teamList($tmp);?></div>
 <div class="content" id="panel2"><?php $tmp=new Observer($db); echo Config::toTable($tmp->getList(),$arr);?></div>
 <div class="content" id="panel3"><?php $tmp=new Participant($db); echo Config::toTable($tmp->getList(),$arr);?></div>
</div>
    <?php
	return ob_get_clean();
	
	/*require_once 'config.inc.php';
	return Config::toTable($adm->getList(), array('Student ID'=>'student_id', 'Nickname'=>'nickname'));*/
}

function teamList(Team $t, $type='', $msg=''){
	ob_start();
	if($msg!==false):?>
<div id="msgTable" class="alert-box info"><?=$msg?><br/><small>Last update: <?=date('Y-m-d H:i:s e')?></small></div>
<? endif;?>
<table width="100%" border="0">
  <tr>
    <? if($type==''):?><th scope="col">Delete</th><? endif;?>
    <th scope="col">Team's name</th>
    <th scope="col">Medical school</th>
    <th scope="col">University/College</th>
    <th scope="col">Country</th>
  </tr>
  <? foreach($t->getList($type) as $row):?><tr>
    <? if($type==''):?><td><input name="del[]" type="checkbox" class="del" value="<?=$row->id?>" title="delete"></td><? endif;?>
    <td><a href="<?=$_SERVER['PHP_SELF']?>?id=<?=$row->id?>" target="_blank" class="teamDialog"><?=$row->team_name?></a></td>
    <td><?=$row->institution?></td>
    <td><?=$row->university?></td>
    <td><?=$row->country?></td>
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
	
	require_once 'class.Member.php';
	require_once 'class.UploadImage.php';
	
	$t=new Team($db);
	$t->id=$id;
	$t->load();
	
	$p=array();
	for($i=1;$i<=$config->REG_PARTICIPANT_NUM;$i++){
		$p[$i]=new Participant($db);
		$p[$i]->team_id=$id;
		$p[$i]->part_no=$i;
		$p[$i]->load();
	}
	
	$o=new Observer($db);
	$o->team_id=$id;
	$o->load();
	
	$i=new UploadImage();
	$i->team_id=$id;
	
	ob_start();
	?>
<h3>Tn</h3>
<ul class="inline-list">
  <li><a href="admin_approve_info.php" target="_blank">Approve Infomation in step 1</a></li>
  <li><a href="admin_pay.php" target="_blank">Approce Transactions</a></li>
  <li><a href="admin_approve_post_reg.php" target="_blank">Approve Infomation in step 2</a></li>
</ul>
<ul class="tabs" data-tab>
<li class="tab-title active"><a href="#statusTab">Overall of Status</a></li>
<li class="tab-title"><a href="#teamTab">Team</a></li>
<li class="tab-title"><a href="#obsTab">Advisor</a></li>
<? for($i=1;$i<=$config->REG_PARTICIPANT_NUM;$i++):?>
<li class="tab-title"><a href="#partTab<?=$i?>"><?=Config::ordinal($i)?> Participant</a></li>
<? endfor;?>
</ul>
<div class="tabs-content">
<div class="content active" id="statusTab">
<h3>Status</h3>
<ol>
  <li><?=State::img($t->team_state)?> Team &amp; Institution information</li>
  <li><?=State::img($o->info_state)?> Advisor's infomation</li>
  <? for($i=1;$i<=$config->REG_PARTICIPANT_NUM;$i++):?>
 <li><?=State::img($p[$i]->info_state)?> <?=Config::ordinal($i)?>  participant's infomation</li>
  <? endfor;?>
  <li><?=State::img($t->pay_state)?> Upload Transaction</li>
  <li><?=State::img($t->post_reg_state)?> Select route &amp; upload team's picture &amp; update arrival time</li>
</ol>
</div>
<div class="content" id="teamTab"><form action="admin_team_list.php" class="updateInfoForm"><fieldset class="require">
  <legend>Team's information</legend>
  <div>
  <label class="require">Email for overall contact
    <input name="email" type="email" required id="email" value="<?=$t->email?>"<?=Config::readonly($r)?>>
  </label>
</div>
  <div>
  <label class="require">Password
    <input name="pw" type="text" required id="pw" value="<?=$t->pw?>"<?=Config::readonly($r)?>>
  </label>
</div>
<div>
  <label class="require">Team's name
    <input name="team_name" type="text" required id="team_name" value="<?=$t->team_name?>" maxlength="40"<?=Config::readonly($r)?>>
  </label>
</div>
<div>
  <label class="require">Institution
    <input name="institution" type="text" id="institution" value="<?=$t->institution?>" required<?=Config::readonly($r)?>>
  </label>
</div><div>
  <label class="require">University
    <input name="university" type="text" id="university" value="<?=$t->university?>" required<?=Config::readonly($r)?>>
  </label>
</div><div>
  <label class="require">Address
    <textarea name="address" rows="5" id="address"<?=Config::readonly($r)?>><?=$t->address?></textarea>
  </label>
</div><div>
  <label class="require">Country
    <?=Team::country($t->country,$r)?>
  </label>
</div><div>
  <label class="require">Institution's telephone number
    <input name="phone" type="phone" id="phone" value="<?=$t->phone?>" placeholder="(with country code) +XXxxxxxx"<?=Config::readonly($r)?>>
  </label>
</div>
</fieldset><fieldset><legend>Routes of Chiang Mai Tour</legend>
<p><a href="../cm_tour.html" target="_blank"><i class="fa fa-map-marker"></i> Information of routes of Chiang Mai Tour</a></p>
<?=$t->routeForm()?>
</fieldset>
<fieldset><legend>Type/Time of Arrival &amp; Departure</legend>
<div>
  <label class="require">Arrival time
    <input name="arrive_time" type="text" id="arrive_time" value="<?=$t->arrive_time?>">
  </label>
</div>
<div>
  <label class="require">Expected type of arrival (to Chiang Mai) <small> Airplane, Bus, Train, Van</small>
    <input name="arrive_time" type="text" id="arrive_time" value="<?=$t->arrive_by?>"></label>
</div>
<div>
  <label>Departure time
    <input name="arrive_time" type="text" id="arrive_time" value="<?=$t->depart_time?>">
  </label>
</div>
<div>
  <label>Expected type of departure (from Chiang Mai) <small>Airplane, Bus, Train, Van</small>
    <input name="arrive_time" type="text" id="arrive_time" value="<?=$t->depart_by?>">
  </label>
</div>
</fieldset>
<? if(!$r):?><fieldset><legend>Save</legend><div>
  <button type="submit" name="save" id="save" value="save">save</button>
  <button type="reset" name="cancel" id="button" value="cancel">cancel</button></div></fieldset><? endif;?></form></div>
<div class="content" id="obsTab"><form action="admin_team_list.php" class="updateInfoForm"></form></div> 
<? for($i=1;$i<=$config->REG_PARTICIPANT_NUM;$i++):?>
<div class="content" id="partTab<?=$i?>"><form action="admin_team_list.php" class="updateInfoForm"></form></div>
<? endfor;?>
</div>
<?php
	return ob_get_clean();
}
?>
