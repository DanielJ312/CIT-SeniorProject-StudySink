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
    <link rel="stylesheet" href="/styles/university/index.css" id="light-theme"/>
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
            subjectElement.style.display = "block"; // Change to 'block' if needed
        }

        if (!input) {
            subjectElement.style.display = "block";
        } 
    });
}


    </script>
</head>
<body>
    <header>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
    </header>
    <main>
        <div class="margin">
       <!-- <label class="switch" onclick="toggleDarkMode(event)">
  <span class="sun" ><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><g fill="#ffd43b"><circle r="5" cy="12" cx="12"></circle><path d="m21 13h-1a1 1 0 0 1 0-2h1a1 1 0 0 1 0 2zm-17 0h-1a1 1 0 0 1 0-2h1a1 1 0 0 1 0 2zm13.66-5.66a1 1 0 0 1 -.66-.29 1 1 0 0 1 0-1.41l.71-.71a1 1 0 1 1 1.41 1.41l-.71.71a1 1 0 0 1 -.75.29zm-12.02 12.02a1 1 0 0 1 -.71-.29 1 1 0 0 1 0-1.41l.71-.66a1 1 0 0 1 1.41 1.41l-.71.71a1 1 0 0 1 -.7.24zm6.36-14.36a1 1 0 0 1 -1-1v-1a1 1 0 0 1 2 0v1a1 1 0 0 1 -1 1zm0 17a1 1 0 0 1 -1-1v-1a1 1 0 0 1 2 0v1a1 1 0 0 1 -1 1zm-5.66-14.66a1 1 0 0 1 -.7-.29l-.71-.71a1 1 0 0 1 1.41-1.41l.71.71a1 1 0 0 1 0 1.41 1 1 0 0 1 -.71.29zm12.02 12.02a1 1 0 0 1 -.7-.29l-.66-.71a1 1 0 0 1 1.36-1.36l.71.71a1 1 0 0 1 0 1.41 1 1 0 0 1 -.71.24z"></path></g></svg></span>
  <span class="moon" ><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path d="m223.5 32c-123.5 0-223.5 100.3-223.5 224s100 224 223.5 224c60.6 0 115.5-24.2 155.8-63.4 5-4.9 6.3-12.5 3.1-18.7s-10.1-9.7-17-8.5c-9.8 1.7-19.8 2.6-30.1 2.6-96.9 0-175.5-78.8-175.5-176 0-65.8 36-123.1 89.3-153.3 6.1-3.5 9.2-10.5 7.7-17.3s-7.3-11.9-14.3-12.5c-6.3-.5-12.6-.8-19-.8z"></path></svg></span>   
  <input type="checkbox" class="input">
  <span class="slider"></span>
</label>-->
            <div class="university-info">
                <h2><?= $posts[0]->UniversityName;?></h2>
            </div>
            <div class="columns">
                <div class="subject-selection-c" id="subject-selection">
                    
                    <div class="search-bar-csun">
                        <input id="searchbar" type="text" name="search" onkeyup="search_subject()" placeholder="Search Subjects..." />
                        <button><i class="fas fa-search"></i></button>
                    </div>
                    <div class="subjects">
                        <?php foreach ($subjects as $subject) : ?>
                            <ul id='list'><a href="/university/<?= $univeristyAbbr; ?>/<?= strtolower($subject->Abbreviation); ?>"><?= $subject->Name; ?></a></ul>
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

<div class= mobileuniversity>
<div class="university-info">
                <h2>California State University, Northridge</h2>
            </div>
            <div class="subject">
                <div class=title>Subjects</div>
                    <div class=subject-container>
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