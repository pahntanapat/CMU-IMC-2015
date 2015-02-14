$(document).ready(function(e) {
	$('#infoForm').submit(function(e) {
        e.preventDefault();
		if(!$('#birth').checkDate()){
			$('#msg').html('Date of birth is greater than today. Please fill out the correct date.');
			return false;
		}
		return $(this).postSK('member.scr.php?'+$.SK(),true);
    });
});