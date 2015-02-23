$(document).ready(function(e) {
    $(document).on('click','a.edit',function(e){
		e.preventDefault();
		$.addDialog('approveForm');
		return $(this).loadSKOriginal($(this).act(),$.SK(),loadScript);
	});
	$('#reload').click(function(e) {
        e.preventDefault();
		return $(this).loadSKOriginal($(this).act(),$.SK(),loadScript);
    });
	$(document).on('submit','form',function(e){
		e.preventDefault();
		return $(this).postSK($(this).addSK());
	});
});