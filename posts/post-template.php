<?php
$pageTitle = "Forum";
include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php");

$postID = isset($_GET['url']) ? basename($_GET['url'], '.php') : 'default';
$values['postid'] = $postID;
$query = "SELECT *, post_t.created AS PostCreated FROM post_t INNER JOIN user_t ON post_t.author = user_t.userid WHERE postid = :postid";
$post = run_database($query, $values)[0];
if (empty($post)) {
    header("Location: index.php");
}

$query = "SELECT *, comment_t.created AS CommentCreated FROM user_t INNER JOIN comment_t ON user_t.userid = comment_t.author WHERE postid = :postid";
$comments = run_database($query, $values);

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    add_comment($_POST, $post->postID);
}

if ($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['action'] == "delete") {
    delete_comment($_POST, $postID);
    header("Location: 413.php");
}

?>

<script src="post.js"></script>
<h2><?= isset($pageTitle) ? $pageTitle : "Page Header" ?></h2>
<div>
    <div>
        <h3><?= $post->title ?></h3>
        <p><?= $post->content; ?></p>
        <p>Submitted: <?= display_time($post->PostCreated, "m/d/Y h:i:s A"); ?></p> 
        <p>By: 
            <?= $post->username; ?>
            <?= check_login(false) && $post->username == $_SESSION['USER']->username ? " (You)" : "" ?>
        </p>
    </div>
    <div>
        <h4>Add Comment</h4>
        <form method="post">
            <p>Content: <textarea name="content" rows="5" cols="40"></textarea></p>
            <input type="submit" value="Submit">
        </form>
    </div>
    <div>
        <h4>Comments (<?= is_array($comments) ? count($comments) : "0"; ?>):</h4>
        <?php if (is_array($comments)) : for ($i = 0; $i < count($comments); $i++) : ?>
            <p>
                <b><?= $comments[$i]->username; ?>
                <?= $comments[$i]->username == $post->username ? " (OP)" : "" ?>
                <?= check_login(false) && $comments[$i]->username == $_SESSION['USER']->username ? " (You)" : "" ?></b>:
                <?= $comments[$i]->content; ?>  
                <?= "(" . display_time($comments[$i]->CommentCreated, "m/d/Y h:i:s A") . ")"; ?>
                <?= check_login(false) && $comments[$i]->username == $_SESSION['USER']->username ? '<input type="submit" value="Delete" onclick="DeleteComment(' . $comments[$i]->commentID . ')">' : "" ?>
            </p>
        <?php endfor; endif;?>
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>

<?php
function add_comment($data, $postID) {
    $errors = array();

    if(empty($data['content'])) {
        $errors[] = "Please enter content for the comment.";
    }

    if (count($errors) == 0) {
        $values['commentID'] = rand(100, 999);
        $values['postID'] = $postID;
        $values['content'] = $data['content'];
        $values['author'] = $_SESSION['USER']->userid;
        $values['created'] = get_local_time();

        $query = "INSERT INTO comment_t (commentID, postID, content, author, created) VALUES (:commentID, :postID, :content, :author, :created)";
        run_database($query, $values);

        header("Location: $postID.php");
    }

    return $errors;
}

function delete_comment($data, $postID) {
    $values['commentID'] = $data['commentID'];

    $query = "DELETE FROM comment_t WHERE commentID = :commentID";
    run_database($query, $values);
}
 
?>