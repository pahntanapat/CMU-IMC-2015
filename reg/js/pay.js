$(document).ready(function(e) {
    $('#uploadForm').ajaxForm({
		clearForm: true,
		dataType: 'json',
		url: 'pay.scr.php?'+$.SK(),
		type: 'POST',
		beforeSerialize:function(){
			$('#msg')
				.removeClass('alert-box radius round warning success alert info secondary')
				.html('<div class="progress round"><span class="meter" style="width:0%"></span></div>');
		},
		uploadProgress:function(e,c,t,p){
			$('#msg>div>span').css({width: p+'%'});
		},
		success:function(msg){
			return $('#uploadForm').SKAjax(msg, true);
		}
	});
});