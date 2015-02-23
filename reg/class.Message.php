<?php
require_once 'class.SKeasySQL.php';
class Message extends SKeasySQL{
	const
		ROW_TEAM_ID='team_id',
		ROW_TITLE='title',
		ROW_DETAIL='detail',
		ROW_ADMIN_ID='admin_id', 
		ROW_SHOW_PAGE='show_page',
		ROW_TIME='time',
		
		PAGE_INFO_TEAM=10,
		PAGE_INFO_PART_=20,
		PAGE_INFO_OBSERVER=20,
		PAGE_PAY=30,
		PAGE_POST_REG_TEAM=40
		;
		
	public $team_id, $title, $detail, $time, $admin_id, $show_page;
	protected $TABLE='team_message',$rowList;

	public function __construct($db){
		$this->setPDO($db);
		$this->rowList=array(
			self::ROW_TEAM_ID=>':tid',
			self::ROW_TITLE=>':t',
			self::ROW_DETAIL=>':d',
			self::ROW_ADMIN_ID=>':aid',
			self::ROW_SHOW_PAGE=>':page'
		);
	}
	
	public function bindValue(PDOStatement $stm){
		foreach($this->rowList as $k=>$v)
			$stm->bindValue($v,$this->$k);
	}
	public function del(){
		$stm=$this->db->prepare('DELETE FROM '.$this->TABLE.' WHERE '.self::ROW_ID.'=?');
		$stm->bindValue(1,$this->id,PDO::PARAM_INT);
		$stm->execute();
		$this->id=NULL;
		return $stm->rowCount();
	}
	public function update(){
		if($this->title==NULL && $this->detail==NULL) return array($this->id, $this->del());
		
		$sql=($this->id<=0 || $this->id==NULL)
			?$this->insert($this->rowList)
			:'UPDATE '.$this->TABLE.' SET '.self::equal($this->rowList).' WHERE '.self::ROW_ID.'=:id';
		$stm=$this->db->prepare($sql);
		$this->bindValue($stm);
		if(!($this->id<=0 || $this->id==NULL)) $stm->bindValue(':id',$this->id);
		$stm->execute();
		if($this->id<=0 || $this->id==NULL) $this->id=$this->db->lastInsertId();
		return array($this->id, $stm->rowCount());
	}
	
	public function load($page=true){
		$stm=$this->db->prepare('SELECT '.self::row()
			.' FROM '.$this->TABLE
			.' WHERE '.self::ROW_TEAM_ID.' =?'.($page?' AND '.self::ROW_SHOW_PAGE.'=? LIMIT 1':'')
		);
		$stm->bindValue(1,$this->team_id,PDO::PARAM_INT);
		if($page) $stm->bindValue(2,$this->show_page,PDO::PARAM_INT);
		$stm->execute();
		$re=$stm->fetchAll(PDO::FETCH_CLASS,__CLASS__,array($this->db));/*
		if($page){
			foreach(isset($re[0])?$re[0]:new self($this->db) as $k=>$v) $this->$k=$v;
		}*/
		
		return $page?isset($re[0])?$re[0]:$this:$re;
	}
	public function getList(){
		return $this->load(false);
	}
	
	public function __toString(){
		return ($this->show_page==NULL)?self::msgList($this->getList()):self::msg($this->load(true));
	}
	
	public function getDB(){
		return $this->db;
	}
	
	public static function PAGE_INFO_PART($partNO){
		return self::PAGE_INFO_PART_ +$partNO;
	}
	
	public static function msgList($msg){
		ob_start();?>
        <div><h3>Messages from administrator <small>Last update: <?=date('Y-m-d H:i:s e')?></small></h3>
        <button id="reloadMsg" type="button">reload</button></div>
        <ul class="accordion" data-accordion>
		<? foreach($msg as $i):?>
        <li class="accordion-navigation"><a href="#teamMessage<?=$i->id?>"><?=$i->title?></a>
        <div id="teamMessage<?=$i->id?>" class="content"><p><?=$i->detail?></p><h6><?=$i->time?></h6></div></li>
		<? endforeach;?>
        <li class="accordion-navigation"><a href="#SinkanokLabs">About the Registration system</a>
        <div id="SinkanokLabs" class="content active"><p>Powered by SKAjax Framwork and Storage-Processor-Carrier-View Architecture of Sinkanok Groups</p><p>Copyright &copy; 2015 <a href="http://labs.sinkanok.com">Sinkanok Labs</a>, <a href="http://sinkanok.com">Sinkanok Groups</a></p></div></li></ul><?php
		return ob_get_clean();
	}
	
	public static function msg(self $i){
		if($i->id<0||$i->id==NULL) return '';
		ob_start();?>
        <div id="teamMsg" data-id="<?=$i->id?>" class="round panel callout"><h3><?=$i->title?></h3><p><?=$i->detail?></p><h6><?=$i->time?></h6></div>
		<?php
		return ob_get_clean();
	}
	
	public function toForm($action,$approveArray,$approveState=false,$addInfo=NULL){
		require_once 'class.State.php';
		ob_start();?>
<form action="<?=$action?>" method="post">
  <fieldset>
    <legend>Approval &amp; Message to Participant</legend>
    <div><label>This information is </label>
<? foreach($approveArray as $k=>$v):?>
    <input type="radio" name="approve" id="approve_<?=$k?>" value="<?=$v?>"<? if($v==$approveState):?> checked="checked"<? endif;?> /><label for="approve_<?=$k?>"><?=State::img($v)?></label>
<? endforeach;?>
    </div><h4>Message to Participants <small>E.g. reasons why the information is not approved. Leave blank if you don't have.</small></h4><div>
      <label>Title
        <input name="title" type="text" id="title" value="<?=$this->title?>" />
      </label>
      <input name="id" type="hidden" value="<?=$this->id?>" />
      <input name="team_id" type="hidden" value="<?=$this->team_id?>" />
      <input name="show_page" type="hidden" value="<?=$this->show_page?>" />
      <input name="add_info" type="hidden" value="<?=$addInfo?>" />
    </div>
    <div>
      <label>Detail
        <textarea name="detail" cols="45" rows="5" id="detail"><?=$this->detail?></textarea>
      </label>
    </div>
        <div>
<b>Last updated: </b><?=$this->time?>
    </div>
    <div><button type="submit">Approve</button><button type="reset">Cancel</button></div>
  </fieldset>
</form>
        <?php
		return ob_get_clean();
	}
}
?>