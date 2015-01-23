$(function(e) {
	$('form').submit(function(e) {
    	e.preventDefault();
		$(this).postSK('login.scr.php?'+$.SK(),true);
		return false;
    });
});