<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function send_mail($recipient, $subject, $message) {
    $phpmailer = parse_ini_file('config.ini');
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->Host = $phpmailer['mailer_host'];
    $mail->Port = $phpmailer['mailer_port'];
    $mail->SMTPAuth   = TRUE;
    $mail->SMTPSecure = "tls";
    $mail->Username   = $phpmailer['mailer_username'];
    $mail->Password   = $phpmailer['mailer_password'];

    $mail->IsHTML(true);
    $mail->AddAddress($recipient);
    $mail->SetFrom("StudySinkLLC@gmail.com", "StudySink LLC");
    //$mail->AddReplyTo("reply-to-email", "reply-to-name");
    //$mail->AddCC("cc-recipient-email", "cc-recipient-name");
    $mail->Subject = $subject;
    $content = $message;

    $mail->MsgHTML($content);
    if (!$mail->Send()) {
        //echo "Error while sending Email.";
        //echo "<pre>";
        //var_dump($mail);
        return false;
    } else {
        //echo "Email sent successfully";
        return true;
    }
}

?>
