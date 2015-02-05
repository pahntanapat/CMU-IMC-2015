$(document).ready(function(e) {
    $('form').submit(function(e) {
        e.preventDefault();
		$.post($(this).attr('action')+'&ajax='+Math.random(),$(this).serialize(),function(data){
			if($(e.target).processJSON(data,true)) window.close();
		});
    });
	$('a.ajax').click(function(e) {
        e.preventDefault();
		if(!confirm('คุณต้องการจะ'+$(this).attr('title')+'ใช่หรือไม่?')) return;
		$.get($(this).attr('href'),{ajax:Math.random()},function(data){
			if($(e.target).processJSON(data,true)){
				if(window.opener!=null){
					window.opener.location.reload();
					window.close();
				}else{
					window.location.reload();
				}
			}
		});
    });
});