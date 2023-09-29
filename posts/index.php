<?php 
$pageTitle = "Forum Testing";
include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php");

$query = "SELECT * FROM post_t INNER JOIN user_t ON post_t.author = user_t.userid ORDER BY post_t.created ASC";
$post = run_database($query);

// copy("1.php","2.php");
// $file = file_get_contents("1.php");
// $newfile = str_replace('$postID = 1', '$postID = 747', $file);
// $file = fopen("2.php", "w");
// fwrite($file, $newfile);

// fix logged out forum

?>

<h2><?=isset($pageTitle) ? $pageTitle : "Page Header" ?></h2>
<div>
    <div>
        <p><a href="create-post.php">Create a new post</a></p>
    </div>
    <h3>Posts</h3>
    <div>
        <?php for ($i=0; $i < count($post); $i++): ?> 
            <a href="/posts/<?=$post[$i]->postID; ?>.php">
                <p><?=$post[$i]->title?></p>
                <p>By: <?= $post[$i]->username ?></p>
            </a>
        <?php endfor;?>
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>