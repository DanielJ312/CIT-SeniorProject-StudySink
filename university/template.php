<!-- Post Template - Displays post for given Post ID  -->
<?php
// require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/forum-functions.php");
update_session();
$univeristyAbbr = isset($_GET['url']) ? basename($_GET['url'], '.php') : 'default';

$query = <<<query
    SELECT POST_T.PostID, Title, POST_T.Content, POST_T.Created AS PostCreated, Username, Avatar, UNIVERSITY_T.UniversityID, UNIVERSITY_T.Name AS UniversityName, UNIVERSITY_T.Abbreviation, SUBJECT_T.Name AS SubjectName, COUNT(DISTINCT CommentID) AS Comments, COALESCE((SELECT COUNT(*) FROM POST_LIKE_T WHERE PostID = POST_T.PostID AND VoteType = 1), 0) AS Likes
    FROM POST_T 
        INNER JOIN USER_T ON POST_T.UserID = USER_T.UserID
        INNER JOIN UNIVERSITY_T ON POST_T.UniversityID = UNIVERSITY_T.UniversityID
        INNER JOIN SUBJECT_T ON POST_T.SubjectID = SUBJECT_T.SubjectID
        LEFT OUTER JOIN COMMENT_T ON COMMENT_T.PostID = POST_T.PostID
    WHERE UNIVERSITY_T.Abbreviation = '$univeristyAbbr'
    GROUP BY POST_T.PostID
    ORDER BY POST_T.Created ASC;
query;
$posts = run_database($query);
if (empty($posts)) header("Location: /university/index.php");
$query = "SELECT * FROM SUBJECT_T WHERE UniversityID = {$posts[0]->UniversityID} ORDER BY Name ASC;";
$subjects = run_database($query);

$pageTitle = $posts[0]->Abbreviation;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
    <!--<link rel="stylesheet" href="/styles/university/dark-mode.css" id="dark-theme"/>-->
    <link rel="stylesheet" href="/styles/university/university.css" />
    <script src="/university/university.js"></script>
</head>
<body>
<header>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/to-top.php"); ?>
</header>
    <main>
        <div class="margin">
            <div class="university-info">
                <h2><?= $posts[0]->UniversityName; ?></h2>
            </div>
            <div class="columns">
                <div class="subject-selection-c">
                    <div class="search-bar-csun">
                        <input id="searchbar" type="text" name="search" onkeyup="search_subject()" placeholder="Search Subjects..." />
                        <button><i class="fas fa-search"></i></button>
                    </div>
                    <div class="subjects">
                        <?php foreach ($subjects as $subject) : ?>
                            <ul><a href="/university/<?= $univeristyAbbr; ?>/<?= strtolower($subject->Abbreviation); ?>"><?= $subject->Name; ?></a></ul>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="posts">
                    <div class="post header">Posts</div>
                    <form method="post">
                        <select class="sort" name="sorts">
                            <option value="post-oldest">Oldest</option>
                            <option value="post-newest">Newest</option>
                            <option value="post-popular">Popular</option>
                            <?= "<script>var postID = 0;</script>"; ?>
                        </select>
                    </form>
                    <?php foreach ($posts as $post) : ?>
                        <a href="/forum/posts/<?= $post->PostID; ?>" class="post">
                            <div class="post-header">
                                <img src="<?= $post->Avatar; ?>" alt="Place Holder" class="post-profile-picture" />
                                <div class="post-info">
                                    <p class="post-account"><?= $post->Username; ?></p>
                                    <p class="post-date"><?= date("F j, Y", $post->PostCreated); ?></p>
                                </div>
                            </div>
                            <h3 class="post-title"><?= $post->Title; ?></h3>
                            <div class="post-content"><?= $post->Content; ?></div>
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
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>
</html>

<div class=mobileuniversity>
    <div class="university-info">
        <h2><?= $posts[0]->UniversityName; ?></h2>
    </div>
    <div class="subject">
        <h2 id="toggleSubject" onclick="toggleSubject()">Subjects<i class="down"></i></h2>
        <div class="subject-selection-c" id="contentsubject">
            <div class="search-bar-csun">
                <input id="searchbar2" type="text" name="search" onkeyup="search_subject_mobile()" placeholder="Search Subjects..." />
                <button><i class="fas fa-search"></i></button>
            </div>
            <div class="subjects">
                <?php foreach ($subjects as $subject) : ?>
                    <ul><a href="/university/<?= $univeristyAbbr; ?>/<?= strtolower($subject->Abbreviation); ?>"><?= $subject->Name; ?></a></ul>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="mobile-posts">
        <div class="post-header">
            <div class=title>Posts</div>
            <form method="post">
                <select class="sort" name="sorts">
                    <option value="post-oldest">Oldest</option>
                    <option value="post-newest">Newest</option>
                    <option value="post-popular">Popular</option>
                    <?= "<script>var postID = 0;</script>"; ?>
                </select>
            </form>
        </div>
        <?php foreach ($posts as $post) : ?>
            <a href="/forum/posts/<?= $post->PostID; ?>" class="post">
                <div class="post-header">
                    <img src="<?= $post->Avatar; ?>" alt="Place Holder" class="post-profile-picture" />
                    <div class="post-info">
                        <p class="post-account"><?= $post->Username; ?></p>
                        <p class="post-date"><?= date("F j, Y", $post->PostCreated); ?></p>
                    </div>
                </div>
                <h3 class="post-title"><?= $post->Title; ?></h3>
                <div class="post-content"><?= $post->Content; ?></div>
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
        <?php endforeach; ?>
    </div>
</div>