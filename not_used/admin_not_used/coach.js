$(document).ready(function(e) {
    $('form').submit(function(e) {
        e.preventDefault();
		$.post($(this).attr('action')+($(this).attr('action').indexOf('=')==-1?'?':'&')+'ajax='+Math.random(),
			$(this).serialize(),function(data){
			if($(e.target).processJSON(data,true) && window.opener!=null)
				return window.opener.location.reload();
		});
		return false;
    });
	$('a.del').click(function(e) {
        e.preventDefault();
		if(!confirm('คุณต้องการ'+$(this).attr('title')+' ใช่หรือไม่?')) return false;
		$.get($(this).attr('href'),{ajax:Math.random()},function(data){
			if($(e.target).processJSON(data,true)) window.location.reload();
		});
    });
	$('a.open').click(function(e) {
        e.preventDefault();
		return window.open($(this).attr('href'),$(this).attr('target'));
    });
	$(this).tooltip();
});