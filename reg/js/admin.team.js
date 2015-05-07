$(document).ready(function(e) {
	loadScript();
    $('#reloadAdminList').click(function(e) {
		e.preventDefault();
        return $(this).loadSKOriginal('admin.team.scr.php',$.SK(),loadScript);
    });
	$(document).on('submit','form.updateInfoForm',function(e){
		e.preventDefault();
		return $(this).postSK($(this).addSK());
	});
	$('#teamListForm').submit(function(e) {
        e.preventDefault();
		return $(this).postSKOriginal('admin.team.scr.php?'+$.SK(),loadScript);
    });
	$(document).on('click','a.edit',function(e){
		e.preventDefault();
		$.addDialog('divTeamInfo');
		return $(this).loadSKOriginal($(this).act(),$.SK(), loadScript);
	});
	$('#selectAll').checkAll('input.del');
});