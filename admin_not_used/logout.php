<?php
require 'class.Session.php';
Session::destroy();
header('Location: login.php');
exit('You have already logged out.');
?>