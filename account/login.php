<!-- Login - Users enter account information to login with either email or username -->
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/account-functions.php");
update_session();
$pageTitle = "Login";

$errors = ($_SERVER['REQUEST_METHOD'] == "POST") ? login($_POST) : [];
if (check_login()) header("Location: /index.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
    <link rel="stylesheet" type="text/css" href="/styles/account/login.css">
</head>

<body class="body-login">
    <header id="header">
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
    </header>
    <main class="main-login">
        <section id=login>
            <div class="login-container">
                <div class="lheader"><?= isset($pageTitle) ? $pageTitle : "Page Header" ?></div>
                <form method="post">
                    <div class="login-form-error" style="margin-top: 10px;">
                        <?= isset($errors['logintype']) ? "<p>" . $errors['logintype'] . "</p>" : ""; ?>
                    </div>
                    <div class="login-email-container">
                        <p style="white-space: nowrap;">Username/Email</p>
                        <input type="text" name="logininput">
                    </div>
                    <div class="login-form-error">
                        <?= isset($errors['password']) ? "<p>" . $errors['password'] . "</p>" : ""; ?>
                    </div>
                    <div class="login-password-container">
                        <p>Password</p>
                        <input type="password" name="password">
                    </div>
                    <input type="submit" value="Login">
                    <div class="login-dontforgot">
                        <p>Don't have an account? <a href="register.php">Sign up</a></p>
                        <p>Forgot password? <a href="forgot.php">Reset password</a></p>
                    </div>
                </form>
            </div>
        </section>
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>

</html>