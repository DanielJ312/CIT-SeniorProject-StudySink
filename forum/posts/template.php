


<!-- Post Template - Displays post for given Post ID  -->
<?php
// require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/forum-functions.php");
update_session();
$pageTitle = "Forum";

$postID = isset($_GET['url']) ? basename($_GET['url'], '.php') : 'default';
$post = get_post($postID);
if (empty($post)) header("Location: /forum/index.php");
$commentTotal = get_comments($postID);
$commentTotal = is_array($commentTotal) ? count($commentTotal) : "0";

// Code for capturing and storing the Post ID of the 5 most recent posts a user has viewed
$urlPath = $_SERVER['REQUEST_URI']; // e.g., "/forum/posts/6969"
$segments = explode('/', $urlPath);
$postId = end($segments); // grab the end segement
// Verify that the post ID is valid
$post = get_post($postId);
if ($post) {
    // Check if cookie exists
    if (isset($_COOKIE['viewed_posts'])) {
        $viewedPosts = explode(',', $_COOKIE['viewed_posts']);   // Get array of viewed post IDs
        // Check if post ID already exists in array
        if (($key = array_search($postId, $viewedPosts)) !== false) {
            unset($viewedPosts[$key]);    // Remove existing post ID from array
        }
        array_unshift($viewedPosts, $postId);     // Add new post ID to the start of the array
        $viewedPosts = array_slice($viewedPosts, 0, 5);    // Limit array to last 5 post IDs
    } else {
        $viewedPosts = array($postId);    // Create new array with the post ID
    }
    // Update cookie
    setcookie('viewed_posts', implode(',', $viewedPosts), time() + (86400 * 3652.5), "/"); // Expires in 10 years
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
    <script async src="/forum/forum.js"></script>
    <link rel="stylesheet" href="/styles/forum/post-template.css" />
</head>
<body>
    <header>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/to-top.php"); ?>
    </header>
    <main class="post-body">
        <div class="margin">
            <div class="university-info">
                <h2><?= $post->UniversityName; ?></h2>
                <?php if (isset($post->SubjectName)) : ?>
                    <h3><?= $post->SubjectName; ?></h3>
                <?php endif; ?>
            </div>
            <div class="posts">
                <div class="post">
                    <div class="post-header">
                        <img src="<?= $post->Avatar; ?>" title="<?= $post->Username; ?>" alt="Place Holder" class="profile-picture" />
                        <div class="post-info">
                            <p class="post-account"><?= $post->Username; ?></p>
                            <p class="post-date">Posted on <?= display_time($post->PostCreated, "F j, Y"); ?></p>
                        </div>
                        <?php if (check_login()) : ?>
                            <div class="dropdown" onclick="toggleDropdown(this)">
                                <i class="fa-solid fa-ellipsis-vertical ellipsis-icon"></i>
                                <div class="dropdown-content">
                                    <?php if ($_SESSION['USER']->UserID != $comment->UserID) : ?>
                                        <a class="report" onclick="ReportComment(<?= $comment->CommentID; ?>)">Report</a>
                                    <?php endif; ?>
                                    <?php if ($comment->Username == $_SESSION['USER']->Username) : ?>
                                        <a onclick="OpenCommentEditor(<?= $comment->CommentID; ?>)">Edit</a>
                                        <a onclick="DeleteComment(<?= $comment->CommentID; ?>)">Delete</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <h3 class="post-title"><?= $post->Title; ?></h3>
                    <p class="post-content"><?= $post->Content; ?></p>
                    <div class="vote">
                        <div class="post-iconsp">
                        <i class="fa-regular fa-heart fa-lg"></i>
                        </div>
                        <div class="votes">WIP</div>
                    </div>
                </div>
                <!-- Comments Section -->
                <div class="comments">
                    <div class="container">
                        <h4>Comments (<span class="comment-total"><?= $commentTotal; ?></span>)</h4>
                        <form id="sort-dropdown" method="">
                            <?= "<script>var postID = $postID;</script>"; ?>
                            <select id="sort" class="sort" name="sorts">
                                <option value="comment-oldest">Oldest</option>
                                <option value="comment-newest">Newest</option>
                                <option value="comment-popular">Popular</option>
                            </select>
                        </form>
                    </div>
                    <?php if (check_login()) : ?>
                        <div id="add-comment">
                        <div class="comment-bar">
                            <textarea style="resize: auto; height: 15px; width: 612px;" id="commentinput" oninput="commentcountChar(this)"type="text" class="commentInput" placeholder="Add a comment..." name="content" onkeypress="handleKeyPress(event)"></textarea>
                            <span id="commentcharCount"></span>
                            <button onclick="AddComment()" type="submit" value="Submit" class="addComment">Add</button>
                        </div>
                        </div>
                    <?php endif; ?>
                    <div class="sort-container">
                        <!-- Comments will get inserted here -->
                    </div>
                </div>
            </div>
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>
</html>

