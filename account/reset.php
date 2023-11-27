<!-- Reset - User enters verification code and new password to change password -->
<?php 
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/account-functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/mail-functions.php");
check_login() ? header("Location: /account/profile.php") : null;
$pageTitle = "Reset Password";

$errors = ($_SERVER['REQUEST_METHOD'] == "POST") ? ((count($errors = reset_password($_POST)) == 0) ? header("Location: login.php") : $errors) : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
    <link rel="stylesheet" type="text/css" href="/account/reset.css">
</head>
<body>
    <header>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
        <h2><?= isset($pageTitle) ? $pageTitle : "Page Header" ?></h2>
    </header>
    <main>
    <div class="reset-container">
        <div>
            <?php display_errors($errors); ?>
        </div>
        <form method="post" novalidate>
            <p>Code: &nbsp; &nbsp; &nbsp;  <input type="text" name="code"></p> 
            <p>Password:  <input type="password" name="password"></p>
            <p>Confirm Password: <input type="password" name="password2"></p>
            <input type="submit" value="Reset Password">
        </form>
</div>
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>
</html>