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
		
		PAGE_TEAM_INFO=10,
		PAGE_PART_=20,
		PAGE_OBSERVER=20,
		PAGE_PAY=30,
		PAGE_TICKET=40
		;
		
		public $id, $team_id, $title, $detail, $time, $admin_id, $show_page;
		protected $TABLE='team_message';
		
		public static function PAGE_PART($partNO){
			return self::PAGE_PART_ +$partNO;
		}
		
		public function  update(){
			if($this->id<=0 || $this->id==NULL)
				$sql='INSERT INTO '.$this->TABLE.'';
		}
}
?>