<?php
require 'check_session.php';
CheckSession::destroy();
header("Location: login.php");
exit("You have just logged out.");
?>