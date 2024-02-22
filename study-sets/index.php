<!-- Study-Set Display Page - Lists all Study Sets in Database -->
<?php 
require($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
$pageTitle = "Forum";

$query = "
    SELECT 
        SS.StudySetID, 
        SS.Title, 
        SS.Created AS SetCreated, 
        U.Username, 
        U.Avatar, 
        C.Name AS CourseName, 
        C.Abbreviation AS CourseAbbreviation, 
        S.Name AS SubjectName, 
        UN.Name AS UniversityName
    FROM 
        STUDY_SET_T SS
        JOIN USER_T U ON SS.UserID = U.UserID
        JOIN COURSE_T C ON SS.CourseID = C.CourseID
        JOIN SUBJECT_T S ON C.SubjectID = S.SubjectID
        JOIN UNIVERSITY_T UN ON S.UniversityID = UN.UniversityID
    ORDER BY 
        SS.Created DESC
";
$sets = run_database($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
    <link rel="stylesheet" href="../styles/study-set-styles/index.css">
    <!-- <script async src="/forum/forum.js"></script> -->
</head>
<body class="studySetBrowsePageBody">
    <header>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
        <!-- <h2><?= isset($pageTitle) ? $pageTitle : "Page Header" ?></h2> -->
    </header>
    <main>
        <div class="studySetBrowsePageContainer">
            <h3>DISREGARD PAGE: THIS IS ONLY FOR TESTING. THIS PAGE WILL BE DELETED FOR THE FINAL VERSION - Alex</h3>
            <div class="displayCardArea">
                <?php for ($i = 0; $i < count($sets); $i++): ?> 
                    <a href="/study-sets/<?= htmlspecialchars($sets[$i]->StudySetID); ?>.php" class="card-link">
                    <div class="cardContainer">
                        <div class="cardHeaderTopLeft">
                            <img src="<?= htmlspecialchars($sets[$i]->Avatar); ?>" alt="<?= htmlspecialchars($sets[$i]->Username); ?>'s avatar" class="profile-picture"/>
                            <div class="cardHeaderUsernameDate">
                                <p><?= htmlspecialchars($sets[$i]->Username); ?></p>
                                <p>Posted on <?= display_time($sets[$i]->SetCreated, "F j, Y"); ?></p>
                            </div>
                        </div>
                        <div class="studySetDetailsBottom">
                            <div class="studySetDetailsBottomLeft">
                                <h3><?= htmlspecialchars($sets[$i]->Title); ?></h3>
                            </div>
                            <div class="studySetDetailsBottomRight">
                                <p><?= htmlspecialchars($sets[$i]->UniversityName); ?></p>
                                <p><?= htmlspecialchars($sets[$i]->CourseAbbreviation); ?></p>
                            </div>
                        </div>
                    </div>
                    </a>
                <?php endfor; ?>
            </div>
        </div>
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>
</html>