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
    <!--<link rel="stylesheet" href="/styles/university/dark-mode.css" id="dark-theme"/>-->
    <link rel="stylesheet" href="/styles/university/university.css"/>
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

        // Function to toggle dark mode
        function toggleDarkMode(event) {
    event.preventDefault(); // Prevent the default action

    const body = document.body;
    body.classList.toggle('dark-mode');

    // Save the current theme preference to localStorage
    const isDarkMode = body.classList.contains('dark-mode');
    localStorage.setItem('darkMode', isDarkMode);

    // Toggle the active theme stylesheet
    const lightTheme = document.getElementById('light-theme');
    const darkTheme = document.getElementById('dark-theme');
    lightTheme.disabled = isDarkMode;
    darkTheme.disabled = !isDarkMode;
}

        // Check the user's theme preference from localStorage
        const savedDarkMode = localStorage.getItem('darkMode');
        if (savedDarkMode === 'true') {
            document.body.classList.add('dark-mode');
            toggleDarkMode(); // Toggle the active theme stylesheet
        }


        // JavaScript code

        function search_subject() {
    let input = document.getElementById('searchbar').value.toLowerCase();
    let subjects = document.querySelectorAll('.subject-selection-c .subjects a');

    subjects.forEach(subject => {
        let subjectText = subject.textContent.toLowerCase();
        let subjectElement = subject.parentElement;

        if (!subjectText.includes(input)) {
            subjectElement.style.display = "none";
        } else {
            subjectElement.style.display = "block"; 
        }

        if (!input) {
            subjectElement.style.display = "block";
        } 
    });
}


function search_subject_mobile() {
    let input = document.getElementById('searchbar2').value.toLowerCase();
    let subjects = document.querySelectorAll('.subject-selection-c .subjects a');

    subjects.forEach(subject => {
        let subjectText = subject.textContent.toLowerCase();
        let subjectElement = subject.parentElement;

        if (!subjectText.includes(input)) {
            subjectElement.style.display = "none";
        } else {
            subjectElement.style.display = "block"; 
        }

        if (!input) {
            subjectElement.style.display = "block";
        } 
    });
}

function toggleSubject() {
        var contentSubject = document.getElementById('contentsubject');
        if (contentSubject.style.display === 'none' || contentSubject.style.display === '') {
            contentSubject.style.display = 'block';
        } else {
            contentSubject.style.display = 'none';
        }
    }


    </script>
</head>
<body>
    <header>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/to-top.php"); ?>
    </header>
    <main>
        <div class="margin">
            <div class="university-info">
                <h2><?= $posts[0]->UniversityName;?></h2>
            </div>
            <div class="columns">
                <div class="subject-selection-c">
                    <div class="search-bar-csun">
                        <input id="searchbar" type="text" name="search" onkeyup="search_subject()" placeholder="Search Subjects..." />
                        <button><i class="fas fa-search"></i></button>
                    </div>
                    <div class="subjects">
                        <?php foreach ($subjects as $subject) : ?>
                            <ul><a href="/university/<?= $univeristyAbbr; ?>/<?= strtolower($subject->Abbreviation); ?>"><?= $subject->Name; ?></a></ul>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="posts">
                    <div class="post header">Posts</div>
                    <form method="post">
            <select class="sort" name="sorts">
                <option value="post-oldest">Oldest</option>
                <option value="post-newest">Newest</option>
                <option value="post-popular">Popular</option>
                <?= "<script>var postID = 0;</script>"; ?>
            </select>
        </form>
                <?php foreach ($posts as $post) : ?>
                    <a href="/forum/posts/<?= $post->PostID; ?>" class="post">
                        <div class="post-header">
                            <img src="<?= $post->Avatar; ?>" alt="Place Holder" class="post-profile-picture" />
                            <div class="post-info">
                                <p class="post-account"><?= $post->Username; ?></p>
                                <p class="post-date"><?= date("F j, Y", $post->PostCreated); ?></p>
                            </div>
                        </div>
                        <h3 class="post-title"><?= $post->Title; ?></h3>
                        <div class="post-content"><?= $post->Content; ?></div>
                        <div class="lower-header">
                            <div class="comment">
                                <div class="post-iconsp">
                                <i class="fa-regular fa-comment"></i>
                                </div>
                                <div class="comments-count">0</div>
                            </div>
                            <div class="vote">
                                <div class="post-iconsp">
                                <i class="fa-regular fa-heart"></i>
                                </div>
                                <div class="votes">(20)</div>
                            </div>
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

<div class= mobileuniversity>
<div class="university-info">
                <h2>California State University, Northridge</h2>
            </div>
            <div class="subject">
                <h2 id="toggleSubject" onclick="toggleSubject()">Subjects<i class="down"></i></h2>
                <div class="subject-selection-c" id="contentsubject">
                    <div class="search-bar-csun">
                        <input id="searchbar2" type="text" name="search" onkeyup="search_subject_mobile()" placeholder="Search Subjects..." />
                        <button><i class="fas fa-search"></i></button>
                    </div>
                    <div class="subjects">
                        <?php foreach ($subjects as $subject) : ?>
                            <ul><a href="/university/<?= $univeristyAbbr; ?>/<?= strtolower($subject->Abbreviation); ?>"><?= $subject->Name; ?></a></ul>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="mobile-posts">
                    <div class="post-header"><div class=title>Posts</div>
                    <form method="post">
            <select class="sort" name="sorts">
                <option value="post-oldest">Oldest</option>
                <option value="post-newest">Newest</option>
                <option value="post-popular">Popular</option>
                <?= "<script>var postID = 0;</script>"; ?>
            </select>
                    </form>
                    </div>
                <?php foreach ($posts as $post) : ?>
                    <a href="post.html" class="post">
                        <div class="post-header">
                            <img src="<?= $post->Avatar; ?>" alt="Place Holder" class="post-profile-picture" />
                            <div class="post-info">
                                <p class="post-account"><?= $post->Username; ?></p>
                                <p class="post-date"><?= date("F j, Y", $post->PostCreated); ?></p>
                            </div>
                        </div>
                        <h3 class="post-title">Temp Title</h3>
                        <div class="post-content">This is the content of the first post.</div>
                        <div class="lower-header">
                            <div class="comment">
                                <div class="post-iconsp">
                                <i class="fa-regular fa-comment"></i>
                                </div>
                                <div class="comments-count">0</div>
                            </div>
                            <div class="vote">
                                <div class="post-iconsp">
                                <i class="fa-regular fa-heart"></i>
                                </div>
                                <div class="votes">(20)</div>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
                </div>
</div> 