$(function(e){
	$(document).on('click','#showPW',function(e) {
		if($(this).data('pw')){
			$(this).data('pw',false);
			$("#password").prop('type','text');
		}else{
			$(this).data('pw',true);
			$("#password").prop('type','password');
		}
    });
	$('#selectAll').checkAll('.del');
	$('#reloadAdminList').click(function(e) {
		e.preventDefault();
		return $('#adminList').loadSKOriginal('admin.edit.scr.php',$.SK());
    });
	$(document).on('submit','form',function(e) {
		e.preventDefault();
		return $(this).postSKOriginal('admin.edit.scr.php?'+$.SK());
    });
	$(document).on('click','a.edit',function(e) {
        e.preventDefault();
		return $(this).loadSKOriginal('admin.edit.scr.php',
			$(this).act().split('?')[1]+'&'+$.SK(), true
		);
    });
});