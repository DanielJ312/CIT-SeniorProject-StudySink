<!-- Subject Page Within University. -->
<?php
require($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
update_session();

$query = "SELECT * FROM UNIVERSITY_T WHERE Abbreviation = '{$_GET['university']}';";
$university = run_database($query)[0];
$query = "SELECT * FROM SUBJECT_T WHERE Abbreviation = '{$_GET['subject']}' && UniversityID = {$university->UniversityID};";
$subject = run_database($query)[0];

$pageTitle = "$university->Abbreviation $subject->Name";
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
            </div>
            <div class="column">
                <div class="study-set">
                    <div class="header">
                        <h2 id="toggleSet">Study Sets<i class="arrow down"></i></h2>
                        <select id="" class="study-set-sort sort" name="sorts">
                            <option value="study-set-popular">Popular</option>
                            <option value="study-set-newest">Newest</option>
                            <option value="study-set-oldest">Oldest</option>
                        </select>
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
                        <h2 id="togglePost">Posts<i class="arrow down"></i></h2>
                        <select class="post-sort sort" name="sorts">
                            <option value="post-popular">Popular</option>
                            <option value="post-newest">Newest</option>
                            <option value="post-oldest">Oldest</option>
                        </select>
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
    <script>
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
</body>
</html>