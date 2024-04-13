<?php
//////////* Profile - Display the logged in user's profile page *//////////
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/account-functions.php");
// if (!check_login()) header("Location: /account/login.php");

$urlUser = get_end_url();
$username = $urlUser == "default" ? $_SESSION['USER']->Username : $urlUser;
$user = get_user_info($username);

if ($user != null) {
    // Retrieving user's study sets, posts, and liked posts
    $usersStudySets = get_profile_users_study_sets($user->UserID);
    $usersPosts = get_profile_users_posts($user->UserID);
    $usersLikedPosts = get_profile_users_liked_posts($user->UserID);
    $pageTitle = "$user->Username's Profile";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
    <link rel="stylesheet" type="text/css" href="/styles/account/profile.css">
    <script defer src="/account/account.js"></script>
    <script defer src="/home.js"></script>
</head>
<body class="profile-body">
    <header>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/to-top.php"); ?>
    </header>
    <main class="profile-main">
        <?php if ($user == null) : ?>
            <!-- code for user that doesn't exist will go here -->
            <p>The user <b><?= $urlUser; ?></b> does not exist.</p>
        <?php else : ?>
            <div class="profile-outer-container">
                <!-- All the users information (picture, name, uni, bio (top section)) -->
                <div class="profile-user-container">
                    <div class="profile-info">
                        <img src="<?= $user->Avatar; ?>" alt="Avatar" class="profile-pp" id="settingsProfilePicture" title="Avatar">
                        <h2 class="username"><?= $user->Username; ?></h2>
                        <p class="university"><?= $user->Name; ?></p>
                    </div>
                    <div class="bio-container">
                        <h3>About Me</h3>
                        <p><?= $user->Bio; ?></p>
                    </div>
                </div>
                <!-- Start of the all the users tiles (study sets, posts, liked posts (bottom section))-->
                <div class="profile-tiles-container">
                    <!-- Users study sets -->
                    <div class="users-study-sets-container">
                        <div class="container-title"><?= $user->Username; ?>'s Study Sets</div>
                        <div class="study-sets-container">
                            <?php if (is_array($usersStudySets)) : ?>
                                <?php foreach ($usersStudySets as $studySet) : ?>
                                    <div class="study-set-tile StudySetLinkTile" data-id="<?= $studySet->StudySetID; ?>">
                                        <div class="study-set-header">
                                            <a href="account/profile.php"><img src="<?= $studySet->Avatar; ?>" alt="Place Holder" class="study-set-profile-picture" /></a>
                                            <div class="study-set-info">
                                                <a href="account/profile.php" class="study-set-account"> <?= $studySet->Username; ?></a>
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
                                <div class="no-tiles">
                                    <p><?= $user->Username; ?> has not created any Study Sets yet.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!-- Users posts -->
                    <div class="users-posts-container">
                        <div class="container-title"><?= $user->Username; ?>'s Posts</div>
                        <div class="posts-container">
                            <?php if (is_array($usersPosts)) : ?>
                                <?php foreach ($usersPosts as $post) : ?>
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
                                <?php endforeach; ?>
                            <?php else : ?>
                                <div class="no-tiles">
                                    <p><?= $user->Username; ?> has not created any Posts yet.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!-- Users liked posts -->
                    <div class="liked-posts-container">
                        <div class="container-title"><?= $user->Username; ?>'s Liked Posts</div>
                        <div class="posts-container">
                            <?php if (is_array($usersLikedPosts)) : ?>
                                <?php foreach ($usersLikedPosts as $post) : ?>
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
                                <?php endforeach; ?>
                            <?php else : ?>
                                <div class="no-tiles">
                                    <p><?= $user->Username; ?> has not liked any Posts yet.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>
</html>
<script>
    window.history.replaceState({}, '', '/account/<?= $user->Username; ?>');
    <?php if (isset($_SESSION['USER']->Username) && $user->Username == $_SESSION['USER']->Username) : ?>
        window.history.replaceState({}, '', '/account/profile');
    <?php endif; ?>
</script>

</html>