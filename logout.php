<?php
session_start();
unset($_SESSION['userid']);
unset($_SESSION['name']);
session_destroy();
header("Location: index.html");

?>