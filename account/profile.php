<!-- Profile - Display the logged in user's profile page -->
<?php
// require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
// require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/account-functions.php");
// update_session();
// if (!check_login()) header("Location: /account/login.php");
$pageTitle = "Profile";
// get user info by passing in a user id
include($_SERVER['DOCUMENT_ROOT'] . "/account/template.php");
?>

