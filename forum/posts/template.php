<!-- Post Template - Displays post for given Post ID  -->
<?php
// require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/forum-functions.php");
update_session();
$pageTitle = "Forum";

$postID = isset($_GET['url']) ? basename($_GET['url'], '.php') : 'default';
$post = get_post($postID);
if (empty($post)) header("Location: /forum/index.php");
$comments = get_comments($postID);

if ($_SERVER['REQUEST_METHOD'] == "POST") add_comment($_POST, $post->PostID);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
    <script async src="/forum/forum.js"></script>
    <link rel="stylesheet" href="/styles/post-template.css" />
</head>
<body>
    <header>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
        <!-- <h2><?= isset($pageTitle) ? $pageTitle : "Page Header" ?></h2> -->
    </header>
    <main class="post-body">
        <div class="margin">
            <div class="university-info">
                <h2><?= $post->UniversityName; ?></h2>
                <?php if (isset($post->SubjectName)) : ?>
                    <h3><?= $post->SubjectName; ?></h3>
                <?php endif ?>
            </div>
            <div class="posts">
                <div class="post">
                    <div class="post-header">
                        <img src="<?= $post->Avatar; ?>" title="<?= $post->Username; ?>" alt="Place Holder" class="profile-picture" />
                        <div class="post-info">
                            <p class="post-account"><?= $post->Username; ?></p>
                            <p class="post-date">Posted on <?= display_time($post->PostCreated, "F j, Y"); ?></p>
                        </div>
                    </div>
                    <h3 class="post-title"><?= $post->Title; ?></h3>
                    <p class="post-content"><?= $post->Content; ?></p>
                    <div class="vote">
                        <div class="post-iconsp">
                                <i class="far fa-thumbs-up"></i>
                                <i class="far fa-thumbs-down"></i>
                            </div>
                        <div class="votes">N/A</div>
                    </div>
                    <!-- Comments Section -->
                    <div class="comments">
                        <div class="container">
                            <h4>Comments (<span class="comment-total"><?= is_array($comments) ? count($comments) : "0"; ?></span>)</h4>
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
                            <form id="add-comment" method="post">
                                <div class="comment-bar">
                                    <input type="text" id="commentInput" placeholder="Add a comment..." name="content"/>
                                    <button type="submit" value="Submit" id="addComment">Add</button>
                                </div>
                            </form>
                        <?php endif; ?>
                        <div class="sort-container">
                            <!-- Comments will get inserted here -->
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