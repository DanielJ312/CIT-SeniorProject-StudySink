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
        <h3 style="color: red">THIS PAGE IS CURRENTLY VERY WORK IN PROGRESS</h3>
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
                    <ul>
                        <li><a href="CIT.html">Computer Information Technology</a></li>
                        <li><a href="subject_page.html">Subject 2</a></li>
                        <li><a href="subject_page.html">Subject 3</a></li>
                    </ul>
                </div>
                <div class="posts">
                    <a href="post.html" class="post">
                        <div class="post-header">
                            <img src="https://cdn.glitch.global/c2bf878a-9e4d-451f-afec-4ed46e479314/Screenshot%202023-10-24%20235521.png?v=1698216981476" alt="Place Holder" class="post-profile-picture" />
                            <div class="post-info">
                                <p class="post-account">schoolsucks24</p>
                                <p class="post-date">Posted on October 24, 2023</p>
                            </div>
                        </div>
                        <h3 class="post-title">Post Title 1</h3>
                        <div class="post-content">This is the content of the first post.</div>
                        <div class="vote">
                            <div class="post-iconsp">
                                <i class="far fa-thumbs-up"></i>
                                <i class="far fa-thumbs-down"></i>
                            </div>
                            <div class="votes">
                                (20)</div>
                        </div>
                    </a>

                    <div class="post">
                        <div class="post-header">
                            <img src="https://cdn.glitch.global/c2bf878a-9e4d-451f-afec-4ed46e479314/Screenshot%202023-10-24%20235521.png?v=1698216981476" alt="Place Holder" class="post-profile-picture" />
                            <div class="post-info">
                                <p class="post-account">schoolsucks24</p>
                                <p class="post-date">Posted on October 24, 2023</p>
                            </div>
                        </div>
                        <h3 class="post-title">Post Title 1</h3>
                        <div class="post-content">This is the content of the first post.</div>
                        <div class="vote">
                            <div class="post-iconsp">
                                <i class="far fa-thumbs-up"></i>
                                <i class="far fa-thumbs-down"></i>
                            </div>
                            <div class="votes">
                                (20)</div>
                        </div>
                    </div>
                    <div class="post">
                        <div class="post-header">
                            <img src="https://cdn.glitch.global/c2bf878a-9e4d-451f-afec-4ed46e479314/Screenshot%202023-10-24%20235521.png?v=1698216981476" alt="Place Holder" class="post-profile-picture" />
                            <div class="post-info">
                                <p class="post-account">schoolsucks24</p>
                                <p class="post-date">Posted on October 24, 2023</p>
                            </div>
                        </div>
                        <h3 class="post-title">Post Title 1</h3>
                        <div class="post-content">This is the content of the first post.</div>
                        <div class="vote">
                            <div class="post-iconsp">
                                <i class="far fa-thumbs-up"></i>
                                <i class="far fa-thumbs-down"></i>
                            </div>
                            <div class="votes">
                                (20)</div>
                        </div>
                    </div>
                    <div class="post">
                        <div class="post-header">
                            <img src="https://cdn.glitch.global/c2bf878a-9e4d-451f-afec-4ed46e479314/Screenshot%202023-10-24%20235521.png?v=1698216981476" alt="Place Holder" class="profile-picture" />
                            <div class="post-info">
                                <p class="post-account">schoolsucks24</p>
                                <p class="post-date">Posted on October 24, 2023</p>
                            </div>
                        </div>
                        <h3 class="post-title">Post Title 1</h3>
                        <div class="post-content">This is the content of the first post.</div>
                        <div class="vote">
                            <div class="post-iconsp">
                                <i class="far fa-thumbs-up"></i>
                                <i class="far fa-thumbs-down"></i>
                            </div>
                            <div class="votes">
                                (20)</div>
                        </div>
                    </div>
                    <div class="post">
                        <div class="post-header">
                            <img src="https://cdn.glitch.global/c2bf878a-9e4d-451f-afec-4ed46e479314/Screenshot%202023-10-24%20235521.png?v=1698216981476" alt="Place Holder" class="post-profile-picture" />
                            <div class="post-info">
                                <p class="post-account">schoolsucks24</p>
                                <p class="post-date">Posted on October 24, 2023</p>
                            </div>
                        </div>
                        <h3 class="post-title">Post Title 1</h3>
                        <div class="post-content">This is the content of the first post.</div>
                        <div class="vote">
                            <div class="post-iconsp">
                                <i class="far fa-thumbs-up"></i>
                                <i class="far fa-thumbs-down"></i>
                            </div>
                            <div class="votes">
                                (20)</div>
                        </div>
                    </div>
                    <div class="post">
                        <div class="post-header">
                            <img src="https://cdn.glitch.global/c2bf878a-9e4d-451f-afec-4ed46e479314/Screenshot%202023-10-24%20235521.png?v=1698216981476" alt="Place Holder" class="post-profile-picture" />
                            <div class="post-info">
                                <p class="post-account">schoolsucks24</p>
                                <p class="post-date">Posted on October 24, 2023</p>
                            </div>
                        </div>
                        <h3 class="post-title">Post Title 1</h3>
                        <div class="post-content">This is the content of the first post.</div>
                        <div class="vote">
                            <div class="post-iconsp">
                                <i class="far fa-thumbs-up"></i>
                                <i class="far fa-thumbs-down"></i>
                            </div>
                            <div class="votes">(20)</div>
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
</html>