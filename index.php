<?php 
$pageTitle = "Home Page";
include("includes/header.php");

// $currTime= get_local_time();
// echo $currTime . "<br>";
// $currTime = new DateTime("@$currTime");
// echo $currTime->format('Y-m-d h:i:s') . "<br>";

// date_default_timezone_set('America/Los_angeles'); // Set the timezone to your local timezone
// $currentLocalTime = date('U'); // Get the current local time
// echo $currentLocalTime . "<br>";
// $currentLocalTime = date('Y-m-d H:i:s e'); // Get the current local time
// echo $currentLocalTime;

// echo $_SERVER['REQUEST_URI'];
// echo getAfterDotCom($_SERVER['REQUEST_URI']);

?>

<h2><?=isset($pageTitle) ? $pageTitle : "Page Header" ?></h2>
<div>
    <p>Welcome to the home page for the StudySink login system.</p>
    <p>This page is accessible whether or not you are logged in.</p>

    <?php 
        $query = "SELECT * FROM user_t ";
        $result = run_database($query);
    ?>
    <table>
        <tr>
            <th>userid</th><th>username</th><th>email</th><th>password</th><th>verified</th>
        </tr>
    <?php for ($i=0; $i < sizeof($result); $i++): ?>
        <tr>
            <td><?=$result[$i]->userid?></td>
            <td><?=$result[$i]->username?></td>
            <td><?=$result[$i]->email?></td>
            <td><?=$result[$i]->password?></td>
            <td><?=$result[$i]->verified == 1 ? "yes" : "no"?></td>
        </tr>
    <?php endfor; ?>
    </table>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>