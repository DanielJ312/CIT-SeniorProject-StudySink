<?php
# 
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/mail-functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/account-functions.php");

// Switch for deciding which function to run in AJAX
if (isset($_POST['function'])) {
    switch ($_POST['function']) {
        case "email":
            email_code($_POST);
            break;
        case "password":
            update_password($_POST);
            break;
        default:
            break;
    }
}

function email_code($data) {
    if (check_email($data) == true) {
        send_verify_code("reset", $data['email']);
        echo "true";
    }
    else {
        echo "false";
    }
}

function update_password($data) {
    echo reset_password($data);
}

