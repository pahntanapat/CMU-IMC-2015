<?php
if(isset($_GET['img'])){
	require_once 'class.SesAdm.php';
	require_once 'class.SesPrt.php';
	if(SesAdm::check(true,false) || SesPrt::check(false)){
		require_once 'class.UploadImage.php';
		$img=new UploadImage();
		echo $img->getImg($_GET['img']);
	}else{
		header("Content-Type: text/html; charset=utf-8");
		echo 'You must log in before accessing the images.';
	}
}
?>