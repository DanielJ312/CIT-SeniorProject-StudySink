<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");

$searchTerm = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';

// Search query for study sets
$studySetsQuery = "
    SELECT DISTINCT STUDY_SET_T.*, 
           COURSE_T.Abbreviation AS CourseAbbreviation, 
           SUBJECT_T.Name AS SubjectName, 
           UNIVERSITY_T.Name AS UniversityName, 
           UNIVERSITY_T.Abbreviation AS UniversityAbbreviation
    FROM STUDY_SET_T
    INNER JOIN COURSE_T ON STUDY_SET_T.CourseID = COURSE_T.CourseID
    INNER JOIN SUBJECT_T ON COURSE_T.SubjectID = SUBJECT_T.SubjectID
    INNER JOIN UNIVERSITY_T ON SUBJECT_T.UniversityID = UNIVERSITY_T.UniversityID
    LEFT JOIN STUDY_CARD_T ON STUDY_SET_T.StudySetID = STUDY_CARD_T.StudySetID
    WHERE STUDY_SET_T.Title LIKE :searchTerm 
       OR STUDY_SET_T.Description LIKE :searchTerm 
       OR COURSE_T.Abbreviation LIKE :searchTerm 
       OR SUBJECT_T.Name LIKE :searchTerm 
       OR UNIVERSITY_T.Name LIKE :searchTerm 
       OR UNIVERSITY_T.Abbreviation LIKE :searchTerm
       OR STUDY_CARD_T.Front LIKE :searchTerm
       OR STUDY_CARD_T.Back LIKE :searchTerm
";
$studySets = run_database($studySetsQuery, ['searchTerm' => "%$searchTerm%"]);

// Search query for posts
$postsQuery = "SELECT * FROM POST_T WHERE Title LIKE :searchTerm OR Content LIKE :searchTerm";
$posts = run_database($postsQuery, ['searchTerm' => "%$searchTerm%"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
    <link rel="stylesheet" href="../styles/results.css" />
</head>
<body>
    <header>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
    </header>
    <main>
        <div class="search-results">
            <h2>Search Results for: <?= $searchTerm ?></h2>
            <section class="study-sets">
                <h3>Study Sets</h3>
                <?php if ($studySets && count($studySets) > 0) : ?>
                    <div class="results">
                        <?php foreach ($studySets as $set) : ?>
                            <div class="result-item">
                                <a href="/study-sets/<?= htmlspecialchars($set->StudySetID); ?>">
                                    <h4><?= htmlspecialchars($set->Title); ?></h4>
                                    <p><?= htmlspecialchars($set->Description); ?></p>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <p>No study sets found.</p>
                <?php endif; ?>
            </section>
            <section class="posts">
                <h3>Posts</h3>
                <?php if ($posts && count($posts) > 0) : ?>
                    <div class="results">
                        <?php foreach ($posts as $post) : ?>
                            <div class="result-item">
                                <a href="/forum/posts/<?= htmlspecialchars($post->PostID); ?>">
                                    <h4><?= htmlspecialchars($post->Title); ?></h4>
                                    <p><?= htmlspecialchars($post->Content); ?></p>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <p>No posts found.</p>
                <?php endif; ?>
            </section>
        </div>
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>
</html>
