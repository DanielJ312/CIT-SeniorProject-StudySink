<!-- Post Template - Displays post for given Post ID  -->
<?php
// require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/forum-functions.php");
update_session();
$pageTitle = "University";

// $postID = isset($_GET['url']) ? basename($_GET['url'], '.php') : 'default';
// $post = get_post($postID);
// if (empty($post)) header("Location: /forum/index.php");
// $commentTotal= get_comments($postID);
// $commentTotal = is_array($commentTotal) ? count($commentTotal) : "0";
$univeristyID = 1;

$query = "SELECT * FROM SUBJECT_T WHERE UniversityID = $univeristyID ORDER BY Name ASC;";
$subjects = run_database($query);

// $query = "SELECT * FROM POST_T INNER JOIN USER_T ON POST_T.UserID = USER_T.UserID WHERE POST_T.UniversityID = $univeristyID ORDER BY POST_T.Created ASC;";
$query = <<<query
    SELECT PostID, Title, Content, POST_T.Created AS PostCreated, Username, Avatar
    FROM POST_T INNER JOIN USER_T ON POST_T.UserID = USER_T.UserID 
    WHERE POST_T.UniversityID = $univeristyID ORDER BY POST_T.Created ASC;
    query;
$posts =  run_database($query);

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
                if (text.length > 31) {
                    item.textContent = text.substring(0, 31) + '...';
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
                <h2>California State University, Northridge</h2>
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
                            <ul><a href=""><?= $subject->Name; ?></a></ul>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="posts">
                <?php foreach ($posts as $post) : ?>
                    <a href="post.html" class="post">
                        <div class="post-header">
                            <img src="<?= $post->Avatar; ?>" alt="Place Holder" class="post-profile-picture" />
                            <div class="post-info">
                                <p class="post-account"><?= $post->Username; ?></p>
                                <p class="post-date"><?= display_time($post->PostCreated, "F j, Y"); ?></p>
                            </div>
                        </div>
                        <h3 class="post-title">Temp Title</h3>
                        <div class="post-content">This is the content of the first post.</div>
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