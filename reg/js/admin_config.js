$(document).ready(function(e) {
	$('form').submit(function(e) {
		e.preventDefault();
		return $(this).postSK('admin_config.scr.php?'+$(this).act().split('?')[1]+'&'+$.SK());
    });
	$('a.reset').click(function(e) {
		e.preventDefault();
        return $(this).loadSK('admin_config.scr.php?'+$(this).act().split('?')[1]+'&'+$.SK());
    });
});