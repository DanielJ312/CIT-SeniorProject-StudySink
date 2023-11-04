<?php
# Logout - Logs out user by unsetting session variables and destroying session 
session_start();

unset($_SESSION['USER']);
unset($_SESSION['LOGGED_IN']);

session_destroy();

header("Location: login.php");
?>