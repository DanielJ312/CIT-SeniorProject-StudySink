<!-- FINISH LATER - No current use other than for testing. -->
<?php
require($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
update_session();
$pageTitle = "Subject";

$query = "SELECT * FROM UNIVERSITY_T WHERE Abbreviation = '{$_GET['university']}';";
$university = run_database($query)[0];

$query = "SELECT * FROM SUBJECT_T WHERE Abbreviation = '{$_GET['subject']}' && UniversityID = {$university->UniversityID};";
$subject = run_database($query)[0];

$query = <<<query
    SELECT StudySetID, Title, Description, STUDY_SET_T.Created AS SetCreated, Username, Avatar, UNIVERSITY_T.Name AS UniversityName, COURSE_T.Abbreviation AS Course
    FROM STUDY_SET_T INNER JOIN USER_T ON STUDY_SET_T.UserID = USER_T.UserID
        INNER JOIN COURSE_T ON COURSE_T.CourseID = STUDY_SET_T.CourseID
        INNER JOIN SUBJECT_T ON SUBJECT_T.SubjectID = COURSE_T.SubjectID
        INNER JOIN UNIVERSITY_T ON UNIVERSITY_T.UniversityID = SUBJECT_T.UniversityID
    WHERE SUBJECT_T.SubjectID = $subject->SubjectID
    ORDER BY STUDY_SET_T.Created ASC;
query;
$sets = run_database($query);

$query = <<<query
    SELECT PostID, Title, Content, POST_T.Created AS PostCreated, Username, Avatar, UNIVERSITY_T.Name AS UniversityName
    FROM POST_T INNER JOIN USER_T ON POST_T.UserID = USER_T.UserID
        INNER JOIN UNIVERSITY_T ON UNIVERSITY_T.UniversityID = POST_T.UniversityID
        INNER JOIN SUBJECT_T ON SUBJECT_T.SubjectID = POST_T.SubjectID
    WHERE UNIVERSITY_T.UniversityID = $university->UniversityID
        AND POST_T.SubjectID = $subject->SubjectID
    ORDER BY POST_T.Created ASC;
query;
$posts = run_database($query);
// echo $posts[5]->Username;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/to-top.php"); ?>
    <link rel="stylesheet" href="/styles/university/subject.css" />
</head>

<body>
    <header>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
    </header>
    <main>
        <div class="margin">
            <div class="university-info">
                <h2><?= $subject->Name; ?></h2>
            </div>
            <div class="column">
                <div class="study-set" >
                    <div class="header">
                        <h2 id="toggleSet">Study Sets<i class="arrow down"></i></h2>
                        <select class="sort" name="sorts">
                            <option value="post-oldest">Oldest</option>
                            <option value="post-newest">Newest</option>
                            <option value="post-popular">Popular</option>
                        </select>
                    </div>
                    <div class="scrollbar" id="contentset">
                        <div class="displayCardArea">
                        <?php foreach ($sets as $set) : ?>
                            <div class="cardContainer">
                                <a href="/study-sets/<?= $set->StudySetID; ?>" class="">
                                    <div class="cardHeaderTopLeft">
                                        <img src="<?= htmlspecialchars($set->Avatar); ?>" alt="<?= htmlspecialchars($set->Username); ?>'s avatar" class="profile-picture" />
                                        <div class="cardHeaderUsernameDate">
                                            <p><?= $set->Username; ?></p>
                                            <p><?= display_time($set->SetCreated, "F j, Y"); ?></p>
                                        </div>
                                    </div>
                                    <div class="studySetDetailsBottom">
                                        <div class="studySetDetailsBottomLeft">
                                            <h3><?= $set->Title; ?></h3>
                                        </div>
                                        <div class="studySetDetailsBottomRight">
                                            <p><?= $set->UniversityName; ?></p>
                                            <p><?= $set->Course; ?></p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="posts">
                    <div class="header">
                        <h2 id="togglePost">Posts<i class="arrow down"></i></h2>
                        <select class="sort" name="sorts">
                            <option value="post-oldest">Oldest</option>
                            <option value="post-newest">Newest</option>
                            <option value="post-popular">Popular</option>
                        </select>
                    </div>
                    <div class="scrollbar"id="contentpost">
                        <?php foreach ($posts as $post) : ?>
                            <div class="post">
                                <a href="/forum/posts/<?= $post->PostID; ?>" class="">
                                    <div class="post-header">
                                        <img src="<?= $post->Avatar; ?>" alt="Place Holder" class="post-profile-picture" />
                                        <div class="post-info">
                                            <p class="post-account"><?= $post->Username; ?></p>
                                            <p class="post-date"><?= display_time($post->PostCreated, "F j, Y"); ?></p>
                                        </div>
                                    </div>
                                    <h3 class="post-title"><?= $post->Title; ?></h3>
                                    <div class="post-content"><?= $post->Content; ?></div>
                                    <div class="vote">
                                        <div class="post-iconsp">
                                            <i class="fa-regular fa-heart"></i>
                                        </div>
                                        <div class="votes">(20)</div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>  
            </div>
        </div>
    </main>
    <script>
    document.getElementById('toggleSet').addEventListener('click', function() {
        if (window.innerWidth <= 850) {
            var contentDiv = document.getElementById('contentset');
            if (contentDiv.style.display === 'none' || window.getComputedStyle(contentDiv).display === 'none') {
                contentDiv.style.display = 'block'; // or any other desired display value
            } else {
                contentDiv.style.display = 'none';
            }
        }
    });

    document.getElementById('togglePost').addEventListener('click', function() {
        if (window.innerWidth <= 850) {
            var contentDiv = document.getElementById('contentpost');
            if (contentDiv.style.display === 'none' || window.getComputedStyle(contentDiv).display === 'none') {
                contentDiv.style.display = 'block'; // or any other desired display value
            } else {
                contentDiv.style.display = 'none';
            }
        }
    });
</script>
</body>
<footer>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
</footer>

</html>