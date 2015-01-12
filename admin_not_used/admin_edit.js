$(document).ready(function(e) {
	$('#showPW').click(function(e) {
        if($(this).data('pw')){
			$(this).data('pw',false);
			$("#pw").prop('type','text');
		}else{
			$(this).data('pw',true);
			$("#pw").prop('type','password');
		}
		return false;
    }).button();
	$('form').submit(function(e) {
        e.preventDefault();
		$.post('admin.scr.php?ajax='+Math.random(),$(this).serialize(),function(data){
			if($(e.target).processJSON(data,true) && window.opener!=null) window.opener.$('.reload').click();
		});
		return false;
    });
});