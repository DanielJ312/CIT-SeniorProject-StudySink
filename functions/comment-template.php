<!-- Comment-template.php - Template file for sorted comments -->
<p id="comment-<?= $comment->CommentID; ?>">
    <img width="25" src="<?= $comment->Avatar; ?>">
    <b><?= $comment->Username; ?>
    <?= $comment->Username == $postUsername ? " (OP)" : ""; ?>
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