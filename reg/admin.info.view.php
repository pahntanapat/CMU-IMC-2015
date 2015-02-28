<?php
require_once 'config.inc.php';
require_once 'class.SesAdm.php';

$sess=SesAdm::check();
if(!$sess) Config::redirect('admin.php','you are not log in.');
if(!$sess->checkPMS(SesAdm::PMS_PARTC)) Config::redirect('home.php','you don\'t have permission here.');

require_once 'class.Team.php';
require_once 'class.Member.php';
require_once 'class.Message.php';
require_once 'class.UploadImage.php';
require_once 'class.State.php';

function approveTeam(Message $msg, $message=''){
	global $config;
	ob_start();
	$t=new Team($msg->getDB());
	$t->id=$msg->team_id;
	$t->load();
	
	$m=array();
	for($no=0;$no<=$config->REG_PARTICIPANT_NUM;$no++){
		$m[$no]=$no>0?new Participant($msg->getDB()):new Observer($msg->getDB());
		$m[$no]->team_id=$msg->team_id;
		if($no>0) $m[$no]->part_no=$no;
		$m[$no]->load();
	}
	
	$img=new UploadImage();
	$img->team_id=$msg->team_id;
	$msg->show_page=Message::PAGE_INFO_TEAM;
?>
<h3>Team's name: <?=$t->team_name?><small>
<a href="admin.team.php?id=<?=$t->id?>" target="_blank">Edit their information</a></small></h3>
 <ul class="tabs" data-tab>
   <li class="tab-title active"><a href="#t">Team's info</a></li>
<? for($no=0;$no<=$config->REG_PARTICIPANT_NUM;$no++):?>
<li class="tab-title"><a href="#p<?=$no?>"><? if($no==0):?>Advisor<? else: echo Config::ordinal($no)?> Participant<? endif;?></a></li>
<? endfor;?>
 </ul>
<div class="tabs-content">
<div class="content active" id="t"><div>
  <table width="100%" border="0">
    <tr>
      <th scope="col" class="require">Form</th>
      <th scope="col">Detail</th>
    </tr>
    <tr>
      <th scope="row" class="require">Email</th>
      <td><?=$t->email?></td>
    </tr>
    <tr>
      <th scope="row" class="require">Team's name</th>
      <td><?=$t->team_name?></td>
    </tr>
    <tr>
      <th scope="row" class="require">Medical school</th>
      <td><?=$t->institution?></td>
    </tr>
    <tr>
      <th scope="row" class="require">University</th>
      <td><?=$t->university?></td>
    </tr>
    <tr>
      <th scope="row" class="require">Address</th>
      <td><?=$t->address?></td>
    </tr>
    <tr>
      <th scope="row" class="require">Country</th>
      <td><?=$t->country?></td>
    </tr>
    <tr>
      <th scope="row" class="require">Med school's phone</th>
      <td><?=$t->phone?></td>
    </tr>
  </table></div>
<?=$msg->load()->toForm('admin.info.php?id='.$msg->team_id, array(State::ST_WAIT, State::ST_PASS, State::ST_NOT_PASS), $t->team_state)?>
</div>
<?php
for($no=0;$no<=$config->REG_PARTICIPANT_NUM;$no++):
	$msg->show_page=Message::PAGE_INFO_PART($no);?>
<div class="content" id="p<?=$no?>"><div>
<?	if($m[$no]->id===NULL):?>
    <h3>There isn't information of this person.</h3>
<? else:?>
  <table width="100%" border="0">
    <tr>
      <th scope="col">Form</th>
      <th scope="col" class="require">Detail</th>
    </tr><? if($no>0):?>
    <tr>
      <th scope="col" class="require">Participant No.</th>
      <td><?=$no?></td>
      </tr><? endif;?>
    <tr>
      <th scope="row" class="require">Title</th>
      <td><?=$m[$no]->title?></td>
      </tr>
    <tr>
      <th scope="row" class="require">Firstname</th>
      <td><?=$m[$no]->firstname?></td>
      </tr>
    <tr>
      <th scope="row">Middlename</th>
      <td><?=$m[$no]->middlename?></td>
      </tr>
    <tr>
      <th scope="row" class="require">Lastname</th>
      <td><?=$m[$no]->lastname?></td>
      </tr>
    <tr>
      <th scope="row" class="require">Gender</th>
      <td><?=$m[$no]->gender?'':'fe'?>male</td>
      </tr><? if($no>0):?>
    <tr>
      <th scope="row" class="require">Med Student Year</th>
      <td><?=$m[$no]->std_y?></td>
      </tr><? endif;?>
    <tr>
      <th scope="row" class="require">Birth</th>
      <td><?=$m[$no]->birth?></td>
      </tr>
    <tr>
      <th scope="row" class="require">Nationality</th>
      <td><?=$m[$no]->nationality?></td>
      </tr>
    <tr>
      <th scope="row">Phone</th>
      <td><?=$m[$no]->phone?></td>
      </tr>
    <tr>
      <th scope="row">Email</th>
      <td><?=$m[$no]->email?></td>
      </tr>
    <tr>
      <th scope="row">Facebook</th>
      <td><?=$m[$no]->fb?></td>
      </tr>
    <tr>
      <th scope="row">Twitter</th>
      <td><?=$m[$no]->tw?></td>
      </tr><? if($no>0):?>
    <tr>
      <th scope="row" class="require">Emergency contact</th>
      <td><?=$m[$no]->emerg_contact?></td>
      </tr><? endif;?>
    <tr>
      <th scope="row" class="require">Religion</th>
      <td><?=$m[$no]->religion?></td>
      </tr>
    <tr>
      <th scope="row">Preferred cuisine</th>
      <td><?=nl2br($m[$no]->cuisine)?></td>
      </tr>
    <tr>
      <th scope="row">Allergy</th>
      <td><?=nl2br($m[$no]->allergy)?></td>
      </tr>
    <tr>
      <th scope="row">Underlying disease</th>
      <td><?=nl2br($m[$no]->disease)?></td>
      </tr>
    <tr>
      <th scope="row">Other requirements</th>
      <td><?=nl2br($m[$no]->other_req)?></td>
      </tr>
    <tr>
      <th scope="row" class="require">Shirt size</th>
      <td><?=$m[$no]->shirt_size?></td>
    </tr>
  </table>
<?php
if($no>0) echo $img->toImgPartStudentCard($no);
echo $msg->load()->toForm('admin.info.php?id='.$msg->team_id, array(State::ST_WAIT, State::ST_PASS, State::ST_NOT_PASS), $m[$no]->info_state, '['.$m[$no]->id.', '.$no.']');
endif;
?></div></div>
<?	endfor;?>
</div>
<div id="apMsg" class="alert-box alert radius"><?=$message?></div>
<?php
	return ob_get_clean();
}

 ?>