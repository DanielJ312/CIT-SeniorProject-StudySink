<?php
# Functions - Contains functions that are used by multiple pages
date_default_timezone_set('America/Los_Angeles');
session_start();
require($_SERVER['DOCUMENT_ROOT'] . "/functions/mail-functions.php");

function run_database($query, $values = array()) {;
    $database = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . "/config.ini");;
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

function check_login() {
    $loggedIn = false;
    if (isset($_SESSION['USER']) && isset($_SESSION['LOGGED_IN'])) {
        $loggedIn = true;
    }
    return $loggedIn;
}

function update_session() {
    //finish
    if (isset($_SESSION['USER'])) {
        $values = array();
        $values['UserID'] = $_SESSION['USER']->UserID;
    
        $query = "SELECT * FROM USER_T WHERE UserID = :UserID LIMIT 1;";
        $result = run_database($query, $values);
        $result = $result[0];
    
        $_SESSION['USER'] = $result;
        $_SESSION['LOGGED_IN'] = true;
    }
    
}

function check_verification() {
    $userid = $_SESSION['USER']->UserID;
    $query = "SELECT * FROM USER_T where UserID = '$userid' LIMIT 1;";
    $result = run_database($query);
    if (is_array($result)) {
        $result = $result[0];
        if ($result->Verified == 1) {
            return true;
        }
    }

    return false;
}

function send_code($type, $recipient) {
    $values['Code'] = rand(10000, 99999);
    $values['Expires'] = (get_local_time() + (60 * 1));
    $values['Email'] = $recipient;
    $values['Type'] = "$type";

    switch ($type) {
        case 'verify':
            $subject = "Verify Account";
            $message = <<<message
            <p>Hello <b>{$_SESSION['USER']->Username}</b>,</p>
            Your account verification code is <b> {$values['Code']}</b>.
            message;
            break;
        case 'reset':
            $subject = "Password Reset";
            $message = <<<message
            <p>Hello, <b>{$_SESSION['USER']->Username}</b></p>
            Your password reset verification code is  <b>{$values['Code']}</b>.
            message;
            break;
        default:
            break;
    }
    delete_code($type, $recipient);

    $query = "INSERT INTO CODE_T (Code, Type, Email, Expires) values (:Code, :Type, :Email, :Expires);";
    run_database($query, $values);
    send_mail($recipient, $subject, $message);
}

function is_code_active($type, $email) {
    $values['Type'] = $type;
    $values['Email'] = $email;

    $query = "SELECT * FROM CODE_T WHERE Type = :Type AND Email = :Email;";
    $result = run_database($query, $values);

    if (is_array($result) && get_local_time() < $result[0]->Expires) {
        return true;
    }
    else {
        send_code($type, $email);
        return false;
    }
}

function delete_code($type, $email) {
    $query = "DELETE FROM CODE_T WHERE type = '$type' AND Email = '$email';";
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
        $createdID = '';
        switch ($type) {
            case 'USER':
                $createdID = rand(101, 999);
                $query = "SELECT * FROM USER_T WHERE UserID = :createdID limit 1";
                break;
            case 'STUDY_SET':
                $createdID = time() + mt_rand(1000, 9999);
                $query = "SELECT * FROM STUDY_SET_T WHERE StudySetID = :createdID limit 1";
                break;
            default:
                throw new Exception("Invalid ID type specified.");
                break;
        }

        $result = run_database($query, [':createdID' => $createdID]);
        
    } while (!empty($result));

    return $createdID;
}

function check_set_title($pageTitle) {
    return isset($pageTitle) ? $pageTitle : "Page Header";
}

function get_universities_list() {
    $query = "SELECT UniversityID, Name FROM UNIVERSITY_T ORDER BY Name ASC";
    return run_database($query);
}
?>