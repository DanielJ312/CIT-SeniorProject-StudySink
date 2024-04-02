<!-- Profile - Display the logged in user's profile page -->
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
update_session();
if (!check_login()) header("Location: /account/login.php");
$pageTitle = "Profile";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
</head>
<body>
    <header>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
        <h2>Profile</h2>
    </header>
    <main>
        <div>
            <p><?= check_login() ? "Hello <b>" . $_SESSION['USER']->Username . "</b>. You are logged in." : "" ?></p>
        </div>
        <div>
            <h4>Account Information</h4>
            <p>UserID: <?= $_SESSION['USER']->UserID ?></p>
            <p>Username: <?= $_SESSION['USER']->Username ?></p>
            <p>Email: <?= $_SESSION['USER']->Email ?></p>
            <p>Password: <?= $_SESSION['USER']->Password ?></p>
            <p>Verified: <?= $_SESSION['USER']->Verified == 1 ? "Yes" : "No" ?></p>
            <p>Account Created: <?= date('F j, Y @ h:i:s A', $_SESSION['USER']->Created); ?></p>
            <p>Avatar: <?= $_SESSION['USER']->Avatar ?></p>
            <img src="<?= $_SESSION['USER']->Avatar ?>">
        </div>
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>
</html>