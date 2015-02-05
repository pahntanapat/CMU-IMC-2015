$(document).ready(function(e) {
    $('form').submit(function(e) {
        e.preventDefault();
		$.post('login.scr.php?ajax='+Math.random(),$(this).serialize(),function(data){
			$(e.target).processJSON(data,true);
		});
		return false;
    });
});