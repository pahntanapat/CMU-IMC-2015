$(document).ready(function(e) {
    $('form').submit(function(e) {
        e.preventDefault();
		$.post($(this).attr('action')+"&ajax="+Math.random(),$(this).serialize(),function(data){
			if($(e.target).processJSON(data,true) && window.opener!=null)
				window.opener.location.reload();
		});
    });
	$("a.open").click(function(e) {
		e.preventDefault();
        return window.open($(this).attr('href'),$(this).attr('target'));
    });
});