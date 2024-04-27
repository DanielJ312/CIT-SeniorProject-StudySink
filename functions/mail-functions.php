<?php
//////////* Mail Functions - Contains functions relating to PHPMailer and sending messages *//////////
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] ."/vendor/autoload.php");

use PHPMailer\PHPMailer\PHPMailer;

function send_mail($recipient, $subject, $message) {
    // Server Settings
    $phpmailer = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . "/config.ini");
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->Host = $phpmailer['mailer_host'];
    $mail->Port = $phpmailer['mailer_port'];
    $mail->SMTPAuth   = TRUE;
    $mail->SMTPSecure = "tls";
    $mail->Username   = $phpmailer['mailer_username'];
    $mail->Password   = $phpmailer['mailer_password'];

    //Recipients
    $mail->SetFrom("StudySinkLLC@gmail.com", "StudySink LLC");
    $mail->AddAddress($recipient);

    //Content
    $mail->IsHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $message;
    $mail->Send();
}

function submit_request($data) {
    $errors = array();
    if (empty($data['email'])) {
        $errors[] = "Please enter an email.";
    }

    if (count($errors) == 0) {
        $values['Email'] = $data['email'] ?? null;
        $values['University'] = $data['universityName'] ?? null;
        $values['Address'] = $data['universityAddress'] ?? null;
        $values['OtherInfo'] = $data['otherInfo'] ?? null;
        $values['Message'] = $data['description'] ?? null;
        $IDnum = rand(10000, 9999999);

        if ($values['Message'] != null) {
            $values['RequestID'] = "H$IDnum";
            $subject = "Help Request - ID: " . $values['RequestID'];
            $message = <<<message
                <p><b>Request #</b>: {$values['RequestID']}</p>
                <p><b>Email Provided</b>: {$values['Email']}</p>
                <p><b>Message</b>: {$values['Message']}</p>
            message;
        } 
        else {
            $values['RequestID'] = "REQ$IDnum";
            $subject = "University Request - ID: " . $values['RequestID'];
            $message = <<<message
                <p><b>Request #</b>: {$values['RequestID']}</p>
                <p><b>Email Provided</b>: {$values['Email']}</p>
                <p><b>University</b>: {$values['University']}</p>
                <p><b>University Address</b>: {$values['Address']}</p>
                <p><b>Other Information</b>: {$values['OtherInfo']}</p>
            message;
        }

        send_mail("StudySinkLLC@gmail.com", $subject, $message, true);
        send_mail($values['Email'], "[User Copy] " . $subject, $message, true);

        $_SESSION['REQUEST'] = $values;
        header("Location: /request/confirmation.php");
    }
}

function send_verify_code($type, $recipient) {
    $values['Code'] = rand(10000, 99999);
    $values['Expires'] = (time() + (60 * 10));
    $values['Email'] = $recipient;
    $values['Type'] = "$type";
    $expireTime = date('Y-m-d H:i:s', $values['Expires']);

    switch ($type) {
        case 'verify':
            $subject = "Verify Account";
            $message = <<<message
            <p>Hello <b>{$_SESSION['USER']->Username}</b>,</p>
            Your account verification code is <b> {$values['Code']}</b>.
            Please verify your account before $expireTime.
            message;
            break;
        case 'reset':
            $subject = "Password Reset";
            $message = <<<message
            <p>Hello,
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

function delete_code($type, $email) {
    $query = "DELETE FROM CODE_T WHERE type = '$type' AND Email = '$email';";
    run_database($query);
}

?>