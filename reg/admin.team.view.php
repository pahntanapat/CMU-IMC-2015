<?php
require_once 'config.inc.php';
require_once 'class.SesAdm.php';
require_once 'class.Team.php';
require_once 'class.State.php';

$sess=SesAdm::check();
if(!$sess) Config::redirect('admin.php','you are not log in.');

function fullList(PDO $db, $msg=''){
	require_once 'class.Member.php';
	ob_start();?>
<ul class="tabs" data-tab>
 <li class="tab-title active"><a href="#panel1">Teams</a></li>
 <li class="tab-title"><a href="#panel2">Advisors</a></li>
  <li class="tab-title"><a href="#panel3">Distinct Advisors</a></li>
 <li class="tab-title"><a href="#panel4">Participants</a></li>
</ul>
<div class="tabs-content">
 <div class="content active" id="panel1"><?php $tmp=new Team($db); echo teamList($tmp, 'admin.team.php');?></div>
 <div class="content" id="panel2"><?php $tmp=new Observer($db); echo Config::toTable($tmp->getList(true), Observer::forTableRow(true));?></div>
  <div class="content" id="panel3"><?=Config::toTable($tmp->getDistinctList(true), Observer::forTableRow(true));?></div>
 <div class="content" id="panel4"><?php $tmp=new Participant($db); echo Config::toTable($tmp->getList(true), Participant::forTableRow(true));?></div>
</div>
    <?php
	return ob_get_clean();
	
	/*require_once 'config.inc.php';
	return Config::toTable($adm->getList(), array('Student ID'=>'student_id', 'Nickname'=>'nickname'));*/
}

function teamList(Team $t, $link, $type='', $msg=''){
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
    <? if($type==Team::ROW_ARRIVE_TIME):?>
    <th scope="col">Arrival Time</th>
    <th scope="col">Arrival Method</th>
    <th scope="col">Departure Time</th>
    <th scope="col">Departure Method</th>
    <? endif;?>
  </tr>
  <? foreach($t->getList($type) as $row):?><tr>
    <? if($type==''):?>
    <td><input name="del[]" type="checkbox" class="del" value="<?=$row->id?>" title="delete"></td>
	<? endif;?>
    <td><a href="<?=$link?>?id=<?=$row->id?>" target="_blank" class="edit"><?=$row->team_name?></a></td>
    <td><?=$row->institution?></td>
    <td><?=$row->university?></td>
    <td><?=$row->country?></td>
    <? if($type==Team::ROW_ARRIVE_TIME):?>
    <td><?=$row->arrive_time?></td>
    <td><?=$row->arrive_by?></td>
    <td><?=$row->depart_time?></td>
    <td><?=$row->depart_by?></td>
     <? endif;?>
  </tr><? endforeach;?>
</table>
<?php
	return ob_get_clean();
}

function teamInfo($id,$pms, $msg=''){
	global $config;
	$db=$config->PDO();
	require_once 'class.SesAdm.php';
	
	$r=!SesAdm::isPMS($pms, SesAdm::PMS_PARTC);
	
	require_once 'class.Member.php';
	require_once 'class.UploadImage.php';
	
	$t=new Team($db);
	$t->id=$id;
	$t->load();
	
	$m=array();
	
	$m[0]=new Observer($db);
	$m[0]->team_id=$id;
	$m[0]->load();
	
	for($i=1;$i<=$config->REG_PARTICIPANT_NUM;$i++){
		$m[$i]=new Participant($db);
		$m[$i]->team_id=$id;
		$m[$i]->part_no=$i;
		$m[$i]->load();
	}
	
	$img=new UploadImage();
	$img->team_id=$id;
	
	ob_start();
	?>
<h3>Team's name: <?=$t->team_name?></h3>
<ul class="inline-list">
  <li><a href="admin.info.php?id=<?=$t->id?>" target="_blank">Approve Infomation in step 1</a></li>
  <li><a href="admin.pay.php?id=<?=$t->id?>" target="_blank">Approce Transactions</a></li>
  <li><a href="admin.post_reg.php?id=<?=$t->id?>" target="_blank">Approve Infomation in step 2</a></li>
</ul>
<ul class="tabs" data-tab>
<li class="tab-title active"><a href="#statusTab">Overall of Status</a></li>
<li class="tab-title"><a href="#teamTab">Team</a></li>
<? for($no=0;$no<=$config->REG_PARTICIPANT_NUM;$no++):?>
<li class="tab-title"><a href="#partTab<?=$no?>"><? if($no==0):?>Advisor<? else: echo Config::ordinal($no)?> Participant<? endif;?></a></li>
<? endfor;?>
</ul>
<div class="tabs-content">
<div class="content active" id="statusTab">
<h3>Status</h3>
<ol>
  <li><?=State::img($t->team_state)?> Team &amp; Institution information</li>
  <? for($i=0;$i<=$config->REG_PARTICIPANT_NUM;$i++):?>
 <li><?php echo State::img($m[$i]->info_state).' '; if($i>0): echo Config::ordinal($i);?> participant<? else:?>Advisor<? endif;?>'s infomation</li>
  <? endfor;?>
  <li><?=State::img($t->pay_state)?> Upload Transaction</li>
  <li><?=State::img($t->post_reg_state)?> Select route &amp; upload team's picture &amp; update arrival time</li>
</ol>
</div>
<div class="content" id="teamTab">
<form action="admin.team.php?id=<?=$_GET['id']?>" class="updateInfoForm"><fieldset>
  <legend>Team's information</legend>
  <div>
    <label class="require">Email for overall contact
    <input name="email" type="email" required id="email" value="<?=$t->email?>"<?=Config::readonly($r)?>>
  </label>
    <input name="act" type="hidden" id="act" value="team" />
    <input name="id" type="hidden" id="id" value="<?=$t->id?>" />
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
</fieldset>
<fieldset><legend>Routes of Chiang Mai Tour</legend>
<p><a href="../cm_tour.html" target="_blank"><i class="fa fa-map-marker"></i> Information of routes of Chiang Mai Tour</a></p>
<?=$t->routeForm($r)?>
</fieldset>
<fieldset><legend>Type/Time of Arrival &amp; Departure</legend>
<div>
  <label class="require">Arrival time
    <input name="arrive_time" type="text" id="arrive_time" value="<?=$t->arrive_time?>"<?=Config::readonly($r)?>>
  </label>
</div>
<div>
  <label class="require">Expected type of arrival (to Chiang Mai) <small> Airplane, Bus, Train, Van</small>
    <input name="arrive_time" type="text" id="arrive_time" value="<?=$t->arrive_by?>"<?=Config::readonly($r)?>></label>
</div>
<div>
  <label>Departure time
    <input name="arrive_time" type="text" id="arrive_time" value="<?=$t->depart_time?>"<?=Config::readonly($r)?>>
  </label>
</div>
<div>
  <label>Expected type of departure (from Chiang Mai) <small>Airplane, Bus, Train, Van</small>
    <input name="arrive_time" type="text" id="arrive_time" value="<?=$t->depart_by?>"<?=Config::readonly($r)?>>
  </label>
</div>
</fieldset>
<fieldset><legend>Transaction, Photo &amp; Ticket</legend>
<?php
if(SesAdm::isPMS($pms, SesAdm::PMS_AUDIT)):
	echo $img->toImgPay();
?>
<div><label>Delete the transaction <small>If you remove file, the file is permanently romoved.</small></label>
    <input name="del_tsc" type="radio" id="del_tsc_0" value="0" checked="checked" /><label for="del_tsc_0">Keep it</label><input name="del_tsc" type="radio" id="del_tsc_1" value="1" /><label for="del_tsc_1">Delete it</label></div><hr />
<?php
else: echo "<h4>You don't have permission to view the transaction.</h4>";
endif;
echo $img->toImgTeamPhoto().$img->toImgTicket();
if(SesAdm::isPMS($pms, SesAdm::PMS_PARTC)):
?>
<div><label>Delete TEAM's PHOTO <small>If you remove file, the file is permanently romoved.</small></label>
    <input name="del_p" type="radio" id="del_p_0" value="0" checked="CHECKED" /><label for="del_p_0">Keep it</label><input name="del_p" type="radio" id="del_p_1" value="1" /><label for="del_p_1">Delete it</label></div>
<div><label>Delete TICKET <small>If you remove file, the file is permanently romoved.</small></label>
    <input name="del_tk" type="radio" id="del_tk_0" value="0" checked="checked" /><label for="del_tk_0">Keep it</label><input name="del_tk" type="radio" id="del_tk_1" value="1" /><label for="del_tk_1">Delete it</label></div>
<? endif;?>
</fieldset>
<? if(!$r || SesAdm::isPMS($pms, SesAdm::PMS_AUDIT)):?><fieldset class="require"><legend>Save</legend><div>
  <button type="submit" name="save" id="save" value="save">save</button>
  <button type="reset" name="cancel" id="button" value="cancel">cancel</button></div></fieldset><? endif;?>
  </form>
  </div>
<?php
		for($no=0;$no<=$config->REG_PARTICIPANT_NUM;$no++):
		$member=$m[$no];
?>
<div class="content" id="partTab<?=$no?>">
   <form action="admin.team.php?id=<?=$_GET['id']?>" method="post" name="infoForm" class="updateInfoForm">
      <fieldset>
        <legend>General Information</legend>
        <div>
          <label class="require">Title
            <input name="act" type="hidden" id="act" value="part" />
            <input name="id" type="hidden" id="id" value="<?=$member->id?>">
            <input name="part_no" type="hidden" id="part_no" value="<?=$no?>">
			<input name="team_id" type="hidden" id="team_id" value="<?=$member->team_id?>">
            <input name="title" type="text" id="title" value="<?=$member->title?>"<?=Config::readonly($r)?>>
          </label>
        </div>
         <div><label class="require">Firstname
            <input name="firstname" type="text" id="firstname" value="<?=$member->firstname?>"<?=Config::readonly($r)?>>
          </label></div>
        <div>
          <label>Middlename
<input name="middlename" type="text" id="middlename" value="<?=$member->middlename?>"<?=Config::readonly($r)?>>
          </label></div>
           <div>
             <label class="require">Lastname
<input name="lastname" type="text" id="lastname" value="<?=$member->lastname?>"<?=Config::readonly($r)?>>
          </label></div>
<?php
		echo Member::gender($member->gender,$r);
		if($no>0):
?>           <div>
             <label class="require">Medical student year
               <input name="std_y" type="text" id="std_y" value="<?=$member->std_y?>">
          </label></div><? endif;?>
           <div>
             <label class="require">Date of Birth <small>Click on the form to show calendar, and click on title bar of calendar to change month, or double click it to select year.</small>
               <input name="birth" type="date" id="birth" value="<?=$member->birth?>"<?=Config::readonly($r)?>>
          </label></div>
           <div>
             <label class="require">Nationality
               <input name="nationality" type="text" id="nationality" value="<?=$member->nationality?>"<?=Config::readonly($r)?>>
          </label></div>
      </fieldset>
      <fieldset>
        <legend>Contact</legend>
        <div>
          <label>Mobile phone number <small>with country code</small>
            <input name="phone" type="tel" id="phone" placeholder="+xx xxx xxx xxx ..." value="<?=$member->phone?>"<?=Config::readonly($r)?>>
          </label></div>
         <div>
           <label>Email address <small>You can fill out same email as log-in email.</small>
           <input name="email" type="email" id="email" value="<?=$member->email?>"<?=Config::readonly($r)?>>
          </label></div>
        <div>
           <label>Facebook Profile name/URL
            <input name="fb" type="text" id="fb" placeholder="Mark Zuckerberg or https://www.facebook.com/zuck" value="<?=$member->fb?>"<?=Config::readonly($r)?>></label></div>
        <div>
           <label>Twitter
            <input name="tw" type="text" id="tw" placeholder="@twitter" value="<?=$member->tw?>"<?=Config::readonly($r)?>>
          </label></div>
<? if($no>0):?>         <div>
           <label class="require">Emergency contact <small>with country code</small>
             <input name="emerg_contact" type="tel" id="emerg_contact" placeholder="+xx xxx xxx xxx ..." value="<?=$member->emerg_contact?>"<?=Config::readonly($r)?>></label></div><? endif;?>
      </fieldset>
      <fieldset>
        <legend>Lifestyle</legend>
         <div>
           <label class="require">Religion
             <input name="religion" type="text" id="religion" value="<?=$member->religion?>"<?=Config::readonly($r)?>>
          </label></div>
          <div>
            <label>Preferred specific cuisine
              <textarea name="cuisine" rows="5" id="cuisine"<?=Config::readonly($r)?>><?=$member->cuisine?></textarea>
          </label></div>
          <div>
            <label>Food/Drug allergy
              <textarea name="allergy" rows="5" id="allergy"<?=Config::readonly($r)?>><?=$member->allergy?></textarea>
          </label></div>
          <div>
            <label>Underlying disease
              <textarea name="disease" rows="5" id="disease"<?=Config::readonly($r)?>><?=$member->disease?></textarea>
          </label></div>
          <div>
            <label>Other requirements
              <textarea name="other_req" rows="5" id="other_req"<?=Config::readonly($r)?>><?=$member->other_req?></textarea>
          </label></div>
      </fieldset>
      <fieldset><legend>Shirt size</legend>
        <a href="../pictures/shirt_size_chart.jpg" target="_blank" class="th"><img src="../pictures/shirt_size_chart.jpg" alt="Shirt size chart"></a><br><br>
<?=Participant::shirtSize($member->shirt_size,$r)?>
      </fieldset>
<?php
		unset($ajax);
		if($no>0 && !$r):
?>
	<hr><fieldset><legend>A copy of student ID card or certificate of student</legend><div><label>Delete the document <small>If you remove file, the file is permanently romoved.</small></label>
    <input name="delete" type="radio" id="delete_0<?=$no?>" value="0" checked="checked" /><label for="delete_0<?=$no?>">Keep it</label><input name="delete" type="radio" id="delete_1<?=$no?>" value="1" /><label for="delete_1<?=$no?>">Delete it</label></div></fieldset>
<?php
			echo $img->toImgPartStudentCard($no);
		elseif($no>0):
			echo "<h4>You don't have permission to see the document</h4>";
		endif;
		if(!$r):
?>      <fieldset class="require"><legend>Save</legend>
      <div><button type="submit" name="submitInfo">Save</button><button type="reset" name="resetInfo">Cancel</button></div>
      </fieldset><? endif;?>
  </form></div>
<? endfor;?>
</div>
<div class="alert-box alert radius" id="teamInfoMsg"><?=$msg?></div>
<?php
	return ob_get_clean();
}
?>
