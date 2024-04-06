<!-- Profile - Display the logged in user's profile page -->
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
update_session();
if (!check_login()) header("Location: /account/login.php");
if (!check_login()) header("Location: /account/login.php");
$pageTitle = "Profile";
$errors = $_SERVER['REQUEST_METHOD'] == "POST" ? upload_avatar($_FILES) : [];
// get user info by passing in a user id
$userInfo = get_user_info('771');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/styles/account/profile.css">
    <script defer src="/account/profile.js"></script>
    <title>User Profile</title>
</head>

<body class="profile-body">
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
    <main class="profile-main">
        <div class="profile-outer-container">
            <!-- First profile container -->
            <div class="profile-user-container">
                <div class="profile-info">
                    <div class="profile-info-top">
                        <img src="<?= $userInfo[0]->Avatar?>" alt="Avatar" class="profile-pp" id="settingsProfilePicture" title="Avatar">
                        <p class="university"><?= get_user_university_name() ?></p>
                    </div>
                    <div class="profile-info-bottom">
                        <h2 class="username"><?= $userInfo[0]->Username ?></h2>
                    </div>
                </div>
                <div class="bio-container">
                    <p><?= $userInfo[0]->Bio ?></p>
                </div>
            </div>
            <!-- Second profile container for study set containers -->
            <div class="profile-tiles-container">
                <div class="users-study-sets">
                    <h3><?= $userInfo[0]->Username ?>'s Study Sets</h3>
                    <div class="posts-container">
                        <!-- Put study set tiles here -->
                    </div>
                </div>
                <div class="users-posts">
                    <h3><?= $userInfo[0]->Username ?>'s Posts</h3>
                    <div class="posts-container">
                        <!-- Put post tiles here -->
                    </div>
                </div>
                <div class="liked-posts">
                    <h3><?= $userInfo[0]->Username ?>'s Liked Posts</h3>
                    <div class="posts-container">
                        <!-- Put liked post tiles here -->
                    </div>
                </div>

            </div>
            <!-- 3rd profile container with for posts containers -->

        </div>
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>

</html>