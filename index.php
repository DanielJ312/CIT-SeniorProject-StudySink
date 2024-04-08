<?php
//////////* Home - Displays home content depending on a user is logged in or logged out */////////
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/study-set-functions.php");
update_session();
$pageTitle = "Home";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
    <link rel="stylesheet" type="text/css" href="/styles/home/<?= !check_login() ? "logged-out" : "logged-in"; ?>.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Concert+One&display=swap" rel="stylesheet">
    <script defer src="/home.js"></script>
</head>
<body>
    <header id="home-logout-header">
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
    </header>
    <main>
    <?php if (!check_login()) : ?>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/home/logged-out.php"); ?>
    <?php else : ?>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/home/logged-in.php"); ?>
    <?php endif; ?>
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>
</html>