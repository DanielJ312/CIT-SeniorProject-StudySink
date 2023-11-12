<!-- Forum Home - Lists all posts made and allows users to sort -->
<?php 
require($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
$pageTitle = "Forum";

$query = "SELECT * FROM POST_T INNER JOIN USER_T ON POST_T.UserID = USER_T.UserID ORDER BY POST_T.Created ASC;";
$post = run_database($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
    <script async src="/forum/forum.js"></script>
</head>
<body>
    <header>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
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
        <!-- <button onclick="TestFunction('testing value')"></button> -->
        <div class="sort-container">
        <?php for ($i=0; $i < count($post); $i++): ?> 
            <a href="/forum/posts/<?=$post[$i]->PostID; ?>.php">
                <p><?=$post[$i]->Title?></p>
                <p>By: <?= $post[$i]->Username ?></p>
            </a>
        <?php endfor; ?>
        </div>
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>
</html>