$(document).ready(function(e) {
    $(document).on('click','a.reload',function(e){
		e.preventDefault();
		return $(this).loadSKOriginal($(this).act(), $.SK(), true);
	});
});