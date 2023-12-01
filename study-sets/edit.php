<?php 
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
update_session();
if (!check_login()) header("Location: /study-sets/index.php");
$pageTitle = "Edit Study Set";
$universities = get_universities_list();

if ($_SERVER['REQUEST_METHOD'] == "POST") edit_study_set($setID, $_POST);

$setID = $_GET['id'];
// if (!(check_login() && ($set->Username == $_SESSION['USER']->Username))) header("Location: /study-sets/index.php");

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
if (!($set->Username == $_SESSION['USER']->Username)) header("Location: /study-sets/index.php");


$query = "SELECT * FROM STUDY_CARD_T WHERE StudySetID = :StudySetID ORDER BY CardID;";
$cards = run_database($query, $values);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
    <link rel="stylesheet" href="/styles/study-set-styles/create.css">
</head>
<body class="createStudySetBody">
    <header>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
    </header>
    <main>
        <div class="studySetContainer">
            <h2 class="header2"><?=isset($pageTitle) ? $pageTitle : "Create a Study Set" ?></h2>
            <form id="studySetForm" method="POST" action="save-study-set">
                <div class="titleContainer">
                    <input type="text" id="setTitle" value="<?= $set->Title; ?>" placeholder="Enter Title Here: &quot;Computer Science 101 - Chapter 1&quot;" name="setTitle" maxlength="255" required>
                </div>
                <div class="studySetTags"> 
                    <div class="description">
                        <textarea type= "text" id="setDescription" placeholder="Add a Description..." name="setDescription" maxlength="500" required><?= $set->Description; ?></textarea>
                    </div>
                    
                    <div class="columnTags">
                        <input list="universities" id="setUniversity" value="<?= $set->UniversityName; ?>" placeholder="University" name="setUniversity" required>
                        <datalist id="universities">
                            <?php foreach($universities as $university): ?>
                                <option value="<?= htmlspecialchars($university->Name) ?>" data-id="<?= $university->UniversityID ?>">
                                    <?= htmlspecialchars($university->Name) ?>
                                </option>
                            <?php endforeach; ?>
                        </datalist>
                        
                        <input list="subjects" id="setSubject" value="<?= $set->SubjectName; ?>" placeholder="Subject" name="setSubject" required>
                        <datalist id="subjects">
                            <!-- Options will be added here by JavaScript after selecting a university -->
                        </datalist>
                        
                        <input list="courses" id="setCourse" placeholder="Course" value="<?= $set->CourseAbbreviation; ?>" name="setCourse" required>
                            <datalist id="courses">
                                <?php foreach ($courses as $course): ?>
                                    <option value="<?php echo htmlspecialchars($course->Abbreviation); ?>" data-id="<?php echo htmlspecialchars($course->CourseID); ?>">
                                        <?php echo htmlspecialchars($course->Name); ?>
                                    </option>
                                <?php endforeach; ?>
                            </datalist>
                        
                        <input type="text" id="setTeacher" value="<?= $set->Instructor; ?>" placeholder="Instructor" name="instructor" maxlength="65" required>
                    </div>
                </div>
                <div id="studyCards" class="studyCards">
                    <!-- Study cards will be added here -->
                    <?php foreach ($cards as $card): ?>
                        <div class="studyCards">
                            <div class="cardHeader">
                                <div class=topOfCard>
                                    <button type="button" class="deleteCardBtn" aria-label="Delete this card">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <div class=frontAndBack>
                                    <div class="cardFront">
                                        <textarea class="card-textarea" id="cardFront${cardCount}" placeholder="Enter term" name="cardFront${cardCount}" maxlength="999" required><?= $card->Front; ?></textarea>
                                    </div>
                                    <div class="cardBack">
                                        <textarea class="card-textarea" id="cardBack${cardCount}" placeholder="Enter definition" name="cardBack${cardCount}" maxlength="999" required><?= $card->Back; ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="formButtons"> 
                    <button type="button" id="addCardBtn">Add a Study Card</button>
                    <button type="submit">Save Study Set</button>
                </div>
            </form>
        </div>
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
    <script src="/study-sets/study-set-create.js"></script>
</body>
</html>