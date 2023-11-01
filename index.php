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
            <p>Welcome to the home page for the StudySink login system.</p>
            <p>This page is accessible whether or not you are logged in.</p>
            <table>
                <h3>User Account Information</h3>
                <tr>
                    <th>userid</th><th>username</th><th>email</th><th>password</th><th>verified</th><th>created</th>
                </tr>
                <?php for ($i = 0; $i < sizeof($users); $i++) : ?>
                <tr>
                    <td><?= $users[$i]->UserID ?></td>
                    <td><?= $users[$i]->Username ?></td>
                    <td><?= $users[$i]->Email ?></td>
                    <td><?= $users[$i]->Password ?></td>
                    <td><?= $users[$i]->Verified == 1 ? "yes" : "no" ?></td>
                    <td><?= display_time($users[$i]->Created, "Y-m-d h:i:s A"); ?></td>
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