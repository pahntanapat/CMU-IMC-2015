<?php
require_once 'config.inc.php';
class UploadImageException extends Exception{
	const
			TYPE_UPLOAD_ERROR=1,
			TYPE_IMG_ERROR=2,
			
			CODE_UNSUPPORT_EXT=1,
			CODE_WRONG_FORMAT=2,
			CODE_UNWRITTABLE=3;
	public function __construct($filename='',$code=0,$type=0){
		if($type==self::TYPE_IMG_ERROR){
			$message="Fail to process ".$filename." because ";
			switch($code){
				case self::CODE_UNSUPPORT_EXT:
					$message.="uploaded file is not supported. Please upload JPEG (*.jpg, *.jpeg), PNG (*.png), or GIF (*.gif)  image only.";
					break;
				case self::CODE_WRONG_FORMAT:
					$message.="upload file format does not match its filetype (extension). Please check your image whether it is damage.";
					break;
				case self::CODE_UNWRITTABLE:
					$message.="the registration system cannot save uploaded file on server. Please contact administrator.";
					break;
				default:
			}
		}elseif($type==self::TYPE_UPLOAD_ERROR){
			$message="Unable to upload file $filename because ";
			switch($code){
				case UPLOAD_ERR_INI_SIZE:
				case UPLOAD_ERR_FORM_SIZE:
					$message.="uploaded filesize is too large. Please compress your image, change to other filetypes, or reduce its resolution.";
					break;
				case UPLOAD_ERR_PARTIAL:
					$message.="some part of file was not uploaded. Please check your internet connection and try to upload the image again."
					;break;
				case UPLOAD_ERR_NO_FILE:
					$message.="there was not uploaded file. Please check your internet connection and your web browser. After solving the problems, try to upload the image again.";
					break;
				case UPLOAD_ERR_NO_TMP_DIR:
					$message.="temporary directory on web server is not found (Code: UPLOAD_ERR_NO_TMP_DIR). Please contact administrator.";
					break;
				case UPLOAD_ERR_CANT_WRITE:
					$message.="the registration system cannot write any file to the server (Code: UPLOAD_ERR_CANT_WRITE). Please contact administrator.";
					break;
				case UPLOAD_ERR_EXTENSION:
					$message.="unknown problem occurs in the registration system (Code: UPLOAD_ERR_EXTENSION). Please contact administrator.";
			}
		}
		parent::__construct($message,$code);
	}
}

class UploadImageOriginal{ // Upload image and convert to jpg
	const JPG='jpg';
	public $team_id, $formName='uploadImgInput',
	$minFileSize=204800, $minResolutionArray=array(1754,1240), // A4 150dpi
	$quality=25, $algorithmFit=true // true = Fit, false = FIll
	;
	public static function rootFolder(){
		global $config;
		return $_SERVER['DOCUMENT_ROOT'].'/'.
			$config->UPLOAD_FOLDER.'/';
	}
	
	public static function getFolder($team_id){
		global $config;
		return self::rootFolder().implode('/',str_split(sprintf("%020d",$team_id),2)).'/';
	}
		
	protected function getFile($filename=false){
		$dir=self::getFolder($this->team_id);
		if(!is_dir($dir))
			mkdir($dir,0777,true);
		return $dir.$filename.'.'.self::JPG;
	}
	
	public function reset(){
		$v=self::rootFolder();
		if(is_dir($v))
			return rmdir($v);
	}
	
	public function upload($filename){
		$file=$_FILES[$this->formName];
		
		if($file['error']!=UPLOAD_ERR_OK)
			throw new UploadImageException($file['name'], $file['error'], UploadImageException::TYPE_UPLOAD_ERROR);
		// Dectect ext and creat img resource to Convert toJPG
		$move=false;
		$img=NULL;
		switch($file['type']){
			case 'image/pjpeg':
			case 'image/jpeg':
			case 'image/jpg':
				if($file['size']<=$this->minFileSize) $move=true;
				else $img=imagecreatefromjpeg($file['tmp_name']);
				break;
			case 'image/gif':
				$img=imagecreatefromgif($file['tmp_name']);
				break;
			case 'image/png':
				$img=imagecreatefrompng($file['tmp_name']);
				break;
			default:
				throw new UploadImageException($file['name'], UploadImageException::CODE_UNSUPPORT_EXT, UploadImageException::TYPE_IMG_ERROR);
				return false;
		}
		
		if($move){ // Move if Small size
			if(move_uploaded_file($file['tmp_name'], $this->getFile($filename))){
				return true;
			}else{
				throw new UploadImageException($file['name'], UploadImageException::CODE_UNWRITTABLE, UploadImageException::TYPE_IMG_ERROR);
				return false;
			}
		}elseif($img===false){ // If cannot create img resource
			throw new UploadImageException($file['name'], UploadImageException::CODE_WRONG_FORMAT, UploadImageException::TYPE_IMG_ERROR);
			return false;
		}else{
			// set target resolution
			$imgW=imagesx($img);
			$imgH=imagesy($img);
			
			if($imgH>$imgW){
				$h=max($this->minResolutionArray);
				$w=min($this->minResolutionArray);
			}else{
				$h=min($this->minResolutionArray);
				$w=max($this->minResolutionArray);
			}
			// Compress
			if($h>=$imgH || $w>=$imgW || $h*$w>=$imgH*$imgW){ // Resolution <= minResolutionArray
				$resize=$img;
			}else{
				// Algorithm: Fit max($imgH,$imgW) < max($h,$w) / Fill min($imgH,$imgW) < min($h,$w)
				/*
				// Algorithm is Fit.
				if(($imgH*$w)>($h*$imgW)) // Equivalent to ($imgH/$imgW)>($h/$w) สูงกว่ากว้าง when size>=0
					$w=round($h*$imgW/$imgH); // ใช้ $h เป็นหลัก
				else
					$h=round($w*$imgH/$imgW); // ใช้ $w เป็นหลัก
				// Algorithm is Fill.
				if(($imgH*$w)<=($h*$imgW)) // Equivalent to ($imgH/$imgW)>($h/$w) when size>=0
					$w=round($h*$imgW/$imgH); // ใช้ $h เป็นหลัก
				else
					$h=round($w*$imgH/$imgW); // ใช้ $w เป็นหลัก
				*/
				
				$resize=($imgH*$w)>($h*$imgW);
				if($this->algorithmFit?$resize:!$resize) // Equivalent to ($imgH/$imgW)>($h/$w) when size>=0
					$w=round($h*$imgW/$imgH); // ใช้ $h เป็นหลัก
				else
					$h=round($w*$imgH/$imgW); // ใช้ $w เป็นหลัก
				$resize=imagecreatetruecolor($w,$h);
				imagecopyresampled($resize,$img,0,0,0,0,$w,$h,$imgW,$imgH);
			}
			// Export
			if(!imagejpeg($resize,$this->getFile($filename),$this->quality)){
				throw new UploadImageException($file['name'], UploadImageException::CODE_UNWRITTABLE, UploadImageException::TYPE_IMG_ERROR);
				return false;
			}
			// destroy
			if(isset($img)) @imagedestroy($img);
			if(isset($resize)) @imagedestroy($resize);
			unset($img,$resize,$imgH,$imgW,$file,$filename,$h,$w,$move);
			return true;
		}
		return false;
	}
	
	// return <img>
	public function toImg($filename){
		ob_start();
		$filename=$this->getFile($filename);
?><div><?php
		if(file_exists($filename)):
			$filename=urlencode(base64_encode(base64_encode($filename).'|'.rand()));
?><a href="images.php?img=<?=$filename?>" target="_blank" class="th"><img src="images.php?img=<?=$filename?>" width="100%"/></a><?php
		else:
?><b>&quot;No image&quot;</b><?php
		endif;
?></div><?php
		return ob_get_clean();
	}
	
	// return file_get_contens(IMG)
	public function getImg($queryString){
		ob_start();
		$queryString=base64_decode($queryString);
		$filename=base64_decode(substr($queryString,0,strpos($queryString,'|')));
		$img=file_exists($filename)?file_get_contents($filename):false;
		if($img===false){
			echo "<br/>\n".'Unable to read file: '.$this->getFile($filename);
			$img=ob_get_clean();
		}else{
			header('Content-Type: image/'.self::JPG);
			ob_end_clean();
		}
		return $img;
	}
	
	// return <input type="file" />
	public function toForm($disable=false){
		$disable=$disable?" disabled=\"disabled\"":'';
		return <<<HTML
<input type="file" name="{$this->formName}" id="{$this->formName}"{$disable} required="required">
HTML;
	}
	
	public function deleteFile($filename){
		$f=$this->getFile($filename);
		return is_file($f)?unlink($f):NULL;
	}
	
	public function deleteFolder($list){
		$i=0;
		foreach($list as $v){
			$v=self::getFolder($v);
			if(!is_dir($v)) continue;
			if(rmdir($v)) $i++;
		}
		return $i;
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
	public function deletePartStudentCard($part_no){
		return self::deleteFile(self::NAME_PREFIX_PART.$part_no);
	}
	
	public function uploadPay(){
		return self::upload(self::NAME_PAY);
	}
	public function toImgPay(){
		return self::toImg(self::NAME_PAY);
	}
	public function deletePay(){
		return self::deleteFile(self::NAME_PAY);
	}
	
	public function uploadTicket(){
		return self::upload(self::NAME_TICKET);
	}
	public function toImgTicket(){
		return self::toImg(self::NAME_TICKET);
	}
	public function deleteTicket(){
		return self::deleteFile(self::NAME_TICKET);
	}
	
	public function uploadTeamPhoto(){
		return self::upload(self::NAME_TEAM_PHOTO);
	}
	public function toImgTeamPhoto(){
		return self::toImg(self::NAME_TEAM_PHOTO);
	}
	public function deleteTeamPhoto(){
		return self::deleteFile(self::NAME_TEAM_PHOTO);
	}
}
?>