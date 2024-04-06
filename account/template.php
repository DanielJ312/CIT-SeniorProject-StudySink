<!-- Profile - Display the logged in user's profile page -->
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/account-functions.php");
update_session();
if (!check_login()) header("Location: /account/login.php");
$pageTitle = "Profile";

$username = isset($_GET['url']) ? basename($_GET['url'], '.php') : 'default';
$user = get_user_info($username);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
    <link rel="stylesheet" type="text/css" href="/styles/account/profile.css">
    <script defer src="/account/profile.js"></script>
</head>
<body class="profile-body">
    <header>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
    </header>
    <main class="profile-main">
        <div class="profile-outer-container">
            <!-- First profile container -->
            <div class="profile-user-container">
                <div class="profile-info">
                    <div class="profile-info-top">
                        <img src="<?= $user->Avatar; ?>" alt="Avatar" class="profile-pp" id="settingsProfilePicture" title="Avatar">
                        <p class="university"><?= get_user_university_name() ?></p>
                    </div>
                    <div class="profile-info-bottom">
                        <h2 class="username"><?= $user->Username; ?></h2>
                    </div>
                </div>
                <div class="bio-container">
                    <p><?= $user->Bio; ?></p>
                </div>
            </div>
            <!-- Second profile container for study set containers -->
            <div class="profile-tiles-container">
                <div class="users-study-sets">
                    <h3><?= $user->Username; ?>'s Study Sets</h3>
                    <div class="posts-container">
                        <!-- Put study set tiles here -->
                    </div>
                </div>
                <div class="users-posts">
                    <h3><?= $user->Username; ?>'s Posts</h3>
                    <div class="posts-container">
                        <!-- Put post tiles here -->
                    </div>
                </div>
                <div class="liked-posts">
                    <h3><?= $user->Username; ?>'s Liked Posts</h3>
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
<script>
    window.history.pushState({}, '', '/account/<?= $user->Username; ?>');
</script>
</html>