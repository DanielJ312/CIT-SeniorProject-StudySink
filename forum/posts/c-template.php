<!-- Comment-template.php - Template file for sorted comments -->
<div id="comment-<?= $comment->CommentID; ?>">
    <div class="comment">
        <div class="comment-header">
            <img src="<?= $comment->Avatar; ?>" alt="Place Holder" class="profile-picture" />
            <div class="comment-info">
                <p class="comment-account"><?= $comment->Username; ?></p>
                <p class="comment-date"><?= display_time($comment->CommentCreated, "F j, Y"); ?></p>
            </div>
            <div class="dropdown" onclick="toggleDropdown(this)">
                <i class="fa-solid fa-ellipsis-vertical ellipsis-icon"></i>
                <div class="dropdown-content">
                    <a href="#home">Report</a>
                    <a href="#home">Edit</a>
                    <?php if (check_login() && $comment->Username == $_SESSION['USER']->Username) : ?>
                        <a onclick="DeleteComment(<?= $comment->CommentID; ?>)">Delete</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <p class="comment-content">
            <?= $comment->Content; ?> <p class="votes">Votes:<span id="comment-<?= $comment->CommentID; ?>-v"><?= $comment->Votes; ?></span></p>
        </p>
        <div class="post-icons">
            <?php if (check_login()) : ?>
                <span id="comment-<?= $comment->CommentID; ?>-vb">
                    <?php $userVote = check_user_vote($_SESSION['USER']->UserID, $comment->CommentID); ?>
                    <?php if ($userVote == 1) : ?>
                        <a class="far fa-thumbs-down" id="comment-<?= $comment->CommentID; ?>-downvote" type="button" value="downvote" onclick="updateCommentVote(<?= $comment->CommentID; ?>, <?= $_SESSION['USER']->UserID; ?>, '-1')"></a>
                    <?php elseif ($userVote == -1) : ?>
                        <a class="far fa-thumbs-up" id="comment-<?= $comment->CommentID; ?>-upvote" type="button" value="upvote" onclick="updateCommentVote(<?= $comment->CommentID; ?>, <?= $_SESSION['USER']->UserID; ?>, '1')"></a>
                    <?php else : ?>
                        <a class="far fa-thumbs-up" id="comment-<?= $comment->CommentID; ?>-upvote" type="button" value="upvote" onclick="updateCommentVote(<?= $comment->CommentID; ?>, <?= $_SESSION['USER']->UserID; ?>, '1')"></a>
                        <a class="far fa-thumbs-down" id="comment-<?= $comment->CommentID; ?>-downvote" type="button" value="downvote" onclick="updateCommentVote(<?= $comment->CommentID; ?>, <?= $_SESSION['USER']->UserID; ?>, '-1')"></a>
                    <?php endif; ?>
                </span>
            <?php endif; ?>
        </div>
    </div>
</div>