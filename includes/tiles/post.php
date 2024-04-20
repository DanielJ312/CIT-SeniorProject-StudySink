<?php if ($subjectID != 0) : ?>
    <div class="post" onclick="window.location.href='/posts/<?= $post->PostID; ?>'">
<?php endif; ?>

<div data-id="<?= $post->PostID; ?>" class="<?= $subjectID != 0 ? "" : "post"; ?>" onclick="window.location.href='/posts/<?= $post->PostID; ?>'">
    <div class="post-header">
        <a href="/account/<?= $post->Username; ?>.php" title="<?= $post->Username; ?>">
            <img src="<?= $post->Avatar; ?>" alt="<?= $post->Username; ?>" class="post-profile-picture" />
        </a>
        <div class="post-info">
            <a href="/account/<?= $post->Username; ?>.php" class="post-account"><?= $post->Username; ?></a>
            <p class="post-date"><?= date('F j, Y', $post->PostCreated); ?></p>
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
</div>

<?php if ($subjectID != 0) : ?>
    </div>
<?php endif; ?>
