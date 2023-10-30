<?php
    require($_SERVER['DOCUMENT_ROOT'] . "/functions.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php if(isset($pageTitle)) {echo "$pageTitle";} ?></title>
    <link rel="stylesheet" href="/styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<header>
    <h1>Login & Registration System</h1>
    <nav class="navbar">
        <a class="<?php check_active_page('/index.php');?>" href="/index.php">Home</a>
        <a class="<?php check_active_page('/request.php');?>" href="/request.php">Request</a>
        <a class="<?php check_active_page('/Posts/index.php');?>" href="/posts/index.php">Posts</a>
        <a class="<?php check_active_page('/account/profile.php');?>" href="/account/profile.php">Profile</a>
        <a class="<?php check_active_page('/account/register.php');?>" href="/account/register.php">Registration</a>
        <a class="<?php check_active_page('/account/login.php');?>" href="/account/login.php">Login</a>
        <a class="<?php check_active_page('/account/logout.php');?>" href="/account/logout.php">Logout</a>
        <a class="<?php check_active_page('/account/forgot.php');?>" href="/account/forgot.php">Reset</a>
        <a class="<?php check_active_page('/study-sets/create-study-set.php');?>" href="/study-sets/create-study-set.php">Create Study Set</a>
        <div style="float:right;">
            <?php if (!isset($_SESSION['USER'])): ?>
                <a class="dropdown" href="/account/login.php">Login</a>
            <?php else: ?>
                <div class="dropdown">
                    <button class="dropbtn <?php check_active_page('/account/profile.php');?>"><?=$_SESSION['USER']->username?></button>
                    <div class="dropdown-content">
                        <a class="<?php check_active_page('/account/profile.php');?>" href="/account/profile.php">Profile</a>
                        <a href="">Settings</a>
                        <a href="/account/logout.php">Logout</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </nav> 
</header>
</html>