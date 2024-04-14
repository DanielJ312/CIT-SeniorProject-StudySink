<?php
//////////* Results - Displays search results based on user prompt */////////
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
$searchTerm = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';

// Search query for study sets
$studySetsQuery = "SELECT DISTINCT STUDY_SET_T.*, 
                   COURSE_T.Abbreviation AS CourseAbbreviation, 
                   SUBJECT_T.Name AS SubjectName, 
                   UNIVERSITY_T.Name AS UniversityName, 
                   UNIVERSITY_T.Abbreviation AS UniversityAbbreviation,
                   USER_T.Username AS Username,
                   USER_T.Avatar AS Avatar,
                   COUNT(DISTINCT CommentID) AS Comments,
                   COALESCE((SELECT AVG(Rating) FROM STUDY_SET_RATINGS WHERE StudySetID = STUDY_SET_T.StudySetID), 0) AS Rating,
                   (MATCH(STUDY_SET_T.Title, STUDY_SET_T.Description, STUDY_SET_T.Instructor) AGAINST(:searchTerm IN NATURAL LANGUAGE MODE)
                   + MATCH(COURSE_T.Name, COURSE_T.Abbreviation) AGAINST(:searchTerm IN BOOLEAN MODE)
                   + MATCH(SUBJECT_T.Name, SUBJECT_T.Abbreviation) AGAINST(:searchTerm IN BOOLEAN MODE)
                   + MATCH(UNIVERSITY_T.Name) AGAINST(:searchTerm IN BOOLEAN MODE)
                   + MATCH(STUDY_CARD_T.Front, STUDY_CARD_T.Back) AGAINST(:searchTerm IN NATURAL LANGUAGE MODE)) AS RelevanceScore
                   FROM STUDY_SET_T
                   INNER JOIN COURSE_T ON STUDY_SET_T.CourseID = COURSE_T.CourseID
                   INNER JOIN SUBJECT_T ON COURSE_T.SubjectID = SUBJECT_T.SubjectID
                   INNER JOIN UNIVERSITY_T ON SUBJECT_T.UniversityID = UNIVERSITY_T.UniversityID
                   LEFT JOIN STUDY_CARD_T ON STUDY_SET_T.StudySetID = STUDY_CARD_T.StudySetID
                   INNER JOIN USER_T ON STUDY_SET_T.UserID = USER_T.UserID
                   LEFT OUTER JOIN COMMENT_T ON COMMENT_T.StudySetID = STUDY_SET_T.StudySetID
                   WHERE MATCH(STUDY_SET_T.Title, STUDY_SET_T.Description, STUDY_SET_T.Instructor) AGAINST(:searchTerm IN NATURAL LANGUAGE MODE)
                     OR MATCH(COURSE_T.Name, COURSE_T.Abbreviation) AGAINST(:searchTerm IN BOOLEAN MODE)
                     OR MATCH(SUBJECT_T.Name, SUBJECT_T.Abbreviation) AGAINST(:searchTerm IN BOOLEAN MODE)
                     OR MATCH(UNIVERSITY_T.Name) AGAINST(:searchTerm IN BOOLEAN MODE)
                     OR UNIVERSITY_T.Abbreviation LIKE :searchTerm
                     OR MATCH(STUDY_CARD_T.Front, STUDY_CARD_T.Back) AGAINST(:searchTerm IN NATURAL LANGUAGE MODE)
                     OR USER_T.Username LIKE :searchTerm
                    GROUP BY STUDY_SET_T.StudySetID
                    ORDER BY RelevanceScore DESC";
$studySets = run_database($studySetsQuery, ['searchTerm' => "%$searchTerm%"]);

// Search query for posts
$postsQuery = "SELECT POST_T.*, USER_T.Username, USER_T.Avatar, 
               UNIVERSITY_T.Name AS UniversityName, UNIVERSITY_T.Abbreviation AS UniversityAbbreviation, 
               SUBJECT_T.Name AS SubjectName,
               COUNT(DISTINCT CommentID) AS Comments, COALESCE((SELECT COUNT(*) FROM POST_LIKE_T WHERE PostID = POST_T.PostID AND VoteType = 1), 0) AS Likes
               FROM POST_T
               INNER JOIN USER_T ON POST_T.UserID = USER_T.UserID
               LEFT JOIN UNIVERSITY_T ON POST_T.UniversityID = UNIVERSITY_T.UniversityID
               LEFT JOIN SUBJECT_T ON POST_T.SubjectID = SUBJECT_T.SubjectID
               LEFT OUTER JOIN COMMENT_T ON COMMENT_T.PostID = POST_T.PostID
               WHERE POST_T.Title LIKE :searchTerm 
               OR POST_T.Content LIKE :searchTerm
               OR UNIVERSITY_T.Name LIKE :searchTerm
               OR UNIVERSITY_T.Abbreviation LIKE :searchTerm
               OR SUBJECT_T.Name LIKE :searchTerm
               OR USER_T.Username LIKE :searchTerm
               GROUP BY POST_T.PostID";
$posts = run_database($postsQuery, ['searchTerm' => "%$searchTerm%"]);

$usersQuery = "SELECT USER_T.Username, USER_T.Bio, USER_T.Avatar, USER_T.Created, UNIVERSITY_T.Name AS UniversityName, UNIVERSITY_T.Abbreviation AS UniversityAbbreviation
FROM USER_T INNER JOIN UNIVERSITY_T ON USER_T.UniversityID = UNIVERSITY_T.UniversityID
WHERE USER_T.Username LIKE :searchTerm
GROUP BY USER_T.UserID";
$users = run_database($usersQuery, ['searchTerm' => "%$searchTerm%"]);

$universitiesQuery = "SELECT UNIVERSITY_T.Name AS UniversityName, UNIVERSITY_T.Abbreviation AS UniversityAbbreviation, UNIVERSITY_T.Logo AS UniversityLogo 
FROM UNIVERSITY_T
WHERE UNIVERSITY_T.Name LIKE :searchTerm
OR UNIVERSITY_T.Abbreviation LIKE :searchTerm";
$universities = run_database($universitiesQuery, ['searchTerm' => "%$searchTerm%"]);

$pageTitle = "Results for " . '"' . $searchTerm . '"';
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
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/to-top.php"); ?>
    </header>
    <main>
        <div class="margin">
            <div class="university-info">
                <h2>Search Results for: <?= $searchTerm ?></h2>
            </div>
            <div class="column">
                <div class="study-set">
                    <div class="header">
                    <h2 id="toggleSet">Study Sets<i class="down"></i></h2>
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
                                                <div class="lower-header">
                                                    <div class="comment">
                                                        <div class="post-iconsp">
                                                            <i class="fa-regular fa-comment"></i>
                                                        </div>
                                                        <div class="comments-count"><?= $set->Comments; ?></div>
                                                    </div>
                                                    <div class="vote">
                                                        <div class="post-iconsp">
                                                            <i class="fa-regular fa-star" aria-hidden="true"></i>
                                                        </div>
                                                        <div class="votes"><?= round($set->Rating, 1); ?></div>
                                                    </div>
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
                    <h2 id="togglePost">Posts<i class="down"></i></h2>
                        <!-- Sorting options can be added here if needed -->
                    </div>
                    <div class="scrollbar" id="contentpost">
                        <?php if ($posts && count($posts) > 0) : ?>
                            <?php foreach ($posts as $post) : ?>
                                <div class="post">
                                    <a href="/posts/<?= htmlspecialchars($post->PostID); ?>">
                                        <div class="post-header">
                                            <img src="<?= htmlspecialchars($post->Avatar); ?>" alt="<?= htmlspecialchars($post->Username); ?>'s avatar" class="post-profile-picture" />
                                            <div class="post-info">
                                                <p class="post-account"><?= htmlspecialchars($post->Username); ?></p>
                                                <p class="post-date"><?= date("F j, Y", $post->Created); ?></p>
                                            </div>
                                        </div>
                                        <h3 class="post-title"><?= htmlspecialchars($post->Title); ?></h3>
                                        <div class="post-content"><?= htmlspecialchars(substr($post->Content, 0, 100)) . '...'; ?></div>
                                        <div class="lower-header">
                                            <div class="comment">
                                                <div class="post-iconsp">
                                                    <i class="fa-regular fa-comment"></i>
                                                </div>
                                                <div class="comments-count"><?= $post->Comments; ?></div>
                                            </div>
                                            <div class="vote">
                                                <div class="post-iconsp">
                                                    <i class="fa-regular fa-heart"></i>
                                                </div>
                                                <div class="votes"><?= $post->Likes; ?></div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <p>No posts found.</p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="users">
                    <div class="header">
                        <h2 id="togglePost">Users</h2>
                        <!-- Sorting options can be added here if needed -->
                    </div>
                    <div class="scrollbar" id="contentuser">
                        <?php if ($users && count($users) > 0) : ?>
                            <?php foreach ($users as $user) : ?>
                                <div class="userCardContainer">
                                    <a href="/account/<?= htmlspecialchars($user->Username); ?>">
                                        <div class="userCardHeaderTopLeft">
                                            <img src="<?= htmlspecialchars($user->Avatar); ?>" class="profile-picture" />
                                            <div class="userCardHeaderUsernameDate">
                                                <p class="post-account"><?= htmlspecialchars($user->Username); ?></p>
                                                <p>Joined: <?= isset($user->Created) ? date('F j, Y', $user->Created) : 'Unknown Date'; ?></p>
                                            </div>
                                        </div>
                                        <div class="userDetailsBottom">
                                            <div class="userDetailsBottomLeft">
                                                <h3 class="post-account"><?= htmlspecialchars($user->UniversityName); ?></h3>
                                            </div>
                                            <div class="userDetailsBottomRight">
                                                <p class="post-account"><?= htmlspecialchars($user->UniversityAbbreviation); ?></p>
                                                <!--<p class="post-title"><?= htmlspecialchars($user->Bio); ?></p>-->
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <p>No posts found.</p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="universities">
                    <div class="header">
                        <h2 id="togglePost">Universities</h2>
                        <!-- Sorting options can be added here if needed -->
                    </div>
                    <div class="scrollbar" id="contentuniversities">
                        <?php if ($universities && count($universities) > 0) : ?>
                            <?php foreach ($universities as $university) : ?>
                                <div class="userCardContainer">
                                    <a href="/university/<?= htmlspecialchars($university->UniversityAbbreviation); ?>">
                                        <div class="userCardHeaderTopLeft">
                                            <!--<img src="<?= htmlspecialchars($university->UniversityLogo); ?>" class="profile-picture" /> -->
                                            <div class="userCardHeaderUsernameDate">
                                                <p class="post-account"><?= htmlspecialchars($university->UniversityName); ?></p>
                                            </div>
                                        </div>
                                        <div class="userDetailsBottom">
                                            <div class="userDetailsBottomLeft">
                                                <h3 class="post-account"><?= htmlspecialchars($university->UniversityAbbreviation); ?></h3>
                                            </div>
                                            <div class="userDetailsBottomRight">
                                               <!-- <p class="post-account"><?= htmlspecialchars($user->UniversityAbbreviation); ?></p> -->
                                            </div>
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
    var contentSet = document.getElementById('contentset');
    var contentPost = document.getElementById('contentpost');

    document.getElementById('toggleSet').addEventListener('click', function() {
        if (window.innerWidth <= 850) {
            contentSet.style.display = (contentSet.style.display === 'none' || window.getComputedStyle(contentSet).display === 'none') ? 'block' : 'none';
        }
    });

    document.getElementById('togglePost').addEventListener('click', function() {
        if (window.innerWidth <= 850) {
            contentPost.style.display = (contentPost.style.display === 'none' || window.getComputedStyle(contentPost).display === 'none') ? 'block' : 'none';
        }
    });

    var gridItems = document.querySelectorAll('.post-content');
    gridItems.forEach(function(item) {
        var text = item.textContent;
        if (text.length > 50) {
            item.textContent = text.substring(0, 50) + '...';
        }
    });
});

</script>
</html>
