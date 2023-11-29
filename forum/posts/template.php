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
                            <input type="text" class="commentInput" placeholder="Add a comment..." name="content" onkeypress="handleKeyPress(event)" />
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