$(document).ready(function(e) {
    if(typeof $.cookie('showJR')=='undefined')
		$.cookie('showJR','[]', { expires: 365});
	$('#loadJR').click(function(e) {
        $(document).foundation('joyride','start');
		return setJR(false);
    });
	if($.parseJSON($.cookie('showJR')).indexOf(window.location.pathname)==-1)
		$('#loadJR').click();
	$(document).on('click','#hideJR',function(e) {
		return setJR($(this).prop('checked'));
    });
});
function setJR(off){
	var ck=$.parseJSON($.cookie('showJR'));
	if(off){
		if(ck.indexOf(window.location.pathname)==-1) ck[ck.length]=window.location.pathname;
	}else{
		if(ck.indexOf(window.location.pathname)!=-1) ck.splice(ck.indexOf(window.location.pathname),1);
		if(ck.indexOf('')!=-1) ck.splice(ck.indexOf(''),1);
	}
	return $.cookie('showJR',ck.length>0?'["'+ck.join('","')+'"]':'[]', { expires: 365});
}