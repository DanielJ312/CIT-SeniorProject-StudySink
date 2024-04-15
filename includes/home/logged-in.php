<?php
//////////* Logged In Home Page - Displays home page content for when a user is logged in *//////////
// University Side Bar
$userUniversity = get_user_university();
if (isset($userUniversity)) {
    // $recentUniPostIDs = get_recent_university_post_IDs($userUniversity);
    $recentPosts = get_recent_university_posts($userUniversity);
}
// Recent Posts
if (isset($_COOKIE['viewed_posts'])) {
    $viewedPostIDs = explode(',', $_COOKIE['viewed_posts']);
    $viewedPosts = get_recent_posts($viewedPostIDs);
}
// Recent Study Sets
if (isset($_COOKIE['viewed_study_sets'])) {
    $viewedStudySetIDs = explode(',', $_COOKIE['viewed_study_sets']);
    $viewedStudySets = get_recent_study_sets($viewedStudySetIDs);
}
$uniAbb = get_user_university_abbreviation();
$uniLogo = "https://studysink.s3.amazonaws.com/assets/Uni-logos/$uniAbb.png";
?>

<div class="home-screen">
    <!-- Left Container with My University Posts -->
    <div class="left-container">
        <div class="university-post-container">
            <?php if (isset($userUniversity)) : ?>
                <div class="university-logo-container">
                    <h2 class="home-container-title" style="margin: 0;">My University</h2>
                    <img src="<?= $uniLogo; ?>" alt="School Logo" class="uni-logo">
                </div>
            <?php else : ?>
                <h2 class="home-container-title" style="margin-top: 35px;">My University</h2>
            <?php endif; ?>
            <div class="university-posts-tiles-container">
                <?php if (isset($userUniversity)) : ?>
                    <!-- If $recentPosts is not empty -->
                    <?php if (!empty($recentPosts)) : ?>
                        <?php foreach ($recentPosts as $post) : ?>
                            <div class="university-post-tile PostLinkTile" data-id="<?= $post->PostID; ?>">
                                <div class="post-header">
                                    <a href="/account/<?= $post->Username; ?>.php" title="<?= $post->Username; ?>"><img src="<?= $post->Avatar; ?>" alt="<?= $post->Username; ?>" class="post-profile-picture" /></a>
                                    <div class="post-info">
                                        <a href="/account/<?= $post->Username; ?>.php" class="post-account"><?= $post->Username; ?></a>
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
                            <p> No posts created for <?= get_user_university_abbreviation() ?> yet.</p>
                    <?php endif; ?>
                <?php else : ?>
                    <div class="noUniContainer">
                        <p>No Primary University Set</p>
                        <button onclick="location.href='/account/settings.php#Primary-University';" class="setPUButton">Set a Primary University</button>
                    </div>
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
                    <?php foreach ($viewedPosts as $post) : ?>
                        <div class="post-tile PostLinkTile" data-id="<?= $post->PostID; ?>">
                            <div class="post-header">
                                <a href="/account/<?= $post->Username; ?>.php" title="<?= $post->Username; ?>"><img src="<?= $post->Avatar; ?>" alt="<?= $post->Username; ?>" class="post-profile-picture" /></a>
                                <div class="post-info">
                                    <a href="/account/<?= $post->Username; ?>.php" class="post-account"> <?= $post->Username; ?> </a>
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
                    <?php endforeach; ?>
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
                    <?php foreach ($viewedStudySets as $studySet) : ?>
                        <div class="study-set-tile StudySetLinkTile" data-id="<?= $studySet->StudySetID; ?>">
                            <div class="study-set-header">
                                <a href="/account/<?= $studySet->Username; ?>.php" title="<?= $studySet->Username; ?>"><img src="<?= $studySet->Avatar; ?>" alt="<?= $studySet->Username; ?>" class="study-set-profile-picture" /></a>
                                <div class="study-set-info">
                                    <a href="/account/<?= $studySet->Username; ?>.php" class="study-set-account"> <?= $studySet->Username; ?></a>
                                    <p class="study-set-date"> <?= date('F j, Y', $studySet->SetCreated); ?> </p>
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
                                    <div class="study-set-rating-count" style="margin-top: 1px;"><?= round($studySet->Rating, 1) ?></div>
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