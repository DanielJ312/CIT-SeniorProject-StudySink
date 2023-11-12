<!-- Post Template - Displays post for given Post ID  -->
<?php
require($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
$pageTitle = "Forum";

$postID = isset($_GET['url']) ? basename($_GET['url'], '.php') : 'default';
$values['PostID'] = $postID;
$query = "SELECT *, POST_T.Created AS PostCreated FROM POST_T INNER JOIN USER_T ON POST_T.UserID = USER_T.UserID WHERE PostID = :PostID;";
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
// if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['action'])) delete_comment($_POST);

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
            <p><?= $post->Content; ?></p>
            <p>Submitted: <?= display_time($post->PostCreated, "m/d/Y h:i:s A"); ?></p> 
            <p>By: 
                <?= $post->Username; ?>
                <?= check_login(false) && $post->Username == $_SESSION['USER']->Username ? " (You)" : "" ?>
            </p>
        </div>
        <div>
            <h4>Comments (<?= is_array($comments) ? count($comments) : "0"; ?>):</h4>
            <form id="sort-dropdown" method="">
                <?= "<script>var postID = $postID;</script>"; ?>
                <select id= "sort" class="sort" name="sorts">
                    <option value="comment-oldest">Oldest</option>
                    <option value="comment-newest">Newest</option>
                    <option value="comment-popular">Popular</option>
                </select>
            </form>
        <?php if (isset($_SESSION['USER'])): ?>
            <div>
                <h4>Add Comment</h4>
                <form id="add-comment" method="post">
                    <p>Content: <textarea name="content" rows="5" cols="40"></textarea></p>
                    <input type="submit" value="Submit">
                </form>
            </div>
        <?php endif; ?>
            <div class="sort-container">
            <?php if (is_array($comments)) : foreach ($comments as $comment) : ?>
                <p id="comment-<?= $comment->CommentID; ?>">
                    <img width="25" src="<?= $comment->Avatar; ?>">
                    <b><?= $comment->Username; ?>
                    <?= $comment->Username == $post->Username ? " (OP)" : ""; ?>
                    <?= check_login(false) && $comment->Username == $_SESSION['USER']->Username ? " (You)" : ""; ?></b>:
                    <?= $comment->Content; ?>  
                    <?= "(" . display_time($comment->CommentCreated, "m/d/Y h:i:s A") . ")"; ?>
                    <span>&lpar;Votes: 
                        <span id="comment-<?= $comment->CommentID; ?>-v"><?= $comment->Votes; ?></span>&rpar;
                    </span>
                <?php if (check_login()): ?>
                    <span id = "comment-<?= $comment->CommentID; ?>-vb">
                    <?php $userVote = check_user_vote($_SESSION['USER']->UserID, $comment->CommentID); ?>
                    <?php if ($userVote == 1) : ?>
                        <input id="comment-<?= $comment->CommentID; ?>-downvote" type="button" value="Downvote" onclick="updateCommentVote(<?= $comment->CommentID; ?>, <?= $_SESSION['USER']->UserID; ?>, '-1')">
                    <?php elseif ($userVote == -1) : ?>
                        <input id="comment-<?= $comment->CommentID; ?>-upvote" type="button" value="Upvote" onclick="updateCommentVote(<?= $comment->CommentID; ?>, <?= $_SESSION['USER']->UserID; ?>, '1')">
                    <?php else : ?>
                        <input id="comment-<?= $comment->CommentID; ?>-upvote" type="button" value="Upvote" onclick="updateCommentVote(<?= $comment->CommentID; ?>, <?= $_SESSION['USER']->UserID; ?>, '1')">
                        <input id="comment-<?= $comment->CommentID; ?>-downvote" type="button" value="Downvote" onclick="updateCommentVote(<?= $comment->CommentID; ?>, <?= $_SESSION['USER']->UserID; ?>, '-1')">
                    <?php endif; ?>
                    </span>
                    <?php if ($comment->Username == $_SESSION['USER']->Username) : ?>
                        <input type="submit" value="Delete" onclick="DeleteComment(<?= $comment->CommentID; ?>)">
                    <?php endif; ?>
                <?php endif; ?>
                </p>
            <?php endforeach; endif;?>
            </div>
        </div>
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>
</html>

<?php
function add_comment($data, $postID) {
    $errors = array();

    if(empty($data['content'])) {
        $errors[] = "Please enter content for the comment.";
    }

    if (count($errors) == 0) {
        $values['CommentID'] = $tempID = rand(100, 999);
        $values['PostID'] = $postID;
        $values['Content'] = $data['content'];
        $values['UserID'] = $_SESSION['USER']->UserID;
        $values['Created'] = get_local_time();

        $query = "INSERT INTO COMMENT_T (CommentID, PostID, Content, UserID, Created) VALUES (:CommentID, :PostID, :Content, :UserID, :Created)";
        run_database($query, $values);
        $query = "INSERT INTO CVOTE_T (CommentID, UserID, VoteType) VALUES ($tempID, {$_SESSION['USER']->UserID}, 1);";
        run_database($query);

        header("Location: $postID.php");
    }

    return $errors;
}
?>
