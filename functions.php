<?php
date_default_timezone_set('America/Los_Angeles');

session_start();
require($_SERVER['DOCUMENT_ROOT'] . "/mail.php");

function run_database($query, $values = array()) {;
    $server = "mysql:host=localhost;dbname=test";
    $connection = new PDO($server, 'root', '');

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
    if ($redirect) {
        header("Location: /account/login.php");
        die;
    } else {
        return false;
    }
}

function check_verification() {
    // if ($_SESSION['USER']->email = $_SESSION['USER']->verified) {
    // $temp = $_SESSION['USER']->verified;
    // echo "$temp";
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
    $values['expires'] = (time() + (60 * 5));
    $values['email'] = $recipient;

    switch ($type) {
        case 'verify':
            $values['type'] = "verify";
            $message = "Your verification code is <b>" . $values['code'] . "</b>.";
            $subject = "Verify Account";
            break;
        case 'reset':
            $values['type'] = "reset";
            $message = "Your verification code to reset your password is " . $values['code'] . ".";
            $subject = "Password Reset";
            break;
        default:
            break;
    }
    
    $query = "INSERT INTO verify_t (code, type, expires, email) values (:code, :type, :expires, :email)";
    run_database($query, $values);
    send_mail($recipient, $subject, $message);
}

function update_session() {
    //finish
    $values = array();
    $values['userid'] = $_SESSION['USER']->userid;

    $query = "SELECT * FROM user_t WHERE userid = :userid limit 1";
    $result = run_database($query, $values);
    $result = $result[0];

    $_SESSION['USER'] = $result;
    $_SESSION['LOGGED_IN'] = true;
}

function check_page($currect_page){
    $url_array =  explode('/', $_SERVER['REQUEST_URI']) ;
    $url = end($url_array);  
    if($currect_page == $url){
        echo 'active'; //class name in css 
    } 
}

function check_active_page($currectPage) {
    if ($currectPage == $_SERVER['REQUEST_URI']) {
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

?>
