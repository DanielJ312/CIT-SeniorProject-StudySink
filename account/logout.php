<?php 
session_start();

if (isset($_SESSION['USER'])) {
    unset($_SESSION['USER']);
}

if (isset($_SESSION['LOGGED_IN'])) {
    unset($_SESSION['LOGGED_IN']);
}

session_destroy();

header("Location: login.php");

?>