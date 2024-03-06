<!-- Forgot - User enters email to send a verification code to reset password -->
<?php 
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/account-functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/mail-functions.php");
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
<body>
    <header>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
    </header>
    <main>
        <div class="forgot-container">
            <div class="reset-form" novalidate>
                <div class="email-container">
                    <p>Enter your email to send a verification code to reset password.</p>
                    <p class="email">Email <input class="email-input" type="email" name="email"></p>
                    <input class="send-email" type="submit" value="Send Code">
                </div>
                <div class="password-container" style="display: none">
                    <p>Enter the verification code you have been emailed in order to proceed.</p>
                    <p class="code">Code <input class="code-input" type="text" name="code"></p> 
                    <p class="password">Password <input class="password-input" type="password" name="password"></p>
                    <p class="password">Confirm Password <input class="password2-input" type="password" name="password2"></p>
                    <input class="submit-pass" type="submit" value="Reset Password">
                </div>
                <p class="error" style="display: none"></p>
            </div>
        </div>
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>
</html>