<div id="comment-<?= $comment->CommentID; ?>" class="comment">
    <div class="comment-header">
        <a href="/account/<?= $comment->Username; ?>" title="<?= $comment->Username; ?>"><img src="<?= $comment->Avatar; ?>" title="<?= $comment->Username; ?>" alt="<?= $comment->Username; ?>" class="profile-picture" /></a>
        <div class="comment-info">
            <p class="comment-account"><a href="<?= $comment->Username; ?>" title="<?= $comment->Username; ?>"><?= $comment->Username; ?></a></p>
            <p class="comment-date">
                <?php if (!isset($comment->Modified)): ?>
                    <span class="posted"><?= date("F j, Y", $comment->CommentCreated); ?></span>
                <?php else: ?>
                    <span class="posted"><?= date("M j, Y", $comment->CommentCreated); ?><b> · </b></span>
                    <span><i><span class="edited">edited on <?= date("M j, Y g:i A", $comment->Modified); ?></span></i></span>
                <?php endif; ?>
            </p>
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
        <p class="comment-content"><?= nl2br($comment->Content); ?></p>
    </div>
    <div class="vote">
        <div class="post-icons">
        <?php if (check_login()) : ?>
            <span>
                <?php $userCVote = check_user_cvote($comment->CommentID); ?>
                <i class="like <?= $userCVote == 1 ? "fa-solid" : "fa-regular"; ?> fa-heart button fa-lg" onclick="updateCommentLike(<?= $comment->CommentID; ?>)"></i>
            </span>
            <?php else : ?>
                <a href="/account/login.php" style="color: #2778ff;"><i class="like fa-regular fa-heart button fa-lg" onclick=""></i></a>
        <?php endif; ?>
        </div>
        <div class="votes"><span id="comment-<?= $comment->CommentID; ?>-v"><?= $comment->Votes; ?></span></div>
    </div>
</div>