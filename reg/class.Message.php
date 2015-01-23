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
		PAGE_POST_REG_TEAM=40,
		PAGE_POST_REG_PART_=50,
		PAGE_POST_REG_OBSERVER=50
		;
		
		public $id, $team_id, $title, $detail, $time, $admin_id, $show_page;
		protected $TABLE='team_message';
		
		public static function PAGE_INFO_PART($partNO){
			return self::PAGE_INFO_PART_ +$partNO;
		}
				
		public static function PAGE_POST_REG_PART($partNO){
			return self::PAGE_POST_REG_PART_+$partNO;
		}
		
		public function  update(){
			if($this->id<=0 || $this->id==NULL)
				$sql='INSERT INTO '.$this->TABLE.'';
		}
}
?>