<!-- Home - No current use other than for testing. -->
<?php
require($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
update_session();
$pageTitle = "Home";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
</head>
<body>
    <header>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
        <link rel="stylesheet" type="text/css" href="/styles/home-<?= !check_login() ? "logged-in" : "logged-out"; ?>.css">
    </header>
    <main>
        <?php if (!check_login()) : ?>
            <!-- LOGGED OUT HOME PAGE -->
            <h3>You are logged out.</h3>
        <?php else : ?>
            <!-- LOGGED IN HOME PAGE -->
            <h3>You are logged in.</h3>
        <?php endif; ?>
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>
</html>