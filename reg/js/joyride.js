$(document).ready(function(e) {
	alert($.cookie('showJR'));
    if(typeof $.cookie('showJR')=='undefined')
		$.cookie('showJR','true');
	if($.cookie('showJR', Boolean))
		$(document).foundation('joyride','start');
});