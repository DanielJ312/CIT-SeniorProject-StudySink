<!-- Post Template - Displays post for given Post ID  -->
<?php
require($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
$pageTitle = "Study Set";

$setID = isset($_GET['url']) ? basename($_GET['url'], '.php') : 'default';
$values['StudySetID'] = $setID;
$query = "SELECT *, STUDY_SET_T.Created AS SetCreated FROM STUDY_SET_T INNER JOIN USER_T ON STUDY_SET_T.UserID = USER_T.UserID WHERE StudySetID = :StudySetID;";
$set = run_database($query, $values)[0];
empty($set) ? header("Location: /study-sets/create.php") : null;

$query = "SELECT * FROM STUDY_CARD_T WHERE StudySetID = :StudySetID ORDER BY CardID;";
$cards = run_database($query, $values);

// $_SERVER['REQUEST_METHOD'] == "POST" ? add_comment($_POST, $post->PostID) : null;

// if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['action'])) {
//     // delete_comment($_POST, $postID);
//     header("Location: {$post->PostID}.php"); // NOT WORKING - WORK ON A FIX
// }
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
            <h3><?= $set->Title ?></h3>
            <p>Description: <?= $set->Description; ?></p>
            <p>Professor: <?= $set->Teacher; ?></p>
            <p>Submitted: <?= display_time($set->SetCreated, "m/d/Y h:i:s A"); ?></p> 
            <p>By: 
                <?= $set->Username; ?>
                <?= check_login(false) && $set->Username == $_SESSION['USER']->Username ? " (You)" : "" ?>
            </p>
        </div>
        <div>
            <table>
                <h3>Cards</h3>
                <tr>
                    <th>Card</th><th>Front</th><th>Back</th>
                </tr>
                <?php for ($i = 0; $i < sizeof($cards); $i++) : ?>
                <tr>
                    <td><?= $i+1; ?></td>
                    <td><?= $cards[$i]->Front; ?></td>
                    <td><?= $cards[$i]->Back; ?></td>
                </tr>
                <?php endfor; ?>
            </table>
        </div>
        <!-- <?php if (check_login(false)): ?>
        <div>
            <h4>Add Comment</h4>
            <form method="post">
                <p>Content: <textarea name="content" rows="5" cols="40"></textarea></p>
                <input type="submit" value="Submit">
            </form>
        </div>
        <?php endif; ?> -->
        <div>
            <!-- <h4>Comments (<?= is_array($comments) ? count($comments) : "0"; ?>):</h4> -->
            <h4>Comments</h4>
            <p>Comments are disabled for the time being.</p>
        </div>
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>
</html>

<?php

?>
