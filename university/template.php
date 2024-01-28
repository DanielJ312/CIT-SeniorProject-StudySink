<!-- Post Template - Displays post for given Post ID  -->
<?php
// require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/forum-functions.php");
update_session();
$pageTitle = "University";

$univeristyAbbr = isset($_GET['url']) ? basename($_GET['url'], '.php') : 'default';
// $post = get_post($postID);
$query = <<<query
    SELECT PostID, Title, Content, POST_T.Created AS PostCreated, Username, Avatar, POST_T.UniversityID, UNIVERSITY_T.Name AS UniversityName
    FROM POST_T INNER JOIN USER_T ON POST_T.UserID = USER_T.UserID
        INNER JOIN UNIVERSITY_T ON UNIVERSITY_T.UniversityID = POST_T.UniversityID
    WHERE UNIVERSITY_T.Abbreviation = '$univeristyAbbr'
    ORDER BY POST_T.Created ASC;
query;
// AND POST_T.SubjectID = '0'

$posts = run_database($query);
if (empty($posts)) header("Location: /forum/index.php");

$query = "SELECT * FROM SUBJECT_T WHERE UniversityID = {$posts[0]->UniversityID} ORDER BY Name ASC;";
$subjects = run_database($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
    <link rel="stylesheet" href="/styles/university/index.css" />
    <script>
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
</head>
<body>
    <header>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
        <h3 style="color: red">THIS PAGE IS CURRENTLY WORK IN PROGRESS</h3>
    </header>
    <main>
        <div class="margin">
            <div class="university-info">
                <h2><?= $posts[0]->UniversityName;?></h2>
            </div>
            <div class="columns">
                <div class="subject-selection-c" id="subject-selection">
                    <h2>Subjects</h2>
                    <div class="search-bar-csun">
                        <input type="text" placeholder="Search Subjects..." />
                        <button><i class="fas fa-search"></i></button>
                    </div>
                    <div class="subjects">
                        <?php foreach ($subjects as $subject) : ?>
                            <ul><a href="/university/<?= $univeristyAbbr; ?>/<?= strtolower($subject->Abbreviation); ?>"><?= $subject->Name; ?></a></ul>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="posts">
                <?php foreach ($posts as $post) : ?>
                    <a href="/forum/posts/<?= $post->PostID; ?>" class="post">
                        <div class="post-header">
                            <img src="<?= $post->Avatar; ?>" alt="Place Holder" class="post-profile-picture" />
                            <div class="post-info">
                                <p class="post-account"><?= $post->Username; ?></p>
                                <p class="post-date"><?= display_time($post->PostCreated, "F j, Y"); ?></p>
                            </div>
                        </div>
                        <h3 class="post-title"><?= $post->Title; ?></h3>
                        <div class="post-content"><?= $post->Content; ?></div>
                        <div class="vote">
                            <div class="post-iconsp">
                                <i class="fa-regular fa-heart"></i>
                            </div>
                            <div class="votes">(20)</div>
                        </div>
                    </a>
                <?php endforeach; ?>
                </div>
            </div>
        </div>
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>
</html>