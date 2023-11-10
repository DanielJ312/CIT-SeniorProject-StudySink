<!-- Post Template - Displays post for given Post ID  -->
<?php
require($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
$pageTitle = "Forum";

$postID = isset($_GET['url']) ? basename($_GET['url'], '.php') : 'default';
$values['PostID'] = $postID;
$query = "SELECT *, POST_T.Created AS PostCreated FROM POST_T INNER JOIN USER_T ON POST_T.UserID = USER_T.UserID WHERE PostID = :PostID;";
$post = run_database($query, $values)[0];
empty($post) ? header("Location: /forum/index.php") : null;

$query = <<<query
SELECT USER_T.UserID, Username, Avatar, COMMENT_T.CommentID, PostID, Content, COMMENT_T.Created AS CommentCreated, sum(VoteType) AS Votes
FROM USER_T INNER JOIN COMMENT_T ON USER_T.UserID = COMMENT_T.UserID
    INNER JOIN CVOTE_T ON COMMENT_T.CommentID = CVOTE_T.CommentID
WHERE PostID = :PostID
GROUP BY CommentID
ORDER BY COMMENT_T.Created ASC;
query;
$comments = run_database($query, $values);

// if ($_SERVER['REQUEST_METHOD'] == "POST") {
//     add_comment($_POST, $post->PostID);
// }

$_SERVER['REQUEST_METHOD'] == "POST" ? add_comment($_POST, $post->PostID) : null;

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['action'])) {
    delete_comment($_POST, $postID);
    header("Location: {$post->PostID}.php"); // NOT WORKING - WORK ON A FIX
}
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
        <?php if (check_login(false)): ?>
        <div>
            <h4>Add Comment</h4>
            <form method="post">
                <p>Content: <textarea name="content" rows="5" cols="40"></textarea></p>
                <input type="submit" value="Submit">
            </form>
        </div>
        <?php endif; ?>
        <div>
            <h4>Comments (<?= is_array($comments) ? count($comments) : "0"; ?>):</h4>
            <?php if (is_array($comments)) : for ($i = 0; $i < count($comments); $i++) : ?>
                <p id="comment-<?= $comments[$i]->CommentID; ?>">
                    <img width="25" src="<?= $comments[$i]->Avatar; ?>">
                    <b><?= $comments[$i]->Username; ?>
                    <?= $comments[$i]->Username == $post->Username ? " (OP)" : ""; ?>
                    <?= check_login(false) && $comments[$i]->Username == $_SESSION['USER']->Username ? " (You)" : ""; ?></b>:
                    <?= $comments[$i]->Content; ?>  
                    <?= "(" . display_time($comments[$i]->CommentCreated, "m/d/Y h:i:s A") . ")"; ?>
                    <span>&lpar;Votes: 
                        <span id="comment-<?= $comments[$i]->CommentID; ?>-v"><?= $comments[$i]->Votes; ?></span>&rpar;
                    </span>
                <?php if (check_login(false)): ?>
                    <span id = "comment-<?= $comments[$i]->CommentID; ?>-vb">
                    <?php $userVote = check_user_vote($_SESSION['USER']->UserID, $comments[$i]->CommentID); ?>
                    <?php if ($userVote == 1) : ?>
                        <input id="comment-<?= $comments[$i]->CommentID; ?>-downvote" type="button" value="Downvote" onclick="updateCommentVote(<?= $comments[$i]->CommentID; ?>, <?= $_SESSION['USER']->UserID; ?>, '-1')">
                    <?php elseif ($userVote == -1) : ?>
                        <input id="comment-<?= $comments[$i]->CommentID; ?>-upvote" type="button" value="Upvote" onclick="updateCommentVote(<?= $comments[$i]->CommentID; ?>, <?= $_SESSION['USER']->UserID; ?>, '1')">
                    <?php else : ?>
                        <input id="comment-<?= $comments[$i]->CommentID; ?>-upvote" type="button" value="Upvote" onclick="updateCommentVote(<?= $comments[$i]->CommentID; ?>, <?= $_SESSION['USER']->UserID; ?>, '1')">
                        <input id="comment-<?= $comments[$i]->CommentID; ?>-downvote" type="button" value="Downvote" onclick="updateCommentVote(<?= $comments[$i]->CommentID; ?>, <?= $_SESSION['USER']->UserID; ?>, '-1')">
                    <?php endif; ?>
                    </span>
                    <?php if ($comments[$i]->Username == $_SESSION['USER']->Username) : ?>
                        <input type="submit" value="Delete" onclick="DeleteComment(<?= $comments[$i]->CommentID; ?>)">
                    <?php endif; ?>
                <?php endif; ?>
                </p>
            <?php endfor; endif;?>
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

function delete_comment($data, $postID) {
    $values['CommentID'] = $data['commentID'];

    $query = "DELETE FROM COMMENT_T WHERE CommentID = :CommentID";
    run_database($query, $values);
}

function check_user_vote($userID, $commentID) {
    $query = "SELECT VoteType FROM CVOTE_T WHERE CommentID = $commentID AND UserID = $userID;";
    $result = run_database($query);
    if (is_array($result) && !$result[0]->VoteType == 0) {
        return $result[0]->VoteType;
    }
}
?>
