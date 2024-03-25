<?php
# 
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/mail-functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/account-functions.php");

// Switch for deciding which function to run in AJAX
if (isset($_POST['function'])) {
    switch ($_POST['function']) {
        case "email":
            if (check_email($_POST) == true) {
                send_verify_code("reset", $_POST['email']);
                echo "true";
            }
            else {
                echo "false";
            }
            break;
        case "password":
            echo reset_password($_POST);
            break;
        default:
            break;
    }
}

?>