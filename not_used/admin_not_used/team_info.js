$(document).ready(function(e) {
    $('form').submit(function(e) {
        e.preventDefault();
		$.post('team_info.scr.php?ajax='+Math.random()+'&'+$(this).attr('action').split('?',2)[1],$(this).serialize(),function(data){
			if($(e.target).processJSON(data,true) && window.opener!=null)
				window.opener.$('#reloadTable').click();
		});
		return false;
    });
});