<?php
require_once 'config.inc.php';
class UploadImageException extends Exception{
	
	
}

class UploadImageOriginal{ // Upload image and convert to jpg
	const JPG='jpg';
	public $team_id, $formName='uploadImgInput';
	
	
	
	public function upload($filename){
		$file=$_FILES[$this->formName];
		
		if($file['error']!=UPLOAD_ERR_OK)
			;
		// Dectect ext and creat img resource to Convert toJPG
		switch($file['type']){
			case 'image/pjpeg':
			case 'image/jpeg':
			case 'image/jpg':
		}
		// Move if Small size
		
		// Compress
		
		// Export
		
		// destroy
		
	}
	
	// return <img>
	public function toImg($filename){
		ob_start();
		$filename=$this->getFolder($filename);
?><div><?php
		if(file_exists($filename)):
			$filename=urlencode(base64_encode($filename));
?><a href="images.php?img=<?=$filename?>" target="_blank" class="th"><img src="images.php?img=<?=$filename?>"/></a><?php
		else:
?><b>&quot;No image&quot;</b><?php
		endif;
?></div><?php
		return ob_get_clean();
	}
	
	// return file_get_contens(IMG)
	public function getImg($queryString){
		ob_start();
		$filename=base64_decode($queryString);
		$img=file_exists($filename)?file_get_contents($filename):false;
		if($img===false){
			echo "<br/>\n".'Unable to read file: '.$this->getFolder($filename);
			$img=ob_get_clean();
		}else{
			header('Content-Type: image/'.self::JPG);
			ob_end_clean();
		}
		return $img;
	}
	
	protected function getFolder($filename){
		global $config;
		return $_SERVER['DOCUMENT_ROOT'].'/'.
			$config->UPLOAD_FOLDER.'/'.
			implode('/',str_split(sprintf("%020d",$this->team_id),2)).'/'.
			$filename.self::JPG;
	}
	
	protected function toJPG($file){
		
	}
	
	// return <input type="file" />
	public function toForm($disable=false){
		$disable=$disable?" disabled=\"disabled\"":'';
		return <<<HTML
<input type="file" name="{$this->formName}" id="{$this->formName}"{$disable} required="required">
HTML;
	}
}

class UploadImage extends UploadImageOriginal{
	const NAME_PREFIX_PART='part_', NAME_PAY='pay', NAME_TICKET='ticket', NAME_TEAM_PHOTO='team_photo';
	
	public function uploadPartStudentCard($part_no){
		return self::upload(self::NAME_PREFIX_PART.$part_no);
	}
	public function toImgPartStudentCard($part_no){
		global $config;
		return ($part_no>$config->REG_PARTICIPANT_NUM || $part_no<=0)
			?'Participant\'s number is wrong.'
			:self::toImg(self::NAME_PREFIX_PART.$part_no);
	}
	
	public function uploadPay(){
		return self::upload(self::NAME_PAY);
	}
	public function toImgPay(){
		return self::toImg(self::NAME_PAY);
	}
	
	public function uploadTicket(){
		return self::upload(self::NAME_TICKET);
	}
	public function toImgTicket(){
		return self::toImg(self::NAME_TICKET);
	}
	
	public function uploadTeamPhoto(){
		return self::upload(self::NAME_TEAM_PHOTO);
	}
	public function toImgTeamPhoto(){
		return self::toImg(self::NAME_TEAM_PHOTO);
	}
}
?>