<?php 
$pageTitle = "Home";
include("includes/header.php");

?>

<h2><?=isset($pageTitle) ? $pageTitle : "Page Header" ?></h2>
<div>
    <p>Welcome to the home page for the StudySink login system.</p>
    <p>This page is accessible whether or not you are logged in.</p>

    <?php 
        $query = "SELECT * FROM user_t";
        $result = run_database($query);
    ?>
    <table>
        <tr>
            <th>userid</th><th>username</th><th>email</th><th>password</th><th>verified</th><th>created</th>
        </tr>
    <?php for ($i=0; $i < sizeof($result); $i++): ?>
        <tr>
            <td><?=$result[$i]->userid?></td>
            <td><?=$result[$i]->username?></td>
            <td><?=$result[$i]->email?></td>
            <td><?=$result[$i]->password?></td>
            <td><?=$result[$i]->verified == 1 ? "yes" : "no"?></td>
            <td><?= display_time($result[$i]->created, "Y-m-d h:i:s A"); ?></td>
        </tr>
    <?php endfor; ?>
    </table>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>