<!-- Study Set Template - Displays Study Set for given Study Set ID  -->
<?php
require($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
$pageTitle = "Study Set";

$setID = isset($_GET['url']) ? basename($_GET['url'], '.php') : 'default';
$values['StudySetID'] = $setID;
$query = "
    SELECT 
        SS.*,
        U.Name AS UniversityName,
        S.Name AS SubjectName,
        C.Name AS CourseName,
        C.Abbreviation AS CourseAbbreviation,
        USER_T.Username,
        USER_T.Avatar,
        SS.Created AS SetCreated 
    FROM 
        STUDY_SET_T SS
        INNER JOIN USER_T ON SS.UserID = USER_T.UserID
        LEFT JOIN COURSE_T C ON SS.CourseID = C.CourseID
        LEFT JOIN SUBJECT_T S ON C.SubjectID = S.SubjectID
        LEFT JOIN UNIVERSITY_T U ON S.UniversityID = U.UniversityID
    WHERE 
        SS.StudySetID = :StudySetID;
";

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
<body class="studySetTemplateBody">
    <header>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?> 
    </header>
    <main>
        <div class="studySetTemplateContainer">
            <h2><?= htmlspecialchars($set->Title) ?></h2>
            <?php if (check_login() && $set->Username == $_SESSION['USER']->Username) : ?>
                <p><a href="/study-sets/edit.php?id=<?= $setID; ?>">Edit</a></p>
            <?php endif; ?>
            <div class="studySetDetails">

                <div class="studySetTemplateHeader">
                    <img src="<?= htmlspecialchars($set->Avatar); ?>" alt="<?= htmlspecialchars($set->Username); ?>'s avatar" class="profile-picture"/>
                    <div class="studySetHeaderInfo">
                        <p><?= htmlspecialchars($set->Username); ?>
                            <?= check_login(false) && $set->Username == $_SESSION['USER']->Username ? " (You)" : "" ?>
                        </p>
                        <p>Created on <?= display_time($set->SetCreated, "F j, Y"); ?></p>
                    </div>
                </div>
                <div class="studySetDetailsBottom">
                    <div class="studySetDetailsBottomLeft">
                        <h3>Description:</h3>
                        <p><?= htmlspecialchars($set->Description); ?></p>
                    </div>
                    <div class="studySetDetailsBottomRight">
                        <p><?= htmlspecialchars($set->UniversityName); ?></p>
                        <p><?= htmlspecialchars($set->SubjectName); ?></p>
                        <p><?= htmlspecialchars($set->CourseAbbreviation); ?></p>
                        <p>Professor/Teacher: <?= htmlspecialchars($set->Instructor); ?></p>
                    </div>

                </div>
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