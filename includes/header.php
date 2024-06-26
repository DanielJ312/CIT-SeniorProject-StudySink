<?php
//////////* Header - Contains header HTML injected into every page *//////////
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/forum-functions.php");
$query = "SELECT * FROM UNIVERSITY_T;";
$postUniversities = get_universities_list();
$postErrors = $_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['setPostUniversity']) && $_POST['form_id'] == 'postForm' ? create_post($_POST) : [];
?>

<!-- Full Size Navbar -->
<div class="Navbody">
    <div class="navbarMain">
        <div class="navbar-left">
            <a href="/index.php" id="Home"><img src="https://studysink.s3.amazonaws.com/assets/StudySinkBanner.png" alt="Company Logo" class="companyLogo" title="Home"></a>
        </div>
        <div class="navbar-center">
            <div class="search-container">
                <form action="/results.php" method="GET" class="search-bar-container">
                    <input type="text" id="searchBar" name="search" placeholder="Search Study sets, Universities, Posts">
                    <button class="search-button" type="submit" aria-label="Search">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>
            </div>
        </div>
        <?php if (check_login()) : ?>
            <div class="navbar-right">
                <div class="dropdown" style="padding-top: 15px; padding-bottom: 15px;">
                    <i class="fa-solid fa-circle-plus fa-2xl <?= check_active('/study-sets/create'); ?>" id="createIcon" title="Create"></i>
                    <div class="dropdown-content-create" id="createDropdown">
                        <a href="/study-sets/create.php">Create Study Set</a>
                        <a onclick="<?= $_SESSION['USER']->Verified == 1 ? "openPopup()" : "" ?>">Create Post</a>
                    </div>
                </div>
                <a href="/index.php" id="Home" title="Home"><i class="fa-solid fa-house fa-2xl <?= check_active('/index', 'home'); ?>"></i></a>
                <a href="/university/<?= isset($_SESSION['USER']->Abbreviation) ? $_SESSION['USER']->Abbreviation : "index"; ?>.php" id="University" title="My University"><i class="fa-solid fa-graduation-cap fa-2xl <?= check_active('/university'); ?>"></i></a>
                <div class="dropdown">
                    <img src="<?= $_SESSION['USER']->Avatar ?>" alt="Avatar" class="profile-picture <?= check_active('/account'); ?>" id="profilePicture" title="Avatar">
                    <div class="dropdown-content-profile" id="profileDropdown">
                        <a href="/account/profile.php">My Profile</a>
                        <a href="/account/settings.php">Account Settings</a>
                        <a href="/request/index.php">Contact Support</a>
                        <a href="/account/logout.php">Logout</a>
                    </div>
                </div>
            </div>
        <?php else : ?>
            <div class="navbar-right">
                <a href="/index.php" id="Home" title="Home"><i class="fa-solid fa-house fa-2xl <?= check_active('/index', 'home'); ?>"></i></a>
                <a href="/university/index.php" id="University" title="My University"><i class="fa-solid fa-graduation-cap fa-2xl <?= check_active('/university'); ?>"></i></a>
                <a href="/account/login.php" id="Login" title="Login or Register"><i class="fa-solid fa-id-card fa-2xl <?= check_account_dir(); ?>"></i></a>
            </div>
        <?php endif; ?>
    </div>

    <!-- End of Full Size Nav bar and Beginning of Mobile Nav Bar -->
    <div class="navbarmobile">
        <header class="mobileheader" style="height: 20px;">
            <div class="mobile-header-items">
                <a href="/index.php" id="Home"><img id="logo" src="https://studysink.s3.amazonaws.com/assets/StudySinkLogoOnly.png" alt="Company Logo" title="Home" style="margin-top: -10px; margin-bottom: -10px;"></a>
                <div class="search-container">
                    <form action="/results.php" method="GET" class="search-bar-container">
                        <input type="text" id="searchBar" name="search" placeholder="Search">
                        <button class="search-button" type="submit" aria-label="Search">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </form>
                </div>
                <i class="fa-solid fa-bars fa-2xl" id="menuIcon" title="Menu Icon"></i>
            </div>
            <nav class="mobilenav">
                <div class="nav-options">
                    <div class="navitem <?= background_check_active('/index', 'home'); ?>"><a href="/index.php" title="Home">Home</a></div>
                    <?php if (check_login()) : ?>
                        <div class="navitem <?= background_check_active('/university'); ?>"><a href="/university/<?= isset($_SESSION['USER']->Abbreviation) ? $_SESSION['USER']->Abbreviation : "index"; ?>" title="My University">My University</a></div>
                        <div class="dropdown">
                            <div class="navitem"><a title="Create">Create</a></div>
                            <div class="dropdown-content">
                                <div class="navitem <?= background_check_active('/study-sets/create'); ?>"><a href="/study-sets/create.php" title="Create Study Set">Study Set</a></div>
                                <div class="navitem"><a title="Create Post" onclick="openPopup()">Post</a></div>
                            </div>
                        </div>
                        <div class="dropdown">
                            <div class="navitem"><a href="#" style="border-bottom-color: black; border-bottom-width: 2px; border-bottom-style: solid;" title="Profile">Profile</a></div>
                            <div class="dropdown-content">
                                <a href="/account/profile.php" class="<?= background_check_active("/account/{$_SESSION['USER']->Username}"); ?>" style="border-top-width: 0px;" title="My Profile">My Profile</a>
                                <div class="navitem <?= background_check_active('/account/settings'); ?>"><a href="/account/settings.php" title="Settings">Settings</a></div>
                                <div class="navitem <?= background_check_active('/request'); ?>"><a href="/request/index.php" title="Help">Support</a></div>
                                <div class="navitem"><a href="/account/logout.php" style="border-bottom-color: black; border-bottom-width: 2px; border-bottom-style: solid;" title="Logout">Logout</a></div>
                            </div>
                        </div>
                    <?php else : ?>
                        <div class="navitem <?= background_check_active('/university'); ?>"><a href="/university/index.php" title="Universities">Universities</a></div>
                        <div class="navitem <?= background_check_active('/account/login'); ?>"><a href="/account/login.php" title="Login">Login</a></div>
                        <div class="navitem <?= background_check_active('/account/register'); ?>"><a href="/account/register.php" title="Register">Register</a></div>
                    <?php endif; ?>
                </div>
            </nav>
        <header>
    </div>
</div>

<!-- End of Mobile Nav Bar and Beginning of Create Forum Post Pop up Window -->
<div id="forumBody">
    <div id="overlay">
        <form method="post">
            <!-- This first input tag with type=hidden is just a jank way to identify which -->
            <input type="hidden" name="form_id" value="postForm">
            <div id="popupContainer">
                <i class="fa-regular fa-circle-xmark fa-2xl" id="closeButton" onclick="closePopup()"></i>
                <div class="contentitem">
                    <label for="universityforum" id="Unilabel">University</label>
                    <select class="foruminput" id="setPostUniversity" name="setPostUniversity" required>
                        <option value="" disabled selected>Select University</option>
                        <?php foreach ($postUniversities as $postUniversity) : ?>
                            <option value="<?= htmlspecialchars($postUniversity->UniversityID) ?>">
                                <?= htmlspecialchars($postUniversity->Name) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="contentitem">
                    <label for="subjectforum" id="subjectlabel">Subject</label>
                    <select class="foruminput" list="subjectsforum" id="setPostSubject" placeholder="Select Subject" name="setPostSubject">
                        <option value=""></option>
                        <!-- Options will be added here by JavaScript after selecting a university -->
                    </select>
                </div>
                <div class="contentitemtitle">
                    <textarea name="title" type="text" id="titleinput" placeholder="Post Title" rows="2" style="resize: none;" oninput="titlecountChar(this)" required></textarea>
                    <span id="titlecharCount"></span>
                </div>
                <div class="contentitempost">
                    <textarea name="content" id="contentinput" rows="10" placeholder="What do you want to share?" style="resize: none;" onkeyup="contentcountChar(this)" required></textarea>
                    <span id="contentcharCount"></span>
                </div>
                <button type="submit" onclick="closePopup()" class="submitpostbutton">Post</button>
            </div>
        </form>
    </div>
</div>