<?php
//////////* University Template - Displays posts and subjects */////////
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/forum-functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/university-functions.php");
$university = get_university(get_end_url());
$university != false ? $subjects = get_university_subjects($university->UniversityID) : header("Location: /university/index.php"); 
$pageTitle = $university->Abbreviation;
$unilogo = "https://studysink.s3.amazonaws.com/assets/Uni-logos/$university->Abbreviation.png";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/to-top.php"); ?>
    <!--<link rel="stylesheet" href="/styles/university/dark-mode.css" id="dark-theme"/>-->
    <link rel="stylesheet" href="/styles/university/university.css" />
    <script async src="/university/university.js"></script>
    <script>
        var universityID = <?= $university->UniversityID; ?>, subjectID = 0;
    </script>
</head>
<body>
    <header>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
    </header>
    <main id="space">
        <a class="all" href="/university/index.php"><&nbsp;&nbsp; All Universities</a>
        <div class="margin">
            <div class="university-info">
                <h2><?= $university->Name; ?></h2>
                <img src="<?= $unilogo; ?>" alt="School Logo">
            </div>
            <div class="columns">
                <div class="subject-selection-c">
                    <div class="search-bar-csun">
                        <input id="searchbar" type="text" name="search" onkeyup="search_subject()" placeholder="Search Subjects..." />
                        <button><i class="fas fa-search"></i></button>
                    </div>
                    <div class="subjects">
                        <?php if (!empty($subjects)) : ?>
                            <?php foreach ($subjects as $subject) : ?>
                                <ul><a href="/university/<?= $university->Abbreviation; ?>/<?= $subject->Abbreviation; ?>"><?= $subject->Name; ?></a></ul>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <ul>There are currently no subjects for <?= $university->Abbreviation; ?>.</ul>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="posts">
                    <div class="post header">Posts</div>
                    <form id="sort-dropdown" method="">
                        <label for="sort">Sort By: </label>
                        <select id="sort" class="post-sort sort" name="sorts">
                            <option value="post-newest">Newest</option>
                            <option value="post-popular">Popular</option>
                            <option value="post-oldest">Oldest</option>
                        </select>
                    </form>
                    <div class="post-sort-container">
                        <!-- Posts will get inserted here -->
                    </div>
                </div>
            </div>
        </div>
    </main>
    <div class=mobileuniversity>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/to-top.php"); ?>
        <div class="university-info">
            <h2><?= $university->Name; ?></h2>
            <img src="<?= $unilogo; ?>" alt="School Logo">
        </div>
        <div class="subject">
            <h2 id="toggleSubject" onclick="toggleSubject()">Subjects<i class="down"></i></h2>
            <div class="subject-selection-c" id="contentsubject">
                <div class="search-bar-csun">
                    <input id="searchbar2" type="text" name="search" onkeyup="search_subject_mobile()" placeholder="Search Subjects..." />
                    <button><i class="fas fa-search"></i></button>
                </div>
                <div class="subjects">
                    <?php if (!empty($subjects)) : ?>
                        <?php foreach ($subjects as $subject) : ?>
                            <ul><a href="/university/<?= $university->Abbreviation; ?>/<?= $subject->Abbreviation; ?>"><?= $subject->Name; ?></a></ul>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <ul>There are currently no subjects for <?= $university->Abbreviation; ?>.</ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="mobile-posts">
            <div class="post-header">
                <div class=title>Posts</div>
                <form method="">
                    <label for="sort" style="font-size: 0.75em;">Sort By: </label>
                    <select id="sort" class="post-sort sort" name="sorts">
                        <option value="post-newest">Newest</option>
                        <option value="post-oldest">Oldest</option>
                        <option value="post-popular">Popular</option>
                    </select>
                </form>
                <div class="post-sort-container">
                    <!-- Posts will get inserted here -->
                </div>
            </div>
        </div>
    </div>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>
<script>
    window.history.replaceState({}, '', '/university/<?= strtoupper($university->Abbreviation); ?>');
</script>
</html>