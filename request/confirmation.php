<?php
//////////* Request Confirmation - Displays confirmation request made *//////////
require($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
if (!isset($_SESSION['REQUEST'])) header("Location: /request/index.php");
else {
    $values = $_SESSION['REQUEST'];
    unset($_SESSION['REQUEST']);
}
$pageTitle = "Confirmation";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
    <link rel="stylesheet" href="/styles/request/confirmation.css">
</head>
<header>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
</header>
<body class="confirmation-body">
    <main class="confirmation-main">
        <div class="confirmation-container">
            <div class="confirmation-message">Thank you for contacting us!</div>

            <div class="confirmation-inner-container">
                <div class="confirmation-label">Request ID:</div>
                <div class="confirmation-value"><?= $values['RequestID'] ?></div>
                <div class="confirmation-label">Provided Email:</div>
                <div class="confirmation-value"><?= $values['Email'] ?></div>
                <?php if (($values['Message'] != null)) : ?>
                    <div class="confirmation-label">Message:</div>
                    <div class="confirmation-value"><?= $values['Message'] ?></div>
                <?php elseif (($values['University'] != null)) : ?>
                    <div class="confirmation-label">University:</div>
                    <div class="confirmation-value"><?= $values['University'] ?></div>
                    <div class="confirmation-label">University Address:</div>
                    <div class="confirmation-value"><?= $values['Address'] ?></div>
                    <div class="confirmation-label">Other Information:</div>
                    <div class="confirmation-value"><?= $values['OtherInfo'] ?></div>
                <?php endif; ?>
            </div>
            <div class="confirmation-message" style="margin-top: 3%;">We will get back to you within 2-5 business days.</div>
            <a href="/request/index.php" class="confirmation-return-button">Return to the Help page</a>
        </div>
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>
</html>