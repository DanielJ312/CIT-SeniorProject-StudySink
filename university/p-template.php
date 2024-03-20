<a href="/forum/posts/<?= $post->PostID; ?>" class="post">
    <div class="post-header">
        <img src="<?= $post->Avatar; ?>" alt="Place Holder" class="post-profile-picture" />
        <div class="post-info">
            <p class="post-account"><?= $post->Username; ?></p>
            <p class="post-date"><?= date("F j, Y", $post->PostCreated); ?></p>
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
</a>