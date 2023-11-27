<!-- Forgot - User enters email to send a verification code to reset password -->
<?php 
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/account-functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/mail-functions.php");
check_login() ? header("Location: /account/profile.php") : null;
$pageTitle = "Reset Password";

$displayRedirect = false;
$errors = ($_SERVER['REQUEST_METHOD'] == "POST") ? ((count($errors = check_email($_POST)) == 0) ? (($displayRedirect = is_code_active("reset", $_POST['email']) === false) ? header("Location: reset.php") : []) : []) : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
    <link rel="stylesheet" type="text/css" href="/account/forgot.css">
</head>
<body>
    <header>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
        <h2><?= isset($pageTitle) ? $pageTitle : "Page Header" ?></h2>
    </header>
    <main>
        <div class="forgot-container">
        <div>
            <?php display_errors($errors); ?>
        </div>
        <form method="post" novalidate>
            <?php if ($displayRedirect): ?>
                <p>A verification code for this email is already active. <a href="reset.php">Change your password.</a></p> 
            <?php endif; ?>
            <h3>Enter your email to reset your password</h3>
            <p>Email:&nbsp; <input type="email" name="email"></p>
            <input type="submit" value="Submit">
        </form>
            </div>
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>
</html>