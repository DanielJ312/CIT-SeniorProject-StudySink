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




// Code for capturing and storing the Study Set ID of the 5 most recent study sets a user has viewed
$urlPath = $_SERVER['REQUEST_URI']; // e.g., "/study-sets/6969"
$segments = explode('/', $urlPath);
$studySetId = end($segments); // grab the end segement

// Verify that the study set ID is valid
$studySet = get_study_set($studySetId);
if ($studySet) {
    // Check if cookie exists
    if (isset($_COOKIE['viewed_study_sets'])) {
        $viewedStudySets = explode(',', $_COOKIE['viewed_study_sets']);   // Get array of viewed study set IDs
        // Check if Study Set ID already exists in array
        if (($key = array_search($studySetId, $viewedStudySets)) !== false) {
            unset($viewedStudySets[$key]);    // Remove existing Study Set ID from array
        }
        array_unshift($viewedStudySets, $studySetId);     // Add new study set ID to the start of the array
        $viewedStudySets = array_slice($viewedStudySets, 0, 5);    // Limit array to last 5 Study Set IDs
    } else {
        $viewedStudySets = array($studySetId);    // Create new array with the study set ID
    }

    // Update cookie
    setcookie('viewed_study_sets', implode(',', $viewedStudySets), time() + (86400 * 1), "/"); // Expires in 1 day
    var_dump($_COOKIE['viewed_study_sets']);
}
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
                            <p>Professor/Teacher: <?= htmlspecialchars($set->Teacher); ?></p>
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