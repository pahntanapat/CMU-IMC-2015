$(document).ready(function(e) {
    $('#reg').submit(function(e) {
		e.preventDefault();
		$(this).formWaitSK(null,null);
        return $(this).postSK('register.scr.php?'+$.SK(),$(this).formWaitSK);
    });
});