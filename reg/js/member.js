$(document).ready(function(e) {
	$.datetimepicker();
	$('#infoForm').submit(function(e) {
        e.preventDefault();
		if(!$('#birth').checkDate()){
			$('#msg').html('<div class="alert-box alert">Date of birth is invalid. Please fill out the correct format (YYYY-MM-DD).</div>');
			return false;
		}
		if($('#birth').val().trim().length>0 && Date.parse($('#birth').val())>=new Date().getTime()){
			$('#msg').html('<div class="alert-box alert">Date of birth is greater than today. Please fill out the correct date.</div>');
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
			$('#uploadMsg')
				.removeClass('alert-box radius round warning success alert info secondary')
				.html('<div class="progress round"><span class="meter" style="width:0%"></span></div>');
		},
		uploadProgress:function(e,c,t,p){
			$('#uploadMsg>div>span').css({width: p+'%'});
		},
		success:function(msg){
			return $('#uploadForm').SKAjax(msg,function(r, msg){
				$('#uploadMsg').addClass('alert-box radius')
					.addClass((r==true?'success':(r==false?'alert':'info')))
					.append('<br/><small>Time: '+Date()+'</small>');
			});
		}
	});
});