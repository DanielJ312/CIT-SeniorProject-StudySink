<div id="comment-<?= $comment->CommentID; ?>" class="comment">
    <div class="comment-header">
        <img src="<?= $comment->Avatar; ?>" title="<?= $comment->Username; ?>" alt="<?= $comment->Username; ?>" class="profile-picture" />
        <div class="comment-info">
            <p class="comment-account"><?= $comment->Username; ?></p>
            <p class="comment-date"><?= date("F j, Y", $comment->CommentCreated); ?></p>
        </div>
        <?php if (check_login()): ?>
        <div class="dropdown" onclick="toggleDropdown(this)">
            <i class="fa-solid fa-ellipsis-vertical ellipsis-icon"></i>
            <div class="dropdown-content">
                <?php if ($_SESSION['USER']->UserID != $comment->UserID) : ?>
                    <a class="report" onclick="ReportComment(<?= $comment->CommentID; ?>)">Report</a>
                <?php endif; ?>
                <?php if ($comment->Username == $_SESSION['USER']->Username) : ?>
                    <a onclick="OpenCommentEditor(<?= $comment->CommentID; ?>)">Edit</a>
                    <a onclick="DeleteComment(<?= $comment->CommentID; ?>)">Delete</a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <div id="comment-<?= $comment->CommentID; ?>-c" >
        <p class="comment-content"><?= $comment->Content; ?></p>
    </div>
    <div class="vote">
        <div class="post-icons">
        <?php if (check_login()) : ?>
            <span>
                <?php $userVote = check_user_vote($_SESSION['USER']->UserID, $comment->CommentID); ?>
                <i class="like <?= $userVote == 1 ? "fa-solid" : "fa-regular"; ?> fa-heart button fa-lg" onclick="updateVote(<?= $comment->CommentID; ?>, <?= $_SESSION['USER']->UserID; ?>)"></i>
            </span>
        <?php endif; ?>
        </div>
        <div class="votes">&lpar;<span id="comment-<?= $comment->CommentID; ?>-v"><?= $comment->Votes; ?></span>&rpar;</div>
    </div>
</div>