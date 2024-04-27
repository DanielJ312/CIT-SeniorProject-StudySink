<?php
//////////* Verify - User recieves email with verifcation code to verify their account *//////////
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/account-functions.php");
if (check_verification()) header("Location: /account/profile.php");
$expirationTime = update_session();
$errors = $_SERVER['REQUEST_METHOD'] == "POST" ? verify_email($_POST) : [];
$pageTitle = "Verify Account";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
    <link rel="stylesheet" type="text/css" href="/styles/account/verify.css">
    <script desync src="/account/account.js"></script>
</head>
<body class="verify-body">
    <header>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/to-top.php"); ?>
    </header>
    <main class="verify-main">
        <div class="verify-container">
            <div class="vheader"><?= isset($pageTitle) ? $pageTitle : "Page Header" ?></div>
            <div>
                <p>The email associated with this account must be verified before you can sign in. A verification code has been sent to <?= $_SESSION['USER']->Email; ?>.</p>
                <p>Your code expires in: <span class="countdown"></span>.</p>
                <div class="verify-form-error">
                    <?= isset($errors['code']) ? "<p>" . $errors['code'] . "</p>" : ""; ?>
                </div>
                <form class="code-input" method="post">
                    <div class="verify-code">
                        <p>Code</p>
                        <input class="code-input" type="text" name="code">
                    </div>
                    <input type="submit" value="Verify">
                </form>
                <div class="expired" style="display: none;">
                    <p>The verification code has expired and this account will now be deleted. You will be redirected to the Sign-Up page where you can begin the process again.</p>
                </div>
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