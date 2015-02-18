$(document).ready(function(e) {
	$('#infoForm').submit(function(e) {
        e.preventDefault();
		if(!$('#birth').checkDate()){
			$('#msg').html('Date of birth is greater than today. Please fill out the correct date.');
			return false;
		}
		return $(this).postSK('member.scr.php?'+$.SK());
    });
	$('#uploadForm').ajaxForm({
		clearForm: true,
		dataType: 'json',
		url: 'member.scr.php?'+$.SK(),
		type: 'POST',
		beforeSerialize:function(){
			$('#uploadMsg').html('<div class="progress round"><span class="meter" style="width:0%"></span></div>');
		},
		uploadProgress:function(e,c,t,p){
			$('#uploadMsg>div>span').css({width: p+'%'});
		},
		success:function(msg){
			return $('#uploadForm').SKAjax(msg,true);
		}
	});
});