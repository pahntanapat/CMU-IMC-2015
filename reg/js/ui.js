(function($){
	$.addDialog=function(html){
		return $('<div class="reveal-modal" data-reveal>'+html+'<a class="close-reveal-modal">&#215;</a></div>')
			.appendTo('body').foundation('reveal', 'open');
	};
	$.checkDate=function(date){
		return date.match(/^\d{4}[\-](0?[1-9]|1[012])[\-](0?[1-9]|[12][0-9]|3[01])$/)?true:false;
	};
	$.fn.checkDate=function(){
		return $.checkDate($(this).val());
	};
	$.fn.tabs=function(){
		var me=this;
		$(this).addClass('tabs').find('ul>li>a').click(function(e) {
            $(me).children('div').removeClass('active');
			$(me).children($(this).attr('href')).addClass('active');
			e.preventDefault();
        }).first().click();
		return me;
	};
}(jQuery));
$(document).ready(function(e) {
    $('input[type="date"]').fdatepicker({format:'yyyy-mm-dd',viewMode:'years'});
});