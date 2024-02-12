<!-- Login - Users enter account information to login with either email or username -->
<?php 
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/account-functions.php");
update_session();
$pageTitle = "Login";

$errors = ($_SERVER['REQUEST_METHOD'] == "POST") ? login($_POST) : [];
if (check_login()) header("Location: /account/profile.php"); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
    <link rel="stylesheet" type="text/css" href="/styles/account/login.css">
</head>
<body>

    <header id="header">
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
        <h2><?= isset($pageTitle) ? $pageTitle : "Page Header" ?></h2>
    </header>

    <main>
        <section id=login>
    <div class="login-container">
        <div>
            <?php display_errors($errors); ?>
        </div>
        <form method="post">
            <p>Email: &nbsp; &nbsp; &nbsp; &nbsp;   <input type="text" name="logininput"></p>
            <p>Password:&nbsp;&nbsp; <input type="password" name="password"></p>
            <input type="submit" value="Login">
            <p>Don't have an account? <a href="register.php">Sign up</a></p>
            <p>Forgot password? <a href="forgot.php">Reset password</a></p>
        </form>
    </div>

</section>
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>
</html>