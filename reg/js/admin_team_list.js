$(document).ready(function(e) {
	loadScript();
    $('#reloadAdminList').click(function(e) {
		e.preventDefault();
        return $(this).loadSKOriginal('admin_team_list.scr.php',$.SK(),loadScript);
    });
	$(document).on('submit','form',function(e){
		e.preventDefault();
		var act=$(this).act();
		act+=act.indexOf('?')==-1?'?':'&';
		return $(this).postSKOriginal(act+$.SK(),loadScript);
	});
	$(document).on('click','a.edit',function(e){
		e.preventDefault();
		$.addDialog('divTeamInfo');
		return $(this).loadSKOriginal($(this).act(),$.SK(), loadScript);
		
	});
});
function loadScript(){
	$(document).foundation();
	$.datetimepicker();
}