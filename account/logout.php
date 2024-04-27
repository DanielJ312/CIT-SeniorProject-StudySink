<?php
//////////* Logout - Logs out user by unsetting session variables and destroying session *//////////
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/account-functions.php");

if ($_SESSION['USER']->Verified == 0) {
    $query = "DELETE FROM USER_T WHERE Email = '{$_SESSION['USER']->Email}';
            DELETE FROM CODE_T WHERE Email = '{$_SESSION['USER']->Email}';";
    run_database($query);
}
logout();
header("Location: login.php");
?>