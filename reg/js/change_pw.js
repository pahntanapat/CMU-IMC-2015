$(document).ready(function(e) {
    $('#changePassword, #editProfile').submit(function(e) {
        e.preventDefault();
		return $(this).postSK($(this).data('action')+'?'+$.SK(),true);
    });
	$(document).on('click','#reloadMsg',function(e){
		e.preventDefault();
		return $(this).loadSK('index.scr.php',$.SK(),true);
	});
});