<?php
//////////* Study Set Template - Displays Study Set for given Study Set ID */////////
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/forum-functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/study-set-functions.php");

$setID = get_end_url();
$values['StudySetID'] = $setID;
$query = "
    SELECT SS.*, U.Name AS UniversityName, S.Name AS SubjectName, 
        C.Name AS CourseName, C.Abbreviation AS CourseAbbreviation,
        USER_T.Username, USER_T.Avatar, SS.Created AS SetCreated, SS.Modified as SetModified
    FROM STUDY_SET_T SS
        INNER JOIN USER_T ON SS.UserID = USER_T.UserID
        LEFT JOIN COURSE_T C ON SS.CourseID = C.CourseID
        LEFT JOIN SUBJECT_T S ON C.SubjectID = S.SubjectID
        LEFT JOIN UNIVERSITY_T U ON S.UniversityID = U.UniversityID
    WHERE SS.StudySetID = :StudySetID;
";

$set = run_database($query, $values)[0];

if (empty($set)) {
    university_redirect();
    exit;
}

$query = "SELECT * FROM STUDY_CARD_T WHERE StudySetID = :StudySetID ORDER BY CardID;";
$cards = run_database($query, $values);

// Fetch the current user's rating for this study set
if (check_login()) {
    $userRatingQuery = "SELECT Rating FROM STUDY_SET_RATINGS WHERE StudySetID = :StudySetID AND UserID = :UserID";
    $userRatingValues = ['StudySetID' => $setID, 'UserID' => $_SESSION['USER']->UserID];
    $userRatingResult = run_database($userRatingQuery, $userRatingValues);
    if ($userRatingResult) {
        $userRating = is_array($userRatingResult[0]) ? $userRatingResult[0]['Rating'] : $userRatingResult[0]->Rating;
    } else {
        $userRating = 0;
    }
}

// Calculate the average rating for the study set
$avgRatingQuery = "SELECT AVG(Rating) as AvgRating FROM STUDY_SET_RATINGS WHERE StudySetID = :StudySetID";
$avgRatingResult = run_database($avgRatingQuery, ['StudySetID' => $setID]);

if ($avgRatingResult) {
    $averageRating = is_array($avgRatingResult[0]) ? round($avgRatingResult[0]['AvgRating'], 2) : round($avgRatingResult[0]->AvgRating, 2);
} else {
    $averageRating = 'Not rated';
}

$commentTotal = count_comments($setID);
$pageTitle = "$set->Title";
save_to_cookie("study-set");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
    <script async src="/posts/forum.js"></script>
    <link rel="stylesheet" href="/styles/study-sets/template.css">
    <link rel="stylesheet" href="/styles/posts/template.css" />
</head>
<body class="studySetTemplateBody">
    <header>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?> 
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/to-top.php"); ?>
    </header>
    <main>
        <div class="studySetTemplateContainer">
            <h2><?= htmlspecialchars($set->Title) ?></h2>

            <div class="studySetDetails">
                <div class="studySetTemplateHeader">
                    <img src="<?= htmlspecialchars($set->Avatar); ?>" alt="<?= htmlspecialchars($set->Username); ?>'s avatar" class="profile-picture"/>
                    <div class="headerInfoAndRating">
                        <div class="studySetHeaderInfo">
                            <p><a href="/account/<?= htmlspecialchars($set->Username); ?>" title="<?= htmlspecialchars($set->Username); ?>"><?= htmlspecialchars($set->Username); ?></a></p>
                            <p><?= date("M j, Y", $set->Created); ?> <?= isset($set->SetModified) ?  "<i>· edited on " . date("F j, Y  h:i A", $set->SetModified) . "</i>" : "" ?></p>
                        </div>
                        <div class="ratingAndAverage">
                            <div class="rating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fa-regular fa-star star" data-value="<?= $i ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <div class="averageRating">
                                <p>Average Rating: <?= $averageRating ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="studySetDetailsBottom">
                    <div class="studySetDetailsBottomLeft">
                        <h3>Description:</h3>
                        <p><?= nl2br(htmlspecialchars($set->Description)); ?></p>
                    </div>
                    <div class="studySetDetailsBottomRight">
                        <p><?= htmlspecialchars($set->UniversityName); ?></p>
                        <p><?= htmlspecialchars($set->SubjectName); ?></p>
                        <p><?= htmlspecialchars($set->CourseAbbreviation); ?></p>
                        <p>Professor/Teacher: <?= htmlspecialchars($set->Instructor); ?></p>
                    </div>

                </div>
            </div>

            <div class="actionButtonsContainer">
                <!-- Edit button is only available to the owner, shown to the left if the user is the owner -->
                <?php if (check_login() && $set->Username == $_SESSION['USER']->Username) : ?>
                    <a href="/study-sets/edit.php?id=<?= htmlspecialchars($setID); ?>" class="actionButton editButton" style="float: left;"><i class="fa-regular fa-pen-to-square"></i></a>
                <?php endif; ?>

                <!-- View Flashcards button is always available and centered -->
                <a href="/study-sets/flashcards.php?setID=<?= htmlspecialchars($setID); ?>" class="actionButton viewFlashcardsButton" style="margin: auto; display: block; width: fit-content;">View Flashcards</a>

                <!-- Delete button is only available to the owner, shown to the right if the user is the owner -->
                <?php if (check_login() && $set->Username == $_SESSION['USER']->Username) : ?>
                    <a href="javascript:void(0);" class="actionButton deleteButton" style="float: right;" data-set-id="<?= htmlspecialchars($setID); ?>"><i class="fas fa-trash"></i></a>
                <?php endif; ?>

            </div>

            <?php foreach ($cards as $card): ?>
                <div class="cardContainer">
                    <div class="cardContainerFront"><?= nl2br(htmlspecialchars($card->Front)); ?></div>
                    <div class="cardContainerBack"><?= nl2br(htmlspecialchars($card->Back)); ?></div>
                </div>
            <?php endforeach; ?>
            <div class="commentContainer">
            <div class="comments">
                    <div class="container">
                        <h4>Comments (<span class="comment-total"><?= $commentTotal; ?></span>)</h4>
                        <form id="sort-dropdown" method="">
                            <?= "<script>var parentID = $setID;</script>"; ?>
                            <select id="sort" class="sort" name="sorts">
                                <option value="comment-oldest">Oldest</option>
                                <option value="comment-newest">Newest</option>
                                <option value="comment-popular">Popular</option>
                            </select>
                        </form>
                    </div>
                    <?php if (check_login()) : ?>
                        <div id="add-comment">
                        <div class="comment-bar">
                            <textarea style="resize: auto; height: 30px; width: 612px;" id="commentinput" oninput="commentcountChar(this)"type="text" class="input-bar" placeholder="Add a comment..." name="content"></textarea>
                            <span id="commentcharCount"></span>
                            <button onclick="AddComment()" type="submit" value="Submit" class="addComment">Add</button>
                        </div>
                        </div>
                    <?php endif; ?>
                    <div class="comment-sort-container">
                        <!-- Comments will get inserted here -->
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div id="deleteConfirmationModal" style="display:none;">
        <div class="modal-content">
            <p>Are you sure you want to DELETE this study set?</p>
            <div class="buttons-container">
                <button id="cancelDelete">Cancel</button>
                <button id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
    <script src="/study-sets/ratings.js"></script>
    <script>
        var userRating = <?= $userRating; ?>; 
        initializeRating('<?= htmlspecialchars($setID) ?>', userRating); 
    </script>
</body>
</html>