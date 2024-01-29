<!-- Verify - User recieves email with verifcation code to verify their account -->
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/account-functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/mail-functions.php");
if (!check_login()) header("Location: /account/login.php"); 
update_session();
$pageTitle = "Verify Account";

if ($_SERVER['REQUEST_METHOD'] == "GET" && !check_verification()) $expiredCode = is_code_active("verify", $_SESSION['USER']->Email);
$errors = $_SERVER['REQUEST_METHOD'] == "POST" ? verify_account() : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
    <link rel="stylesheet" type="text/css" href="/styles/account/verify.css">
</head>
<body>
    <header>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
        <h2><?=isset($pageTitle) ? $pageTitle : "Page Header" ?></h2>
    </header>
    <main>
        <div class="verify-container">    
        <div>
            <div>
                <?php display_errors($errors); ?>
            </div>
            <p>A verification code has been sent to your email. Enter the code below.</p>
            <form method="post">
                <?php if ($expiredCode == true): ?>
                    <p>Your previous verifcation code has expired. A new one has been sent.</p> 
                <?php else: ?>
                    <p>You currently have an active verifcation code.</p> 
                <?php endif; ?>
                <p>Code: <input type="text" name="code"></p>
                <input type="submit" value="Verify">
            </form>
            </div>
        </div>
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>
</html>