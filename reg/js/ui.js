(function($){
	$.fn.setSidebar=function(){
		if($(window).width()/$(this).width()>3){
			//Large Screen
			$(this).find('ul.accordion>li>div').addClass('active');
		}else{ // Medium - Small Screen
			$(this).find('ul.accordion>li>div').removeClass('active').first().addClass('active');
		}
	};
	$.addDialog=function(id){
		if($('#'+id).length==0) $('<div id="'+id+'" class="reveal-modal" data-reveal></div>').appendTo('body');
		return $('#'+id).prepend('<a class="close-reveal-modal" href="#">&#215;</a>').foundation('reveal', 'open');
	};
	$.checkDate=function(date){
		date=date.trim();
		return date.length>0?(date.match(/^\d{4}[\-](0?[1-9]|1[012])[\-](0?[1-9]|[12][0-9]|3[01])$/)?true:false):true;
	};
	$.checkDateTime=function(d){
		d=d.trim();
		return d.length>0?(d.match(/^\d{4}[\-](0?[1-9]|1[012])[\-](0?[1-9]|[12][0-9]|3[01]) ([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/)?true:false):true;
	};
	$.fn.checkDate=function(){
		var result=true;
		$(this).each(function(index, element) {
            result&=$.checkDate($(element).val());
			return result;
        });
		return result;
	};
	$.fn.checkDateTime=function(){
		var result=true;
		$(this).each(function(index, element) {
            result&=$.checkDateTime($(element).val());
			return result;
        });
		return result;
	};
/*	$.fn.tabs=function(){
		var me=this;
		$(this).addClass('tabs').find('ul>li>a').click(function(e) {
            $(me).children('div').removeClass('active');
			$(me).children($(this).attr('href')).addClass('active');
			e.preventDefault();
        }).first().click();
		return me;
	};*/
	$.datetimepicker=function(){
		$('input[type="datetime"], input.datetime').mask('9999-99-99 99:99:99', {placeholder:'YYYY-MM-DD hh:mm:ss'});
		$('input[type="date"], input.date').mask('9999-99-99', {placeholder:'YYYY-MM-DD'})
			.fdatepicker({format:'yyyy-mm-dd',viewMode:'years'});
	};
}(jQuery));
$(window).resize(function(e) {
	$('#sidebar').setSidebar();
});
function loadScript(){
	try{
		$(document).foundation();
		$.datetimepicker();
	}catch(e){
		return e;
	}
}