<!-- Profile - Display the logged in user's profile page -->
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/account-functions.php");
update_session();
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
            <div class="profile-container">
                <div class="profile-info">
                <img src="<?= $userInfo[0]->Avatar?>" alt="Avatar" class="profile-pp profile-pp-left" id="settingsProfilePicture" title="Avatar">
                    <h2 class="username"><?= $userInfo[0]->Username ?></h2>
                </div>
                <div class="info-container">
                    <p class="university"><b>School:</b><?= get_user_university_name() ?></p>
                    <p class="university"><b>Bio:</b><?= $userInfo[0]->Bio ?></p>
                    <p class="profile-bio"></p>
                </div>
            </div>
            <!-- Second profile container for study set containers -->
            <div class="profile-container2">
                <div class="content-container">
                    <h3><?= $userInfo[0]->Username ?>'s Study Sets</h3>
                    <div class="posts-container">
                        <ul id="post-list">
                            <!-- Posts will be added here using JavaScript -->
                        </ul>
                    </div>
                </div>
            </div>
            <!-- 3rd profile container with for posts containers -->
            <div class="profile-container2">
                <div class="content-container">
                    <h3><?= $userInfo[0]->Username ?>'s Posts</h3>
                    <div class="posts-container">

                        <ul id="post-list">
                            <!-- Posts will be added here using JavaScript -->
                        </ul>
                    </div>
                </div>
                <div class="content-container">
                    <h3><?= $userInfo[0]->Username ?>'s Liked Posts</h3>
                    <div class="posts-container">
                        <ul id="post-list">
                            <!-- Liked posts will be dynamically added here -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>

</html>