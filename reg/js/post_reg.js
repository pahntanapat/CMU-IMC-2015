$(document).ready(function(e) {
    $.datetimepicker();
	$('#infoForm').submit(function(e) {
        e.preventDefault();
		if(!$('input[type="datetime"]').checkDateTime()){
			$('#msg').html('<div class="alert-box alert">Arrival or Depart time is invalid. Please fill out the correct format (YYYY-MM-DD hh:mm:ss).</div>');
			return false;
		}
		return $(this).postSK('post_reg.scr.php?'+$.SK());
    });
	$('#photoForm, #ticketForm').ajaxForm({
		clearForm: true,
		dataType: 'json',
		url: 'post_reg.scr.php?'+$.SK(),
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