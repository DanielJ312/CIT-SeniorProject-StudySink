<!-- Forum Home - Lists all posts made and allows users to sort -->
<?php 
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/forum-functions.php");
update_session();
$pageTitle = "Forum";

$post = get_posts();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
    <script async src="/posts/forum.js"></script>
</head>
<body>
    <header>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/to-top.php"); ?>
        <h2><?= isset($pageTitle) ? $pageTitle : "Page Header" ?></h2>
    </header>
    <main>
        <div>
            <p><a href="create.php">Create a new post</a></p>
        </div>
        <h3>Posts</h3>
        <form method="post">
            <select class="sort" name="sorts">
                <option value="post-oldest">Oldest</option>
                <option value="post-newest">Newest</option>
                <option value="post-popular">Popular</option>
                <?= "<script>var postID = 0;</script>"; ?>
            </select>
        </form>
        <div class="sort-container">
            <!-- Posts will get inserted here -->
        </div>
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>
</html>