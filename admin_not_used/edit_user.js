$(document).ready(function(e) {
	$('#reloadTable').button({icons:{primary:'ui-icon-refresh'}});
	$(document).on('click','#reloadTable',function(e){
		$('.main_content').fadeOut("fast",function(){
			$.get('edit_user.scr.php',{reload:Math.random()},function(data){
				$('.main_content').html(data).fadeIn("slow").find('#reloadTable').button({icons:{primary:'ui-icon-refresh'}});
			});
		});
		return false;
	}).on('click','.cnf',function(e) { //ลบทีม
		e.preventDefault();
        if(!confirm("ต้องการ"+$(this).attr("title")+"มั้ย?")) return;
		$.get($(this).attr('href'),{ajax:Math.random()},function(data){
			$(e.target).processJSON(data,false);
			$('#reloadTable').click();
		});
    }).on('click','a.open',function(e){
		e.preventDefault();
		return window.open($(this).attr('href'),$(this).attr('target'));
	});
});