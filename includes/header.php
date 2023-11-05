<!-- Header - Contains HTML injected into the header tag -->
<!-- Primary Navbar -->
<div class="body">
    <div class="navbarMain">
        <div class="navbar-left">
            <a href="/index.php" id="Home"><img src="https://studysink.s3.amazonaws.com/assets/StudySinkBanner.png" alt="Company Logo" class="companyLogo" title="Home"></a>
        </div>
        <div class="navbar-center">
            <div class="search-container">
                <a href="SearchResults"><i class="fa-solid fa-magnifying-glass fa-xl" style="color: #000000;"></i></a>
                <input type="text" id="searchBar" placeholder="Search Study sets, Universities, Posts" style="padding-left: 35px;">
            </div>
        </div>
    <?php if (check_login()) : ?>
        <div class="navbar-right">
            <div class="dropdown" style="padding-top: 15px; padding-bottom: 15px;">
                <i class="fa-solid fa-circle-plus fa-2xl" id="createIcon" title="Create" style="color: black;"></i>
                <div class="dropdown-content-create" id="createDropdown">
                    <a href="/study-sets/create.php">Create Study Set</a>
                    <a href="/forum/create.php">Create Post</a>
                </div>
            </div>
            <a href="/index.php" id="Home" title="Home"><i class="fa-solid fa-house fa-2xl"></i></a>
            <a href="UniversityPage" id="University" title="My University"><i class="fa-solid fa-graduation-cap fa-flip-horizontal fa-2xl"></i></a>
            <div class="dropdown">
            <img src="<?= $_SESSION['USER']->Avatar ?>" alt="Avatar" class="profile-picture" id="profilePicture" title="Avatar">
                <div class="dropdown-content-profile" id="profileDropdown">
                    <a href="/account/profile.php">Profile</a>
                    <a href="/account/settings.php">Settings</a>
                    <a href="/request/index.php">Help/Request University</a>
                    <a href="/account/logout.php">Logout</a>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="navbar-right">
            <a href="/index.php" id="Home" title="Home"><i class="fa-solid fa-house fa-2xl"></i></a>
            <div class="dropdown">
                <span>Login Or Register</span>
                <img src="https://studysink.s3.amazonaws.com/assets/DefaultAvatar.jpg" alt="Pic" class="profile-picture" id="profilePicture" title="Profile">
                <div class="dropdown-content-profile" id="profileDropdown">
                    <a href="/account/login.php">Login</a>
                    <a href="/account/register.php">Register</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
    </div>
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
                    <div class="navitem"><a href="University" title="My University">My University</a></div>
                    <div class="dropdown">
                        <div class="navitem"><a href="#" title="Create">Create</a></div>
                        <div class="dropdown-content">
                            <div class="navitem"><a href="/study-sets/create.php" title="Create Study Set">Study Set</a></div>
                            <div class="navitem"><a href="/forum/create.php" title="Create Post">Post</a></div>
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
                <?php else: ?>
                    <div class="navitem"><a href="/login.php" title="Home">Login</a></div>
                    <div class="navitem"><a href="/register.php" title="Home">Register</a></div>
                <?php endif; ?>
                </div>
            </nav>
        <header>
    </div>
</div>

<!-- Development Navbar For Easy Access -->
<nav class="navbar">
    <span>Development Navbar:</span>
    <a class="<?php check_active_page('/index.php'); ?>" href="/index.php">Home</a>
    <div class="dropdowndev">
        <button class="dropbtn <?php check_active_dir('/request'); ?>">Request</button>
        <div class="dropdowndev-content">
            <a class="<?php check_active_page('/request/index.php'); ?>" href="/request/index.php">Submit</a>
            <a class="<?php check_active_page(''); ?>" href="">Success</a>
        </div>
    </div>
    <div class="dropdowndev">
        <button class="dropbtn <?php check_active_dir('/forum'); ?>">Forum</button>
        <div class="dropdowndev-content">
            <a class="<?php check_active_page('/forum/index.php'); ?>" href="/forum/index.php">Posts</a>
            <a class="<?php check_active_page('/forum/create.php'); ?>" href="/forum/create.php">Create</a>
        </div>
    </div>
    <div class="dropdowndev">
        <button class="dropbtn <?php check_active_dir('/study-sets'); ?>">Study Sets</button>
        <div class="dropdowndev-content">
            <a class="<?php check_active_page(''); ?>" href="">Study Sets</a>
            <a class="<?php check_active_page('/study-sets/create.php'); ?>" href="/study-sets/create.php">Create</a>
        </div>
    </div>
    <div class="dropdowndev">
        <button class="dropbtn <?php check_active_dir('/account'); ?>">Account</button>
        <div class="dropdowndev-content">
            <a class="<?php check_active_page('/account/profile.php'); ?>" href="/account/profile.php">Profile</a>
            <a class="<?php check_active_page('/account/register.php'); ?>" href="/account/register.php">Registration</a>
            <a class="<?php check_active_page('/account/login.php'); ?>" href="/account/login.php">Login</a>
            <a class="<?php check_active_page('/account/logout.php'); ?>" href="/account/logout.php">Logout</a>
            <a class="<?php check_active_page('/account/forgot.php'); ?>" href="/account/forgot.php">Reset</a>
        </div>
    </div>
    <div style="float:right;">
        <?php if (!check_login()) : ?>
            <a class="dropdowndev" href="/account/login.php">Login</a>
        <?php else : ?>
            <div class="dropdowndev">
                <button class="dropbtn <?php check_active_page('/account/profile.php'); ?>"><?= $_SESSION['USER']->Username ?></button>
                <div class="dropdowndev-content">
                    <a class="<?php check_active_page('/account/profile.php'); ?>" href="/account/profile.php">Profile</a>
                    <a href="">Settings</a>
                    <a href="/account/logout.php">Logout</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</nav>
<h1>StudySink Backend Development</h1>