<!-- Verify - User recieves email with verifcation code to verify their account -->
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/account-functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/mail-functions.php");
if (check_verification()) header("Location: /account/profile.php"); 

$expirationTime = update_session();
$pageTitle = "Verify Account";


$errors = $_SERVER['REQUEST_METHOD'] == "POST" ? verify_email($_POST) : [];

// $query = "SELECT Expires FROM CODE_T WHERE Email = '{$_SESSION['USER']->Email}';";
// $expirationTime = run_database($query)[0]->Expires; 
// echo $expirationTime;
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

function updateCountdown(endTimeUnix) {
    var nowUnix = Math.floor(Date.now() / 1000);
    var timeLeft = endTimeUnix - nowUnix;
    if (timeLeft <= 0) {
        $(".countdown").html("Expired");
        clearInterval(timer);
        return;
    }

    var days = Math.floor(timeLeft / 86400);
    var hours = Math.floor((timeLeft % 86400) / 3600);
    var minutes = Math.floor((timeLeft % 3600) / 60);
    var seconds = timeLeft % 60;

    // $(".countdown").html(days + " days " + hours + " hours " + minutes + " minutes " + seconds + " seconds");
    $(".countdown").html(hours + " hours, " + minutes + " minutes, and " + seconds + " seconds remaining");
}

// Update the countdown every second
var timer = setInterval(function() {
    updateCountdown(<?= $expirationTime; ?>);
}, 1000);
</script>
</html>