$(document).ready(function(e) {
    $('#reg').submit(function(e) {
		e.preventDefault();
        return $(this).postSK('register.scr.php?'+$.SK(),true);
    });
});