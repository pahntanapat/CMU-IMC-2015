$(document).ready(function(e) {
	$('form').submit(function(e) {
		e.preventDefault();
		return $(this).postSK('admin.config.scr.php?'+$(this).act().split('?')[1]+'&'+$.SK());
    });
	$('a.reset').click(function(e) {
		e.preventDefault();
		if(confirm('Do you really want to '+$(this).text()+'?'))
        	return $(this).loadSK('admin.config.scr.php?'+$(this).act().split('?')[1]+'&'+$.SK());
    });
	$.datetimepicker();
});