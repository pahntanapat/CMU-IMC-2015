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
	$.fn.addSK=function(){
		var act=$(this).act();
		act+=act.indexOf('?')==-1?'?':'&';
		return act+$.SK();
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
	/**
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
	**/
	$.fn.postSK=function(url){
		var me=$(this).waitSK(null,null);
		return $.post(url,$(this).serialize(), function(data){
			return $(me).SKAjax(data, function(r, m){
				$(me).waitSK(r, m);
			});
		});
	};
	$.fn.postSKOriginal=function(url,success){
		var me=$(this).waitSK(null,null);
		return $.post(url,$(this).serialize(),function(data){
			return $(me).SKAjax(data, function(r, m){
				$(me).waitSK(null,null);
				if($.isFunction(success)) return success(r, m);
				else return r;
			});
		});
	};
	
	$.fn.loadSK=function(url,data){
		var me=$(this).waitSK(null,null);
		return $.get(url,data,function(data){
			return $(me).SKAjax(data, function(r, m){
				$(me).waitSK(r, m);
			});
		});
	};
	$.fn.loadSKOriginal=function(url,data,success){
		var me=$(this).waitSK(null,null);
		return $.get(url,data,function(data){
			return $(me).SKAjax(data, function(r, m){
				$(me).waitSK(null,null);
				if($.isFunction(success)) return success(r, m);
				else return r;
			});
		});
	};
	$.fn.waitSK=function(r, m){
		if($(this).data('waitsk')=='1'){
			$(this).removeData('waitsk');
			$('#waitsk').remove();
			if(m!=null)
				$('#'+m).removeClass('alert-box radius round warning success alert info secondary')
					.addClass('alert-box radius')
					.addClass((r==true?'success':(r==false?'alert':'info')))
					.append('<br/><small>Time: '+Date()+'</small>');
		}else{
			if($(this).data('waitsk','1').prop("tagName")=='FORM'){
				$(this).append(
					'<div id="waitsk" class="alert-box radius warning">'
					+'<i class="fa fa-spinner fa-pulse"></i>'
					+' Please wait. The registration system is processing.</div>'
				);
			}else{
				$(this).after(
					'<div id="waitsk" class="alert-box radius warning">'
					+'<i class="fa fa-spinner fa-pulse"></i>'
					+' Please wait. The registration system is processing.</div>'
				);
			}
			
		}
		return this;
	};
	/*
	$.fn.waitSK=function(result, msg){
		//if(typeof $(this).data('waitsk')=='undefined') 
		alert($(this).length);
		if($(this).removeClass('warning success alert info secondary').data('waitsk')==true){ // Remove Waiting message
			$(this).html(msg).data('waitsk',false)
				.addClass('alert-box radius')
				.addClass((result==true?'success':(result==false?'alert':'info')));
		}else{
			$(this).html('<i class="fa fa-spinner fa-pulse"></i> Please wait. The registration system is processing.')
				.addClass('alert-box warning radius').data('waitsk',true);
		}
		return function(result,msg){
			return $(this).waitSK(result,msg);
		};
	};
	$.fn.formWaitSK=function(result, msg){
		alert($(this).prop("tagName"));
		$('#'+$(this).data('waitsk')).waitSK(result, msg);return this;
	};
	*/
	
	
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