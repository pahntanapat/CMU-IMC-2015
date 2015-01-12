(function($){
	$.addDialog=function(html){
		$('<div class="dialog"><button>Close</button><div>'+html+'</div></div>')
			.appendTo('body').fadeIn();
		$('div.dialog, div.dialog>button').click(function(e) {
			if(e.target!=this) return;
			$('div.dialog, div.dialog').fadeOut(function(){$(this).remove();});
		});
		return $('div.dialog');
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