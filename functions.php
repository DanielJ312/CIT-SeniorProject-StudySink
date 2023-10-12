<?php
date_default_timezone_set('America/Los_Angeles');
session_start();
require($_SERVER['DOCUMENT_ROOT'] . "/mail.php");
require($_SERVER['DOCUMENT_ROOT'] . "/s3functions.php");

function run_database($query, $values = array()) {;
    $database = parse_ini_file('config.ini');
    $dbhost = $database['db_host'];
    $dbport = $database['db_port'];
    $dbname = $database['db_name'];
    $dbusername = $database['db_username'];
    $dbpassword = $database['db_password'];
    
    $server = "mysql:host=$dbhost;port=$dbport;dbname=$dbname;";
    $connection = new PDO($server, $dbusername, $dbpassword);

    if (!$connection)  {
        return false;
    }

    $statement = $connection->prepare($query);
    $check = $statement->execute($values);

    if ($check) {
        $data = $statement->fetchAll((PDO::FETCH_OBJ));
        if (count($data) > 0) {
            return $data;
        }
    }

    return false;
}

function check_login($redirect = true) {
    if (isset($_SESSION['USER']) && isset($_SESSION['LOGGED_IN'])) {
        return true;
    }
    else {
        return false;
    }
    
    if ($redirect) {
        header("Location: /account/login.php");
        // die;
    } else {
        return false;
    }
}

function update_session() {
    //finish
    if (isset($_SESSION['USER'])) {
        $values = array();
        $values['userid'] = $_SESSION['USER']->userid;
    
        $query = "SELECT * FROM user_t WHERE userid = :userid limit 1";
        $result = run_database($query, $values);
        $result = $result[0];
    
        $_SESSION['USER'] = $result;
        $_SESSION['LOGGED_IN'] = true;
    }
    
}

function check_verification() {
    $userid = $_SESSION['USER']->userid;
    $query = "SELECT * FROM user_t where userid = '$userid' limit 1 ";
    $result = run_database($query);
    if (is_array($result)) {
        $result = $result[0];
        if ($result->verified == 1) {
            return true;
        }
    }

    return false;
}

function send_code($type, $recipient) {
    $values['code'] = rand(10000, 99999);
    $values['expires'] = (get_local_time() + (60 * 1));
    $values['email'] = $recipient;
    $values['type'] = "$type";

    switch ($type) {
        case 'verify':
            $subject = "Verify Account";
            $message = <<<message
            <p>Hello <b>{$_SESSION['USER']->username}</b>,</p>
            Your account verification code is <b> {$values['code']}</b>.
            message;
            break;
        case 'reset':
            $subject = "Password Reset";
            $message = <<<message
            <p>Hello, <b>{$values['requestID']}</b></p>
            Your password reset verification code is  <b>{$values['code']}</b>.
            message;
            break;
        default:
            break;
    }
    delete_code($type, $recipient);

    $query = "INSERT INTO verify_t (code, type, expires, email) values (:code, :type, :expires, :email)";
    run_database($query, $values);
    send_mail($recipient, $subject, $message);
}

function is_code_active($type, $email) {
    $values['type'] = $type;
    $values['email'] = $email;

    $query = "SELECT * FROM verify_t WHERE type = :type AND email = :email";
    $result = run_database($query, $values);

    if (is_array($result) && get_local_time() < $result[0]->expires) {
        return true;
    }
    else {
        send_code($type, $email);
        return false;
    }
}

function delete_code($type, $email) {
    $query = "DELETE FROM verify_t WHERE type = '$type' AND email = '$email'";
    run_database($query);
}

function check_active_page($currectPage) {
    if ($currectPage == $_SERVER['REQUEST_URI']) {
        echo "active";
    }
}

function check_active_dir($dirToCheck) {
    if (str_contains($_SERVER['REQUEST_URI'], $dirToCheck)) {
        echo "active";
    }
}

function display_errors($errors) {
    if(count($errors) > 0) {
        foreach($errors as $errors) {
            echo "<p>$errors</p>";
        }
    }
}

function get_local_time() {
    return (time() - (60*60*7));
}

function display_time($time, $format) {
    return (new DateTime("@$time"))->format("$format");
}

function generate_ID($type) {
    do {
        switch ($type) {
            case 'user':
                $createdID = rand(101, 999);
                break;
            default:
                # code...
                break;
        }
        $query = "SELECT * FROM {$type}_t WHERE {$type}id = '$createdID' limit 1";
        $result = run_database($query);
        $result = $result[0];
    } while ($createdID == $result->userid);
    
    return $createdID;
}

function check_set_title($pageTitle) {
    return isset($pageTitle) ? $pageTitle : "Page Header";
}

?>
