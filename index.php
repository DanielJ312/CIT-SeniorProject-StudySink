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
            <p>Welcome to the home page for the StudySink Backend Development.</p>
            <p>This page currently has no use and instead lists all created users.</p>
            <table>
                <h3>Created Users</h3>
                <tr>
                    <th>Avatar</th><th>UserID</th><th>Username</th><th>Email</th>
                    <th>Verified</th><th>Created</th><th>Current User</th>
                </tr>
                <?php for ($i = 0; $i < sizeof($users); $i++) : ?>
                <tr>
                    <td><img width="25" src="<?= $users[$i]->Avatar ?>"></td>
                    <td><?= $users[$i]->UserID ?></td>
                    <td><?= $users[$i]->Username ?></td>
                    <td><?= $users[$i]->Email ?></td>
                    <td><?= $users[$i]->Verified == 1 ? "yes" : "no" ?></td>
                    <td><?= display_time($users[$i]->Created, "Y-m-d h:i:s A"); ?></td>
                    <td><?= check_login(false) && $users[$i]->Username == $_SESSION['USER']->Username ? "Yes" : "" ?></b></td>
                </tr>
                <?php endfor; ?>
            </table>
        </div>
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>
</html>