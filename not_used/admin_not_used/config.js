$(document).ready(function(e) {
    $('#tabs').tabs();
	$('form').submit(function(e) {
        e.preventDefault();
		$.post($(this).attr('action')+'&ajax='+Math.random(),$(this).serialize(),function(data){
			return $(e.target).processJSON(data,true);
		});
    });
	$('a.reset').click(function(e) {
        e.preventDefault();
		$.get($(this).attr('href'),{ajax:Math.random()},function(data){
			return $(e.target).processJSON(data,true);
		});
    });
});