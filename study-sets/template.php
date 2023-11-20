<!-- Study Set Template - Displays Study Set for given Study Set ID  -->
<?php
require($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
$pageTitle = "Study Set";

$setID = isset($_GET['url']) ? basename($_GET['url'], '.php') : 'default';
$values['StudySetID'] = $setID;
$query = "SELECT STUDY_SET_T.*, USER_T.Username, USER_T.Avatar, STUDY_SET_T.Created AS SetCreated FROM STUDY_SET_T INNER JOIN USER_T ON STUDY_SET_T.UserID = USER_T.UserID WHERE StudySetID = :StudySetID;";
$set = run_database($query, $values)[0];
empty($set) ? header("Location: /study-sets/create.php") : null;

$query = "SELECT * FROM STUDY_CARD_T WHERE StudySetID = :StudySetID ORDER BY CardID;";
$cards = run_database($query, $values);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
        <link rel="stylesheet" href="../styles/study-set-styles/template.css">
    </head>
    <body>
        <header>
            <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
            <h2><?= isset($pageTitle) ? $pageTitle : "Page Header" ?></h2>
        </header>
        <main>
            <div class="studySetTemplateContainer">
                <div class="studySetDetails">
                    <img src="<?= htmlspecialchars($set->Avatar); ?>" alt="<?= htmlspecialchars($set->Username); ?>'s avatar" class="profile-picture"/>
                    <p><?= htmlspecialchars($set->Title) ?></p>
                    <p>Description: <?= htmlspecialchars($set->Description); ?></p>
                    <p>Professor: <?= htmlspecialchars($set->Teacher); ?></p>
                    <p>Submitted: <?= display_time($set->SetCreated, "m/d/Y h:i:s A"); ?></p>
                    <p>By: <?= htmlspecialchars($set->Username); ?>
                        <?= check_login(false) && $set->Username == $_SESSION['USER']->Username ? " (You)" : "" ?>
                    </p>
                </div>
                <?php foreach ($cards as $card): ?>
                    <div class="cardContainer">
                        <!-- <div class="card-header">Card <?= htmlspecialchars($card->CardID); ?></div> NOTE: Need to create a way to number cards-->
                        <div class="cardContainerFront"><?= htmlspecialchars($card->Front); ?></div>
                        <div class="cardContainerBack"><?= htmlspecialchars($card->Back); ?></div>
                    </div>
                <?php endforeach; ?>
                <div class="commentContainer">
                    <div class="card-header">Comments</div>
                    <div class="card-content">Comments are disabled for the time being.</div>
                </div>
            </div>
        </main>
        <footer>
            <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
        </footer>
    </body>
</html>