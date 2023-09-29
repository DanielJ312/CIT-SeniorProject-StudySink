<?php
$pageTitle = "Forum Testing";
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

?>

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
        <h4>Comments (<?= is_array($comments) ? count($comments) : "0"; ?>):</h4>
        <?php if (is_array($comments)) : for ($i = 0; $i < count($comments); $i++) : ?>
            <p>
                <b><?= $comments[$i]->username; ?>
                <?= $comments[$i]->username == $post->username ? " (OP)" : "" ?>
                <?= check_login(false) && $comments[$i]->username == $_SESSION['USER']->username ? " (You)" : "" ?></b>:
                <?= $comments[$i]->content; ?>  
                <?= "(" . display_time($comments[$i]->CommentCreated, "m/d/Y h:i:s A") . ")"; ?>
            </p>
        <?php endfor; endif;?>
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>