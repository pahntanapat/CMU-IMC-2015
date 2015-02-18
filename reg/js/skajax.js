(function($){
	$.fn.id=function(){
		return $(this).attr('id');
	};
	$.SK=function(){
		return "ajax="+Math.random();
	};
	$.fn.act=function(){
		if($(this).is('form'))	return $(this).attr('action');
		else if($(this).is('a')) return $(this).attr('href');
		else	return this;
	};
	$.fn.SKAjax=function(data,callback){
		if(typeof data =="string"){
			try{
				data=$.parseJSON(data);
			}catch(e){
				return callback(true,data);
			}
		}
		var recall=false;
		for(var k in data.action){
			var json=data.action[k];
			switch(json.act){
				case "alert":
					alert(json.message);continue;
				case "redirect":
					window.location=json.url;continue;
				case "eval":
					$.globalEval(json.script);continue;
				case "setText":
					$(json.selector).text(json.message);continue;
				case "setHTML":
					$(json.selector).html(json.message);continue;
				case "setVal":
					$(json.selector).val(json.message);continue;
				case "resetForm":
					$(this).find(":reset").click();
				case "reloadCAPTCHA":
					 $(this).find("#reload_captcha").click().click();continue;
				case "scrollTo":
					$("html, body").animate({scrollTop:$(json.selector).offset().top},"fast");continue;
				case "focus":
					$(json.selector).focus();continue;
				case "recall":
					recall=json.call;continue;
				/*
				case "":
					continue;
				*/
				default:
			}
		}
		if(recall) return callback(recall);
		if($.isFunction(callback))
			return callback(data.result,data.message);
		else if(callback==true)
			return data.result;
		return callback;
	};
	$.fn.ajaxSK=function(setting){
		var me=this;
		return $.ajax($.extend({},setting,{
			success:function(data){
				return $(me).SKAjax(data,f);
			}
		}));
	};
	$.fn.postSK=function(url,success){
		var me=this;
		return $.post(url,$(this).serialize(),function(data){
			return $(me).SKAjax(data,success);
		});
	};	
	$.fn.loadSK=function(url,data,success){
		var me=this;
		return $.get(url,data,function(data){
			return $(me).SKAjax(data,success);
		});
	};
	$.fn.getSK=function(url,success){
		return $(this).loadSK(url,$(this).serialize(),success);
	};
	$.fn.checkAll=function(checkbox){
		return $(this).click(function(e) {
            if($(this).data("checked")===1){
				$(checkbox).prop("checked",false);
				$(this).data("checked",0).text("Select All");
			}else{
				$(checkbox).prop("checked",true);
				$(this).data("checked",1).text("Unselect All");
			}
			return false;
        }).data("checked",0).text("Select All");
	};
	$.query=function(str){
		return str.replace('#','').split('?',2)[1];
	};
	$.fn.query=function(){
		return $.query($(this).act());
	};
}(jQuery));
$(function(e) {
    $("#reload_captcha").click(function(e) {
        $("#captchaIMG").attr("src",$("#captchaIMG").attr("src").split('?')[0]+'?'+Math.random());
		$("#captcha").val('');
    });
});