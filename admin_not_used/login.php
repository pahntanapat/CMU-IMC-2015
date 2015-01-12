<?php
require_once 'config.inc.php';
require_once 'class.Session.php';

if(Session::isLogIn(false) && isset($_GET['ajax'])) require_once 'login.scr.php';
?>
<!doctype html>
<html><!-- InstanceBegin template="/Templates/mahidol.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta charset="utf-8">
<!-- InstanceBeginEditable name="doctitle" -->
<title>การแข่งขันตอบปัญหาวิทยาศาสตร์สุขภาพ Mahidol Quiz 2014</title>
<!-- InstanceEndEditable -->
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="../login_not_used/js/jquery-ui-1.10.4.custom.min.js"></script>
<script src="../login_not_used/js/mahidol_ajax.js"></script>
<link rel="stylesheet" href="../mahidol_quiz.css">
<link href="../mahidol.css" rel="stylesheet" type="text/css">

<!-- InstanceBeginEditable name="head" -->
<script src="login.js"></script>
<!-- InstanceEndEditable -->

</head>

<body>
<div id="header"></div>
<div id="menu"></div>
<div id="content"><div id="Heading"><!-- InstanceBeginEditable name="Heading" -->
		        <h1><strong>Admin's log in page</strong></h1>
		      <!-- InstanceEndEditable --></div><div id="Content"><!-- InstanceBeginEditable name="Content" --> <form name="form1" method="post" action="login.php">
    <fieldset>
      <legend>Log in</legend>
      <div>
        <label for="std_id">Student ID: </label>
        <input type="text" name="std_id" id="std_id" required>
      </div>
      <div>
        <label for="pw">Password: </label>
        <input type="password" name="pw" id="pw" required>
      </div>
      <? require 'captcha.php'; ?>
      <div class="btnset"><button type="submit">Log in</button><button type="reset">Clear</button></div>
    </fieldset>
  </form><!-- InstanceEndEditable --></div></div>
<div id="footer"></div>
</body>
<!-- InstanceEnd --></html>
