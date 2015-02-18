$(function(e) {
	$('form').submit(function(e) {
    	e.preventDefault();
		var test=$(this).formWaitSK(null,null);
		$(this).fadeTo(10000,0.5,function(){$(this).postSK('login.scr.php?'+$.SK(),test);});
	//	$(this).postSK('login.scr.php?'+$.SK(),$(this).formWaitSK);
		return false;
    });
});