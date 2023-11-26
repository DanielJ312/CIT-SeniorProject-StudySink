<!-- Home - No current use other than for testing. -->
<?php
require($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
update_session();
$pageTitle = "Home";

$users = run_database("SELECT * FROM USER_T;");
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
            <p>Welcome to the home page for StudySink Development.</p>
            <h3 style="color: red">This page currently has no use and instead lists all created users.</h3>
            <table>
                <h3>Created Users</h3>
                <tr>
                    <th>Avatar</th><th>UserID</th><th>Username</th><th>Email</th>
                    <th>Verified</th><th>Created</th><th>You</th>
                </tr>
                <?php foreach ($users as $user) : ?>
                <tr>
                    <td><img width="25" src="<?= $user->Avatar ?>"></td>
                    <td><?= $user->UserID ?></td>
                    <td><?= $user->Username ?></td>
                    <td><?= $user->Email ?></td>
                    <td><?= $user->Verified == 1 ? "yes" : "no" ?></td>
                    <td><?= display_time($user->Created, "Y-m-d h:i:s A"); ?></td>
                    <td><?= check_login(false) && $user->Username == $_SESSION['USER']->Username ? "Yes" : "" ?></b></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>
</html>