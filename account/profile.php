<?php
//////////* Profile - Redirects to the profile template *//////////
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
!check_login() ? header("Location: /account/login.php") : header("Location: /account/{$_SESSION['USER']->Username}.php");
// include($_SERVER['DOCUMENT_ROOT'] . "/account/profile.php");
?>