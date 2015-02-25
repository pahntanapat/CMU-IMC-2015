<?php
require_once 'class.Admin.php';
require_once 'class.SesAdm.php';

function tableAdmin(Admin $adm,$msg=''){
	ob_start();?>
    <div id="msgTable" class="alert-box info"><?=$msg?><br/><small>Last update: <?=date('Y-m-d H:i:s e')?></small></div>
<table width="100%" border="0">
  <tr>
    <th scope="col">Delete</th>
    <th scope="col">Student ID</th>
    <th scope="col">Nickname</th>
    <th scope="col">Edit</th>
  </tr>
<?		foreach($adm->getList() as $row):?>  <tr>
    <th scope="row"><input name="del[]" type="checkbox" class="del" value="<?=$row->id?>" title="delete"></th>
    <td><?=$row->student_id?></td>
    <td><?=$row->nickname?></td>
    <td class="center"><a href="admin.edit.php?id=<?=$row->id?>" title="Edit admin" class="edit">Edit</a></td>
  </tr><? endforeach;?>
</table>
<?php
	return ob_get_clean();
}

function formAdmin(Admin $adm,$id=false,$msg=NULL){
	require_once 'config.inc.php';
	if(Config::isPost() && $adm->id==NULL && $id===false){
		$adm=new Admin(NULL);
		$adm=Config::assocToObjProp($_POST,$adm);
	}elseif($id>0){
		$adm->id=$id;
		$adm->load();
	}elseif($id!==false){
		$adm=new Admin(NULL);
		$adm->id=0;
	}
	ob_start();?>
    <a href="admin.edit.php?id=<?=$adm->id?>" target="_blank">View in new tab</a>
    <form action="admin.edit.php?id=<?=$adm->id?>" method="post" id="formAdmin"><fieldset><legend><?=$adm->id==0?'Add':'Edit'?> admin</legend>
    <div>
      <label for="student_id">Student ID:</label>
      <input name="student_id" type="text" required="required" id="student_id" placeholder="รหัสนักศึกษา" value="<?=$adm->student_id?>" autocomplete="off">
      <input name="id" type="hidden" id="id" value="<?=$adm->id?>">
    </div>
    <div>
      <label for="nickname">Nickname</label>
      <input name="nickname" type="text" required="required" id="nickname" placeholder="nickname" value="<?=$adm->nickname?>" autocomplete="off">
      <input name="act" type="hidden" id="act" value="edit" autocomplete="off" required="required">
    </div>
    <div>
      <label for="password">Password</label>
      <input name="password" type="password" required="required" id="password" placeholder="password" value="<?=$adm->password?>"><br>
<button type="button" data-pw="true" id="showPW">show/hide Password</button>
    </div>
    <fieldset>
      <legend>permission</legend>
        <?=SesAdm::checkbox($adm->permission)?>
    </fieldset>
    <div class="btnset"><button type="submit">บันทึก</button><button type="reset">ยกเลิก</button></div><div id="msgForm"><?=$msg?></div></fieldset></form>
    <?php
	return ob_get_clean();
}
?>