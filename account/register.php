<!-- Register - User creates account by entering username, email, and password -->
<?php 
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/account-functions.php");
if (check_login()) header("Location: /account/profile.php"); 
$pageTitle = "Create Account";

$errors = ($_SERVER['REQUEST_METHOD'] == "POST") ? ((count($errors = signup($_POST)) == 0) ? header("Location: login.php") : $errors) : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
</head>
<body>
    <header>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
        <h2><?= isset($pageTitle) ? $pageTitle : "Page Header" ?></h2>
    </header>
    <main>
        <div>
            <?php display_errors($errors);?>
        </div>
        <form method="post">
            <p>Username: <input type="text" name="username"></p>
            <p>Email Address: <input type="email" name="email"></p>
            <p>Password: <input type="password" name="password"></p>
            <p>Confirm Password: <input type="password" name="password2"></p>
            <input type="submit" formnovalidate value="Register">
            <p>Already have an account? <a href="login.php">Sign in</a></p>
        </form>
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>
</html>