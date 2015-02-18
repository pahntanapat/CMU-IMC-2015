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
		return $('#adminList').loadSK('edit_admin.scr.php',$.SK());
    });
	$(document).on('submit','form',function(e) {
		e.preventDefault();
		return $(this).postSK('edit_admin.scr.php?'+$.SK());
    });
	$(document).on('click','a.edit',function(e) {
        e.preventDefault();
		return $(this).loadSKOriginal('edit_admin.scr.php',
			$(this).act().split('?')[1]+'&'+$.SK(), true
		);
    });
});