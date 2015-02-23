$(function(e) {
	$('form').submit(function(e) {
    	e.preventDefault();
		$(this).postSK('admin.scr.php?'+$.SK());
		return false;
    });
});