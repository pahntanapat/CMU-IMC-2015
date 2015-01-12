$(document).ready(function(e) {
    $('#tabs').tabs();
	$('form').submit(function(e) {
		e.preventDefault();
		return $(this).postSK('config.scr.php?'+$(this).act().split('?')[1]+'&'+$.SK(),true);
    });
	$('a.reset').click(function(e) {
		e.preventDefault();
        return $(this).loadSK('config.scr.php?'+$(this).act().split('?')[1]+'&'+$.SK(),true);
    });
});