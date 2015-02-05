$(document).ready(function(e) {
    $("form").submit(function(e) {
        e.preventDefault();
		return $(this).postSK($(this).data('action')+'&'+$.SK(),true);
    });
});