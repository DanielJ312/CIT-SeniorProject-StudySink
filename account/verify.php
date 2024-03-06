<!-- Verify - User recieves email with verifcation code to verify their account -->
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/account-functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/mail-functions.php");
if (check_verification()) header("Location: /account/profile.php"); 
$expirationTime = update_session();
$pageTitle = "Verify Account";

$errors = $_SERVER['REQUEST_METHOD'] == "POST" ? verify_email($_POST) : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
    <link rel="stylesheet" type="text/css" href="/styles/account/verify.css">
    <script desync src="/account/account.js"></script>
</head>
<body>
    <header>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
        <h2><?=isset($pageTitle) ? $pageTitle : "Page Header" ?></h2>
    </header>
    <main>
        <div class="verify-container">    
        <div>
            <p>Your email must be verified before you will be allowed to continue using the website. A verifcation code has been sent to the email <?= $_SESSION['USER']->Email; ?>.</p>
            <p>You have: <span class="countdown"></span>.</p>
            <form method="post">
                <p>Code: <input type="text" name="code"></p>
                <?= isset($errors['code']) ? "<p>" . $errors['code'] . "</p>": ""; ?>
                <input type="submit" value="Verify">
            </form>
            </div>
        </div>
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>
<script>
updateCountdown(<?= $expirationTime; ?>);

// Update the countdown every second
var timer = setInterval(function() {
    updateCountdown(<?= $expirationTime; ?>);
}, 1000);
</script>
</html>