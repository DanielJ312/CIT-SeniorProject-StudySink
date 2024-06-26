<?php 
//////////* Edit - Edits a study set */////////
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/study-set-functions.php");
if (!check_login()) header("Location: /study-sets/index.php");
isset($_GET['id']) ? $setID = $_GET['id'] : university_redirect();
if ($_SERVER['REQUEST_METHOD'] == "POST") edit_study_set($setID, $_POST);
$universities = get_universities_list();
$values['StudySetID'] = $setID;
$query = "
    SELECT 
        SS.CourseID AS CourseID,
        SS.*,
        U.Name AS UniversityName,
        U.UniversityID AS UniversityID,
        S.Name AS SubjectName,
        S.SubjectID AS SubjectID,
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
if (!($set->Username == $_SESSION['USER']->Username)) header("Location: /study-sets/index.php");

$query = "SELECT * FROM STUDY_CARD_T WHERE StudySetID = :StudySetID ORDER BY CardID;";
$cards = run_database($query, $values);
$pageTitle = "Edit Study Set";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
    <link rel="stylesheet" href="/styles/study-sets/create.css">
</head>
<body class="createStudySetBody">
    <header>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
    </header>
    <main>
        <div class="fixed-action-bar">
            <div class="action-bar-buttons">
                <a href="/study-sets/<?= urlencode($set->StudySetID) ?>" class="back-button">< Back to Set</a>
                <button type="submit" form="studySetForm" class="save-button">Save Study Set</button>
            </div>
        </div>
        <div id="pageIdentifier" data-page-type="edit"></div>
        <div class="studySetContainer">
            <h2 class="header2"><?=isset($pageTitle) ? $pageTitle : "Create a Study Set" ?></h2>
            <form id="studySetForm" method="POST">
                <div class="titleContainer">
                    <input type="text" id="setTitle" value="<?= $set->Title; ?>" placeholder="Enter Title Here: &quot;Computer Science 101 - Chapter 1&quot;" name="setTitle" maxlength="255" required>
                </div>
                <div class="studySetTags"> 
                    <div class="description">
                        <textarea type= "text" id="setDescription" placeholder="Add a Description..." name="setDescription" maxlength="500" required><?= $set->Description; ?></textarea>
                    </div>
                    
                    <div class="columnTags">
                        <!-- University Select -->
                        <select id="setUniversity" name="setUniversity" required>
                            <option value="">Select University</option>
                            <?php foreach($universities as $university): ?>
                                <option value="<?= htmlspecialchars($university->UniversityID) ?>"
                                        <?= $university->UniversityID == $set->UniversityID ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($university->Name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <!-- Hidden field for University ID -->
                        <input type="hidden" name="universityId" id="universityId" value="<?= $set->UniversityID; ?>">

                        <!-- Subject Select -->
                        <select id="setSubject" name="setSubject" required>
                            <option value="">Select Subject</option>
                            <!-- Options will be added here by JavaScript after selecting a university -->
                        </select>
                        <!-- Hidden field for Subject ID -->
                        <input type="hidden" name="subjectId" id="subjectId" value="<?= $set->SubjectID; ?>">

                        <!-- Course Select -->
                        <select id="setCourse" name="setCourse" required>
                            <option value="">Select Course</option>
                            <!-- Options will be added here by JavaScript after selecting a subject -->
                        </select>
                        <!-- Hidden field for Course ID -->
                        <input type="hidden" name="courseId" id="courseId" value="<?= $set->CourseID; ?>">

                        <input type="text" id="setInstructor" placeholder="Instructor" name="instructor" maxlength="65" value="<?= htmlspecialchars($set->Instructor); ?>" required>
                    </div>

                </div>
                <div id="studyCards" class="studyCards">
                    <!-- Study cards will be added here -->
                    <?php foreach ($cards as $card): ?>
                        <div class="studyCard" data-card-id="<?= $card->CardID; ?>" data-edited="false">
                            <div class="cardHeader">
                                <div class="topOfCard">
                                    <button type="button" class="deleteCardBtn" aria-label="Delete this card">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <div class="frontAndBack">
                                    <div class="cardFront">
                                        <textarea class="card-textarea" name="cards[<?= $card->CardID; ?>][front]" placeholder="Enter term" maxlength="1250" required><?= htmlspecialchars($card->Front); ?></textarea>
                                    </div>
                                    <div class="cardBack">
                                        <textarea class="card-textarea" name="cards[<?= $card->CardID; ?>][back]" placeholder="Enter definition" maxlength="1250" required><?= htmlspecialchars($card->Back); ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="cards[<?= $card->CardID; ?>][deleted]" value="false" class="delete-flag">
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" id="addCardBtn" class="bigAddCardButton">+ ADD CARD</button>

            </form>
            <!-- Unique hidden element to identify the edit page -->
            <div id="editPageIdentifier" style="display: none;"></div>
        </div>
    </main>
    <div id="modal-delete-last-card" style="display:none;">
        <div class="modal-content">
            <p>Cannot delete the last study card in the Study Set.</p>
            <div class="buttons-container">
                <button id="closeModal">OK</button>
            </div>
        </div>
    </div>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
    <script>
        var initialUniversityId = <?= json_encode($set->UniversityID) ?>;
        var initialSubjectId = <?= json_encode($set->SubjectID) ?>;
        var initialCourseId = <?= json_encode($set->CourseID) ?>;
    </script>
    <script src="/study-sets/study-set-create.js"></script>
</body>
</html>