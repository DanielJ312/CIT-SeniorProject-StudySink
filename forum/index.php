<?php 
$pageTitle = "Forum";
include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php");

$query = "SELECT * FROM post_t INNER JOIN user_t ON post_t.author = user_t.userid ORDER BY post_t.created ASC";
$post = run_database($query);

?>

<script async src="/forum/forum.js"></script>
<h2><?=isset($pageTitle) ? $pageTitle : "Page Header" ?></h2>
<div>
    <div>
        <p><a href="create.php">Create a new post</a></p>
    </div>
    <h3>Posts</h3>
    <div>
        <form action="" method="post">
            <select class="sort" name="sorts"  >
                <option value="oldest">Oldest</option>
                <option value="newest">Newest</option>
                <option value="popular">Popular</option>
            </select>
        </form>
        <!-- <button onclick="TestFunction('testing value')"></button> -->
    </div>
    <div class="forum-posts">
        <?php for ($i=0; $i < count($post); $i++): ?> 
            <a href="/forum/posts/<?=$post[$i]->postID; ?>.php">
                <p><?=$post[$i]->title?></p>
                <p>By: <?= $post[$i]->username ?></p>
            </a>
        <?php endfor; ?>
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>

<?php 

?>