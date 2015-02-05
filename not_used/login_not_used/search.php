<?php $_GET['q']=trim(@$_GET['q']);?><!doctype html>
<html><!-- InstanceBegin template="/Templates/mahidol.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta charset="utf-8">
<!-- InstanceBeginEditable name="doctitle" -->
<title><? if(strlen($_GET['q'])>0):?>ผลการค้นหา <? echo $_GET['q'];else:?>ค้นหา<? endif;?> - การแข่งขันตอบปัญหาวิทยาศาสตร์สุขภาพ Mahidol Quiz 2014</title>
<!-- InstanceEndEditable -->
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="js/jquery-ui-1.10.4.custom.min.js"></script>
<script src="js/mahidol_ajax.js"></script>
<link rel="stylesheet" href="../mahidol_quiz.css">
<link href="../mahidol.css" rel="stylesheet" type="text/css">

<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->

</head>

<body>
<div id="header"></div>
<div id="menu"></div>
<div id="content"><div id="Heading"><!-- InstanceBeginEditable name="Heading" --><? if(strlen($_GET['q'])>0):?>ผลการค้นหา <? echo $_GET['q'];else:?>ค้นหา<? endif;?><!-- InstanceEndEditable --></div><div id="Content"><!-- InstanceBeginEditable name="Content" --> <form action="search.php" method="get" name="search" class="left" id="search">
    <label for="q">ค้นหา</label>
    <input name="q" type="text" id="q" placeholder="กรุณากรอก keyword" value="<?=$_GET['q']?>"><button type="submit">Search!</button>
  </form>
  <script>
  $(document).ready(function(e) {
    $(':submit,:reset,:button').button();
	$('#search label').css({"min-width":"0px"});
});
  (function() {
    var cx = 'partner-pub-5867850403191105:8150931399';
    var gcse = document.createElement('script');
    gcse.type = 'text/javascript';
    gcse.async = true;
    gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
        '//www.google.com/cse/cse.js?cx=' + cx;
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(gcse, s);
  })();
</script>
<gcse:searchresults-only></gcse:searchresults-only><!-- InstanceEndEditable --></div></div>
<div id="footer"></div>
</body>
<!-- InstanceEnd --></html>
