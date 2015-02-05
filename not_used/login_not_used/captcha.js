$(document).ready(function(e) {
    $("#reload_img").click(function(e){
		$("#siimage").prop("src",$("#siimage").attr("src").split('?')[0]+'?'+(Math.random()));
		$("#captcha").val("");
	});
});