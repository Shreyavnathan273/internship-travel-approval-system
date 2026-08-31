<?php
session_start();
session_destroy();
header("Location: login.html"); // or login.php
exit();
?>