<!-- Profile - Display the logged in user's profile page -->
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
update_session();
if (!check_login()) header("Location: /account/login.php");
$pageTitle = "Profile";

$username = isset($_GET['url']) ? basename($_GET['url'], '.php') : 'default';
$query = "SELECT * FROM USER_T WHERE Username = '{$username}'; LIMIT 1";
$user = run_database($query)[0];
// header("Location: /account/$user->Username")
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
            <h4>Account Information</h4>
            <p>UserID: <?= $user->UserID; ?></p>
            <p>Username: <?= $user->Username; ?></p>
            <p>Email: <?= $user->Email; ?></p>
            <p>Password: <?= $user->Password; ?></p>
            <p>Verified: <?= $user->Verified == 1 ? "Yes" : "No" ?></p>
            <p>Account Created: <?= date('F j, Y @ h:i:s A', $user->Created); ?></p>
            <p>Avatar: <?= $user->Avatar; ?></p>
            <img src="<?= $user->Avatar; ?>">
        </div>
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>
<script>
    window.history.pushState({}, '', '/account/<?= $user->Username; ?>');
</script>
</html>