<?php 
// University Side Bar
$userUniversity = get_user_university();
if (isset($userUniversity)) {
    $recentUniPostIDs = get_recent_university_post_IDs($userUniversity);
}
// Recent Posts
if (isset($_COOKIE['viewed_posts'])) {
    $viewedPosts = explode(',', $_COOKIE['viewed_posts']);
}
// Recent Study Sets
if (isset($_COOKIE['viewed_study_sets'])) {
    $viewedStudySets = explode(',', $_COOKIE['viewed_study_sets']);
}
?>

<div class="home-screen">
    <!-- Left Container with My University Posts -->
    <div class="left-container">
        <div class="university-post-container">
            <h2 class="home-container-title">My University</h2>
            <div class="university-posts-tiles-container">
                <?php if (isset($userUniversity)) : ?>
                <?php foreach ($recentUniPostIDs as $postId) : ?>
                    <?php $post = get_post($postId); ?>
                    <div class="university-post-tile PostLinkTile" data-id="<?= $post->PostID; ?>">
                        <div class="post-header">
                            <a href="account/profile.php"><img src="<?= $post->Avatar; ?>" alt="Place Holder" class="post-profile-picture" /></a>
                            <div class="post-info">
                                <a href="account/profile.php" class="post-account"><?= $post->Username; ?></a>
                                <p class="post-date"> <?= date('F j, Y', $post->PostCreated); ?> </p>
                            </div>
                        </div>
                        <h3 class="post-title"> <?= $post->Title; ?> </h3>
                        <div class="post-content" style="margin-top: 2px;"> <?= $post->Content; ?> </div>
                        <div class="bottom-of-tile">
                            <div class="comment">
                                <i class="fa-regular fa-comment"></i>
                                <div class="comments-count"><?= $post->Comments; ?></div>
                            </div>
                            <div class="vote">
                                <i class="fa-regular fa-heart"></i>
                                <div class="votes-count"><?= $post->Likes; ?></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php else : ?>
                    <p>You do not currently have a primary university set. Visit the <a href="/account/settings.php">settings</a> page to set one.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!-- Right Container -->
    <div class="right-container">
        <!-- Recently Viewed Posts within Right Container -->
        <div class="recent-posts-container">
            <h2 class="home-container-title">Recently Viewed Posts</h2>
            <div class="post-tiles-container">
            <?php if (isset($_COOKIE['viewed_posts'])) : ?>
                <?php foreach ($viewedPosts as $postId) : ?>
                    <?php $post = get_post($postId); if ($post) : ?>
                    <div class="post-tile PostLinkTile" data-id="<?= $post->PostID; ?>">
                        <div class="post-header">
                            <a href="account/profile.php"><img src="<?= $post->Avatar; ?>" alt="Place Holder" class="post-profile-picture" /></a>
                            <div class="post-info">
                                <a href="account/profile.php" class="post-account"> <?= $post->Username; ?> </a>
                                <p class="post-date"> <?= date('F j, Y', $post->PostCreated); ?> </p>
                            </div>
                        </div>
                        <h3 class="post-title"> <?= $post->Title; ?> </h3>
                        <div class="post-content"> <?= $post->Content; ?> </div>
                        <div class="bottom-of-tile">
                            <div class="comment">
                                <i class="fa-regular fa-comment"></i>
                                <div class="comments-count"><?= $post->Comments; ?></div>
                            </div>
                            <div class="vote">
                                <i class="fa-regular fa-heart"></i>
                                <div class="votes-count"><?= $post->Likes; ?></div>
                            </div>
                        </div>
                    </div>
                <?php endif; endforeach; ?>
                <?php else : ?>
                    <p>You have not viewed any posts yet.</p>
                <?php endif; ?>
            </div>
        </div>
        <!-- Recently Viewed Study Sets within Right Container -->
        <div class="recent-sets-container">
            <h2 class="home-container-title">Recently Viewed Study Sets</h2>
            <div class="study-sets-tiles-container">
                <?php if (isset($_COOKIE['viewed_study_sets'])) : ?>
                <?php foreach ($viewedStudySets as $StudySetId) : ?>
                    <?php 
                        $studySet = get_study_set($StudySetId);
                        $avgRatingQuery = "SELECT AVG(Rating) as AvgRating FROM STUDY_SET_RATINGS WHERE StudySetID = :StudySetID";
                        $avgRatingResult = run_database($avgRatingQuery, ['StudySetID' => $StudySetId]);
                        if ($avgRatingResult) {
                            $averageRating = is_array($avgRatingResult[0]) ? round($avgRatingResult[0]['AvgRating'], 2) : round($avgRatingResult[0]->AvgRating, 2);
                        } else {
                            $averageRating = 'Not rated';
                        }
                    ?>
                    <div class="study-set-tile StudySetLinkTile" data-id="<?= $studySet->StudySetID; ?>">
                        <div class="study-set-header">
                            <a href="account/profile.php"><img src="<?= $studySet->Avatar; ?>" alt="Place Holder" class="study-set-profile-picture" /></a>
                            <div class="study-set-info">
                                <a href="account/profile.php" class="study-set-account"> <?= $studySet->Username; ?></a>
                                <p class="study-set-date"> <?= date('F j, Y', $studySet->Created); ?> </p>
                            </div>
                        </div>
                        <h3 class="study-set-title"> <?= $studySet->Title; ?> </h3>
                        <div class="study-set-description"> <?= $studySet->Description; ?> </div>
                        <div class="bottom-of-tile">
                            <div class="comment">
                                <i class="fa-regular fa-comment"></i>
                                <div class="comments-count"><?= $studySet->Comments; ?></div>
                            </div>
                            <div class="study-set-rating">
                                <i class="fa-regular fa-star"></i>
                                <div class="study-set-rating-count" style="margin-top: 1px;"><?= $averageRating; ?></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php else : ?>
                    <p>You have not viewed any study sets yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    // Event listener for the post tiles so it goes to the post page when clicking on the tile
    let postTiles = document.querySelectorAll('.PostLinkTile');
    postTiles.forEach(tile => {
        tile.addEventListener('click', function() {
            window.location.href = "/forum/posts/" + this.dataset.id;
        });
    });

    // Event listener for the Study Set tiles so it goes to the study set page when clicking on the tile
    let studySetTiles = document.querySelectorAll('.StudySetLinkTile');
    studySetTiles.forEach(tile => {
        tile.addEventListener('click', function() {
            window.location.href = "/study-sets/" + this.dataset.id;
        });
    });

    // Truncate the post content if it's too long
    // Select all post content
    var contents = document.querySelectorAll('.post-content');
    // Loop through each post content
    contents.forEach(function(content) {
        // Check if the content is longer than 50 characters
        if (content.textContent.length > 50) {
            // If it is, truncate it to 50 characters and add an ellipsis
            content.textContent = content.textContent.substring(0, 50) + '...';
        }
    });

    // Same for the 3 below
    var postTitles = document.querySelectorAll('.post-title');
    postTitles.forEach(function(title) {
        if (title.textContent.length > 80) {
            title.textContent = title.textContent.substring(0, 50) + '...';
        }
    });

    var descriptions = document.querySelectorAll('.study-set-description');
    descriptions.forEach(function(description) {
        if (description.textContent.length > 50) {
            description.textContent = description.textContent.substring(0, 50) + '...';
        }
    });

    var titles = document.querySelectorAll('.study-set-title');
    titles.forEach(function(title) {
        if (title.textContent.length > 80) {
            title.textContent = title.textContent.substring(0, 50) + '...';
        }
    });
</script>