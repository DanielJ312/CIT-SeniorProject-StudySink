<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");

$searchTerm = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';

// Search query for study sets
$studySetsQuery = "SELECT DISTINCT STUDY_SET_T.*, 
                   COURSE_T.Abbreviation AS CourseAbbreviation, 
                   SUBJECT_T.Name AS SubjectName, 
                   UNIVERSITY_T.Name AS UniversityName, 
                   UNIVERSITY_T.Abbreviation AS UniversityAbbreviation,
                   USER_T.Username AS Username,
                   USER_T.Avatar AS Avatar
                   FROM STUDY_SET_T
                   INNER JOIN COURSE_T ON STUDY_SET_T.CourseID = COURSE_T.CourseID
                   INNER JOIN SUBJECT_T ON COURSE_T.SubjectID = SUBJECT_T.SubjectID
                   INNER JOIN UNIVERSITY_T ON SUBJECT_T.UniversityID = UNIVERSITY_T.UniversityID
                   LEFT JOIN STUDY_CARD_T ON STUDY_SET_T.StudySetID = STUDY_CARD_T.StudySetID
                   INNER JOIN USER_T ON STUDY_SET_T.UserID = USER_T.UserID
                   WHERE STUDY_SET_T.Title LIKE :searchTerm 
                     OR STUDY_SET_T.Description LIKE :searchTerm 
                     OR STUDY_SET_T.Instructor LIKE :searchTerm
                     OR COURSE_T.Abbreviation LIKE :searchTerm 
                     OR SUBJECT_T.Name LIKE :searchTerm 
                     OR UNIVERSITY_T.Name LIKE :searchTerm 
                     OR UNIVERSITY_T.Abbreviation LIKE :searchTerm
                     OR STUDY_CARD_T.Front LIKE :searchTerm
                     OR STUDY_CARD_T.Back LIKE :searchTerm
                     OR USER_T.Username LIKE :searchTerm";
$studySets = run_database($studySetsQuery, ['searchTerm' => "%$searchTerm%"]);

// Search query for posts
$postsQuery = "SELECT POST_T.*, USER_T.Username, USER_T.Avatar, 
               UNIVERSITY_T.Name AS UniversityName, UNIVERSITY_T.Abbreviation AS UniversityAbbreviation, 
               SUBJECT_T.Name AS SubjectName
               FROM POST_T
               INNER JOIN USER_T ON POST_T.UserID = USER_T.UserID
               LEFT JOIN UNIVERSITY_T ON POST_T.UniversityID = UNIVERSITY_T.UniversityID
               LEFT JOIN SUBJECT_T ON POST_T.SubjectID = SUBJECT_T.SubjectID
               WHERE POST_T.Title LIKE :searchTerm 
               OR POST_T.Content LIKE :searchTerm
               OR UNIVERSITY_T.Name LIKE :searchTerm
               OR UNIVERSITY_T.Abbreviation LIKE :searchTerm
               OR SUBJECT_T.Name LIKE :searchTerm
               OR USER_T.Username LIKE :searchTerm";
$posts = run_database($postsQuery, ['searchTerm' => "%$searchTerm%"]);

$pageTitle = "Results for " . '"' . $searchTerm . '"';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
        <link rel="stylesheet" href="../styles/university/subject.css" />
    </head>
    <body>
    <header>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
    </header>
    <main>
        <div class="margin">
            <div class="university-info">
                <h2>Search Results for: <?= $searchTerm ?></h2>
            </div>
            <div class="column">
                <div class="study-set">
                    <div class="header">
                        <h2 id="toggleSet">Study Sets</h2>
                        <!-- Sorting options can be added here if needed -->
                    </div>
                    <div class="scrollbar" id="contentset">
                        <div class="displayCardArea">
                            <?php if ($studySets && count($studySets) > 0) : ?>
                                <?php foreach ($studySets as $set) : ?>
                                    <div class="cardContainer">
                                        <a href="/study-sets/<?= htmlspecialchars($set->StudySetID); ?>">
                                            <div class="cardHeaderTopLeft">
                                                <img src="<?= htmlspecialchars($set->Avatar); ?>" alt="<?= htmlspecialchars($set->Username); ?>'s avatar" class="profile-picture" />
                                                <div class="cardHeaderUsernameDate">
                                                    <p><?= isset($set->Username) ? htmlspecialchars($set->Username) : 'Unknown User'; ?></p>
                                                    <p><?= isset($set->Created) ? date('F j, Y', $set->Created) : 'Unknown Date'; ?></p>
                                                </div>
                                            </div>
                                            <div class="studySetDetailsBottom">
                                                <div class="studySetDetailsBottomLeft">
                                                    <h3><?= htmlspecialchars($set->Title); ?></h3>
                                                </div>
                                                <div class="studySetDetailsBottomRight">
                                                    <p><?= htmlspecialchars($set->UniversityName); ?></p>
                                                    <p><?= htmlspecialchars($set->CourseAbbreviation); ?></p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <p>No study sets found.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="posts">
                    <div class="header">
                        <h2 id="togglePost">Posts</h2>
                        <!-- Sorting options can be added here if needed -->
                    </div>
                    <div class="scrollbar" id="contentpost">
                        <?php if ($posts && count($posts) > 0) : ?>
                            <?php foreach ($posts as $post) : ?>
                                <div class="post">
                                    <a href="/forum/posts/<?= htmlspecialchars($post->PostID); ?>">
                                        <div class="post-header">
                                            <img src="<?= htmlspecialchars($post->Avatar); ?>" alt="<?= htmlspecialchars($post->Username); ?>'s avatar" class="post-profile-picture" />
                                            <div class="post-info">
                                                <p class="post-account"><?= htmlspecialchars($post->Username); ?></p>
                                                <p class="post-date"><?= date("F j, Y", $post->Created); ?></p>
                                            </div>
                                        </div>
                                        <h3 class="post-title"><?= htmlspecialchars($post->Title); ?></h3>
                                        <div class="post-content"><?= htmlspecialchars(substr($post->Content, 0, 100)) . '...'; ?></div>
                                        <div class="vote">
                                            <div class="post-iconsp">
                                                <i class="fa-regular fa-heart"></i>
                                            </div>
                                            <div class="votes">(20)</div> <!-- Placeholder for like count -->
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <p>No posts found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>
</html>
