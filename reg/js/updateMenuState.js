(function($){
	$.updateMenuState=function(json){
		if(typeof json=="string") json=$.parseJSON(json);
		var css=json[0].join(" ");
		for(k in json[1]){
			$('#'+k).removeClass(css).addClass(json[1][k]);
		}
	};
}(jQuery));