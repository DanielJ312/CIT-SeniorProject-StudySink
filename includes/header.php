<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/forum-functions.php");
$query = "SELECT * FROM UNIVERSITY_T;";
$universitiesforum = get_universities_list();
$postErrors = $_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['university']) ? create_post($_POST) : [];
?>

<!-- Header - Contains HTML injected into the header tag -->
<!-- Full Size Navbar -->
<div class="Navbody">
    <div class="navbarMain">
        <div class="navbar-left">
            <a href="/index.php" id="Home"><img src="https://studysink.s3.amazonaws.com/assets/StudySinkBanner.png" alt="Company Logo" class="companyLogo" title="Home"></a>
        </div>
        <div class="navbar-center">
            <div class="search-container">
                <form action="../results.php" method="GET">
                    <input type="text" id="searchBar" name="search" placeholder="Search Study sets, Universities, Posts">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </form>
            </div>
        </div>
    <?php if (check_login()) : ?>
        <div class="navbar-right">
            <div class="dropdown" style="padding-top: 15px; padding-bottom: 15px;">
                <i class="fa-solid fa-circle-plus fa-2xl <?= check_active('/study-sets/create'); ?>" id="createIcon" title="Create"></i>
                <div class="dropdown-content-create" id="createDropdown">
                    <a href="/study-sets/create.php">Create Study Set</a>
                    <a onclick="<?= $_SESSION['USER']->Verified == 1 ? "openPopup()" : ""?>">Create Post</a>
                </div>
            </div>
            <a href="/index.php" id="Home" title="Home"><i class="fa-solid fa-house fa-2xl <?= check_active('/index', 'home'); ?>"></i></a>
            <a href="/university/<?= isset($_SESSION['USER']->Abbreviation) ? $_SESSION['USER']->Abbreviation : "index"; ?>.php" id="University" title="My University"><i class="fa-solid fa-graduation-cap fa-flip-horizontal fa-2xl <?= check_active('/university'); ?>"></i></a>
            <div class="dropdown">
                <img src="<?= $_SESSION['USER']->Avatar ?>" alt="Avatar" class="profile-picture <?= check_active('/account/profile'); ?>" id="profilePicture" title="Avatar">
                <div class="dropdown-content-profile" id="profileDropdown">
                    <a href="/account/profile.php">Profile</a>
                    <a href="/account/settings.php">Settings</a>
                    <a href="/request/index.php">Help/Request University</a>
                    <a href="/account/logout.php">Logout</a>
                </div>
            </div>
        </div>
    <?php else : ?>
        <div class="navbar-right">
            <a href="/index.php" id="Home" title="Home"><i class="fa-solid fa-house fa-2xl <?= check_active('/index', 'home'); ?>"></i></a>
            <a href="/account/login.php" id="Login" title="Login or Register"><i class="fa-solid fa-id-card fa-2xl <?= check_active('/account'); ?>"></i></a>
        </div>
    <?php endif; ?>
    </div>

    <!-- End of Full Size Nav bar and Beginning of Mobile Nav Bar -->
    <div class="navbarmobile">
        <header class="mobileheader" style="height: 20px;">
            <i class="fa-solid fa-bars fa-2xl" id="menuIcon" title="Menu Icon"></i>
            <img id="logo" src="https://studysink.s3.amazonaws.com/assets/StudySinkBanner.png" alt="Company Logo" title="Home" style="margin-top: -10px; margin-bottom: -10px;">
            <nav class="mobilenav">
                <div>
                    <form id="search-form" class="searchform">
                        <a href="SearchResults" class="mag"><i class="fa-solid fa-magnifying-glass fa-sm" style="color: #000000; padding-left: 2px; margin-bottom: 3px"></i></a>
                        <input type="text" id="search-input" placeholder="Search" class="searchbar" title="search">
                    </form>
                </div>
                <div class="nav-options">
                    <div class="navitem"><a href="/index.php" title="Home">Home</a></div>
                <?php if (check_login()) : ?>
                    <div class="navitem"><a href="/university/<?= isset($_SESSION['USER']->Abbreviation) ? $_SESSION['USER']->Abbreviation : "index"; ?>" title="My University">My University</a></div>
                    <div class="dropdown">
                        <div class="navitem"><a href="#" title="Create">Create</a></div>
                        <div class="dropdown-content">
                            <div class="navitem"><a href="/study-sets/create.php" title="Create Study Set">Study Set</a></div>
                            <div class="navitem"><a title="Create Post" onclick="openPopup()">Post</a></div>
                        </div>
                    </div>
                    <div class="dropdown">
                        <div class="navitem"><a href="#" style="border-bottom-color: black; border-bottom-width: 2px; border-bottom-style: solid;" title="Profile">Profile</a></div>
                        <div class="dropdown-content">
                            <a href="/account/profile.php" style="border-top-width: 0px;" title="My Profile">My Profile</a>
                            <div class="navitem"><a href="/account/settings.php" title="Settings">Settings</a></div>
                            <div class="navitem"><a href="/request/index.php" title="Help">Help/Request Uni</a></div>
                            <div class="navitem"><a href="/request/logout.php" style="border-bottom-color: black; border-bottom-width: 2px; border-bottom-style: solid;" title="Logout">Logout</a></div>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="navitem"><a href="/account/login.php" title="Home">Login</a></div>
                    <div class="navitem"><a href="/account/register.php" title="Home">Register</a></div>
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
            <div id="popupContainer">
                <i class="fa-regular fa-circle-xmark fa-2xl" id="closeButton" onclick="closePopup()"></i>
                <div class="contentitem">
                    <label for="universityforum" id="Unilabel">University</label>
                    <input class="foruminput" list="universitiesforum" id="setUniversityforum" placeholder="Select from the dropdown" name="university" required>
                    <datalist id="universitiesforum">
                        <?php foreach ($universitiesforum as $universityforum) : ?>
                            <option value="<?= htmlspecialchars($universityforum->Name) ?>" data-id="<?= $universityforum->UniversityID ?>">
                                <?= htmlspecialchars($universityforum->Name) ?>
                            </option>
                        <?php endforeach; ?>
                    </datalist>
                </div>
                <div class="contentitem">
                    <label for="subjectforum" id="subjectlabel">Subject</label>
                    <input class="foruminput" list="subjectsforum" id="setSubjectforum" placeholder="Select from the dropdown" name="subject">
                    <datalist id="subjectsforum">
                        <!-- Options will be added here by JavaScript after selecting a university -->
                    </datalist>
                </div>
                <div class="contentitemtitle">
                    <textarea name="title" type="text" id="titleinput" placeholder="Post Title" rows="2" style="resize: none;" oninput="titlecountChar(this)" required></textarea>
                    <span id="titlecharCount"></span>
                </div>
                <div class="contentitempost">
                    <textarea name="content" id="contentinput" rows="10" placeholder="What do you want to share?" style="resize: none;" onkeyup="contentcountChar(this)" required></textarea>
                    <span id="contentcharCount"></span>
                </div>
                <button type="submit" onclick="closePopup()" class="submitpostbutton">
                    <span class="shadow"></span>
                    <span class="edge"></span>
                    <span class="front text">Post</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- End of Create Forum Post Pop up Window and Beginning of Development Navbar For Easy Access -->
<div class="dev-navbar" style="display: none">
    <nav class="navbar">
        <span>Development Navbar:</span>
        <a class="<?php check_active('/', 'home'); ?>" href="/index.php">Home</a>
        <div class="dropdowndev">
            <button class="dropbtn <?php check_active('/request'); ?>">Request</button>
            <div class="dropdowndev-content">
                <a class="<?php check_active('/request/index'); ?>" href="/request/index.php">Submit</a>
                <a class="<?php #check_active(''); ?>" href="">Success</a>
            </div>
        </div>
        <div class="dropdowndev">
            <button class="dropbtn <?php check_active('/forum'); ?>">Forum</button>
            <div class="dropdowndev-content">
                <a class="<?php check_active('/forum/index'); ?>" href="/forum/index.php">Posts</a>
                <a class="<?php check_active('/forum/create'); ?>" href="/forum/create.php">Create</a>
            </div>
        </div>
        <div class="dropdowndev">
            <button class="dropbtn <?php check_active('/study-sets'); ?>">Study Sets</button>
            <div class="dropdowndev-content">
                <a class="<?php check_active('/study-sets/index'); ?>" href="/study-sets/index.php">Study Sets</a>
                <a class="<?php check_active('/study-sets/create'); ?>" href="/study-sets/create.php">Create</a>
            </div>
        </div>
        <div class="dropdowndev">
            <button class="dropbtn <?php check_active('/university'); ?>">University</button>
            <div class="dropdowndev-content">
                <a class="<?php check_active('/university/index'); ?>" href="/university/index.php">Home</a>
                <a class="<?php check_active('/university/csun'); ?>" href="/university/csun.php">CSUN</a>
                <a class="<?php check_active('/university/ucla'); ?>" href="/university/ucla.php">UCLA</a>
            </div>
        </div>
        <div class="dropdowndev">
            <button class="dropbtn <?php check_active('/account'); ?>">Account</button>
            <div class="dropdowndev-content">
                <a class="<?php check_active('/account/profile'); ?>" href="/account/profile.php">Profile</a>
                <a class="<?php check_active('/account/register'); ?>" href="/account/register.php">Registration</a>
                <a class="<?php check_active('/account/login'); ?>" href="/account/login.php">Login</a>
                <a class="<?php check_active('/account/logout'); ?>" href="/account/logout.php">Logout</a>
                <a class="<?php check_active('/account/forgot'); ?>" href="/account/forgot.php">Reset</a>
            </div>
        </div>
        <div style="float:right;">
            <?php if (!check_login()) : ?>
                <a class="dropdowndev" href="/account/login">Login</a>
            <?php else : ?>
                <div class="dropdowndev">
                    <button class="dropbtn <?php check_active('/account/profile'); ?>"><?= $_SESSION['USER']->Username ?></button>
                    <div class="dropdowndev-content">
                        <a class="<?php check_active('/account/profile'); ?>" href="/account/profile.php">Profile</a>
                        <a href="">Settings</a>
                        <a href="/account/logout.php">Logout</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </nav>
</div>
