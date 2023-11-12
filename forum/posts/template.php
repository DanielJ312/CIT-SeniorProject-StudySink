<!-- Post Template - Displays post for given Post ID  -->
<?php
// require($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
require($_SERVER['DOCUMENT_ROOT'] . "/functions/forum-functions.php");
$pageTitle = "Forum";

$postID = isset($_GET['url']) ? basename($_GET['url'], '.php') : 'default';
$values['PostID'] = $postID;
$query = <<<query
SELECT PostID, Title, Content, POST_T.Created AS PostCreated, Username, Avatar, UNIVERSITY_T.Name AS UniversityName, SUBJECT_T.Name AS SubjectName
FROM POST_T INNER JOIN USER_T ON POST_T.UserID = USER_T.UserID 
	INNER JOIN UNIVERSITY_T ON POST_T.UniversityID = UNIVERSITY_T.UniversityID
    INNER JOIN SUBJECT_T ON POST_T.SubjectID = SUBJECT_T.SubjectID
WHERE PostID = :PostID;
query;
$post = run_database($query, $values)[0];
if (empty($post)) header("Location: /forum/index.php");

$query = <<<query
SELECT USER_T.UserID, Username, Avatar, COMMENT_T.CommentID, PostID, Content, COMMENT_T.Created AS CommentCreated, sum(VoteType) AS Votes
FROM USER_T INNER JOIN COMMENT_T ON USER_T.UserID = COMMENT_T.UserID
    INNER JOIN CVOTE_T ON COMMENT_T.CommentID = CVOTE_T.CommentID
WHERE PostID = :PostID
GROUP BY CommentID
ORDER BY COMMENT_T.Created ASC;
query;
$comments = run_database($query, $values);

if ($_SERVER['REQUEST_METHOD'] == "POST") add_comment($_POST, $post->PostID);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
    <script async src="/forum/forum.js"></script>
</head>
<body>
    <header>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
        <h2><?= isset($pageTitle) ? $pageTitle : "Page Header" ?></h2>
    </header>
    <main>
        <div>
            <h3><?= $post->Title ?></h3>
            <i><?= $post->UniversityName; ?> - <?= $post->SubjectName; ?></i>
            <p><?= $post->Content; ?></p>
            <p>Submitted: <?= display_time($post->PostCreated, "m/d/Y h:i:s A"); ?></p> 
            <p>By: 
                <?= $post->Username; ?>
                <?= check_login(false) && $post->Username == $_SESSION['USER']->Username ? " (You)" : "" ?>
            </p>
        </div>
        <div>
            <h4>Comments (<span class = comment-total><?= is_array($comments) ? count($comments) : "0"; ?></span>):</h4>
            <form id="sort-dropdown" method="">
                <?= "<script>var postID = $postID;</script>"; ?>
                <select id= "sort" class="sort" name="sorts">
                    <option value="comment-oldest">Oldest</option>
                    <option value="comment-newest">Newest</option>
                    <option value="comment-popular">Popular</option>
                </select>
            </form>
        <?php if (check_login()) : ?>
            <div>
                <h4>Add Comment</h4>
                <form id="add-comment" method="post">
                    <p>Content: <textarea name="content" rows="5" cols="40"></textarea></p>
                    <input type="submit" value="Submit">
                </form>
            </div>
        <?php endif; ?>
            <div class="sort-container">
                <!-- Comments will get inserted here -->
            </div>
        </div>
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>
</html>