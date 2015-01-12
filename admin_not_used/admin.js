$(document).ready(function(e) {
    $('.reload').click(function(e) {
        $.get('admin.scr.php',{t:Math.random()},function(data){
			$('form table').after(data).remove();
		});
		return false;
    });
	$('form').submit(function(e) {
        e.preventDefault();
		if($(this).find(':checked').length<=0) return false;
		if(!confirm('คุณต้องการจะลบ admin ใช่หรือไม่?')) return false;
		$.post('admin.scr.php?ajax='+Math.random(),$(this).serialize(),function(data){
			if($(e.target).processJSON(data,true))
				$('.reload').click();
		});
    });
	$(document).on('click','a.open',function(e){
		e.preventDefault();
        return window.open($(this).attr('href'),$(this).attr('target'));
	});
});