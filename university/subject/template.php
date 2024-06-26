<?php
//////////* Subject - Displays selected subject page for given university */////////
require($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
$query = "SELECT * FROM UNIVERSITY_T WHERE Abbreviation = '{$_GET['university']}';";
$university = run_database($query);
is_array($university) ? $university = reset($university) : header("Location: /university/index.php");
$query = "SELECT * FROM SUBJECT_T WHERE Abbreviation = '{$_GET['subject']}' && UniversityID = {$university->UniversityID};";
$subject = run_database($query);
is_array($subject) ? $subject = reset($subject) : header("Location: /university/{$university->Abbreviation}.php");
$pageTitle = "$university->Abbreviation $subject->Name";
$unilogo = "https://studysink.s3.amazonaws.com/assets/Uni-logos/$university->Abbreviation.png";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/to-top.php"); ?>
    <link rel="stylesheet" href="/styles/university/subject.css" />
    <script async src="/university/university.js"></script>
    <script>
        var universityID = <?= $university->UniversityID; ?>, subjectID = <?= $subject->SubjectID; ?>;
    </script>
</head>
<body>
    <header>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
    </header>
    <main>
        <div class="margin">
            <div class="university-info">
                <h2><?= $subject->Name; ?></h2>
                <img src="<?= $unilogo; ?>" alt="School Logo">
            </div>
            <div class="column">
                <div class="study-set">
                    <div class="header">
                        <h2 id="toggleSet">Study Sets<i class="down"></i></h2>
                        <div class="sort-container">
                            <label for="sorts">Sort By: </label>
                            <select id="" class="study-set-sort sort" name="sorts">
                                <option value="study-set-popular">Popular</option>
                                <option value="study-set-newest">Newest</option>
                                <option value="study-set-oldest">Oldest</option>
                            </select>
                        </div>
                    </div>
                    <div class="scrollbar" id="contentset">
                        <div class="displayCardArea">
                            <div class="study-set-sort-container">
                                <!-- Posts will get inserted here -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="posts">
                    <div class="header">
                        <h2 id="togglePost">Posts<i class="down"></i></h2>
                        <div class= "sort-container">
                            <label for="sorts" style="align-self: right;">Sort By: </label>
                            <select class="post-sort sort" name="sorts">
                                <option value="post-popular">Popular</option>
                                <option value="post-newest">Newest</option>
                                <option value="post-oldest">Oldest</option>
                            </select>
                        </div>
                    </div>
                    <div class="scrollbar" id="contentpost">
                        <div class="post-sort-container">
                            <!-- Posts will get inserted here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>
<script>
    window.history.replaceState({}, '', '/university/<?= $university->Abbreviation; ?>/<?= $subject->Abbreviation; ?>');
    document.getElementById('toggleSet').addEventListener('click', function() {
        if (window.innerWidth <= 850) {
            var contentDiv = document.getElementById('contentset');
            if (contentDiv.style.display === 'none' || window.getComputedStyle(contentDiv).display === 'none') {
                contentDiv.style.display = 'block'; // or any other desired display value
            } else {
                contentDiv.style.display = 'none';
            }
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        var contentDiv = document.getElementById('contentpost');

        // Initially show the content
        contentDiv.style.display = 'block';

        document.getElementById('togglePost').addEventListener('click', function() {
            if (window.innerWidth <= 850) {
                if (contentDiv.style.display === 'none' || window.getComputedStyle(contentDiv).display === 'none') {
                    contentDiv.style.display = 'block';
                } else {
                    contentDiv.style.display = 'none';
                }
            }
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        var gridItems = document.querySelectorAll('.post-content');
        gridItems.forEach(function(item) {
            var text = item.textContent;
            if (text.length > 50) {
                item.textContent = text.substring(0, 50) + '...';
            }
        });
    });
</script>
</html>