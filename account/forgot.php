<?php 
//////////* Forgot - User enters email to send a verification code to reset password *//////////
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
if (check_login()) header("Location: /account/profile.php"); 
$pageTitle = "Reset Password";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
    <link rel="stylesheet" type="text/css" href="/styles/account/forgot.css">
    <script async src="/account/account.js"></script>
</head>
<body class="forgot-body">
    <header>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/to-top.php"); ?>
    </header>
    <main class="forgot-main">
        <div class="forgot-container">
            <div class="reset-form" novalidate>
                <div class="email-container">
                    <p>Enter your account email to recieve a verification code.</p>
                    <p class="email">Email <input class="email-input" type="email" name="email"></p>
                    <input class="send-email" type="submit" value="Send Code">
                </div>
                <div class="password-container" style="display: none">
                    <p>The code has been sent</p>
                    <div class="reset-code">
                        <p class="code">Code</p>
                        <input class="code-input" type="text" name="code"> 
                    </div>
                    <div class="reset-password">
                        <p class="password">New Password</p>
                        <input class="password-input" type="password" name="password">
                    </div>
                    <div class="reset-password2">
                        <p class="password">Confirm Password</p>
                        <input class="password2-input" type="password" name="password2">
                    </div>
                    <input class="submit-pass" type="submit" value="Reset Password">
                </div>
                <p class="error" style="display: none; color: red;"></p>
            </div>
        </div>
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>
</html>