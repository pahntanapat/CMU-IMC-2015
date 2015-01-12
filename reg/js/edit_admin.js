$(function(e){
	$(document).on('click','#showPW',function(e) {
        if($(this).data('pw')){
			$(this).data('pw',false);
			$("#password").prop('type','text');
		}else{
			$(this).data('pw',true);
			$("#password").prop('type','password');
		}
		return false;
    });
	$('#selectAll').checkAll('.del');
	$('#reloadAdminList').click(function(e) {
		e.preventDefault();
		return $('#adminList').loadSK('edit_admin.scr.php',$.SK(),true);
    });
	$(document).on('submit','form',function(e) {
		e.preventDefault();
		return $(this).postSK('edit_admin.scr.php?'+$.SK(),true);
    });
	$(document).on('click','a.edit',function(e) {
        e.preventDefault();
		$(this).loadSK('edit_admin.scr.php',
			$(this).act().split('?')[1]+'&'+$.SK(),
			function(r,msg){
				$.addDialog(msg).children('div').prop('id','divAdminForm');//'<div id="divAdminForm">'+msg+'</div>'
		});
    });
});