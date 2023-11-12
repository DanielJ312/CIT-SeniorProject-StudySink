<?php
# Functions - Contains functions relating to PHPMailer
require ($_SERVER['DOCUMENT_ROOT'] ."/vendor/autoload.php");

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
?>