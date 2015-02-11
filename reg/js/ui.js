(function($){
	$.addDialog=function(html){
		return $('<div class="reveal-modal" data-reveal>'+html+'<a class="close-reveal-modal">&#215;</a></div>')
			.appendTo('body').foundation('reveal', 'open');
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