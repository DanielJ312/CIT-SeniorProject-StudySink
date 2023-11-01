<?php
# Sort.php - Runs sorting functions for posts
require($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");

if (isset($_POST['sortType'])) {
    $type = null;
    $type = $_POST['sortType'];

    switch ($type) {
        case 'newest':
            $query = "SELECT * FROM POST_T INNER JOIN USER_T ON POST_T.UserID = USER_T.UserID ORDER BY POST_T.Created DESC;";
            break;
        default:
            $query = "SELECT * FROM POST_T INNER JOIN USER_T ON POST_T.UserID = USER_T.UserID ORDER BY POST_T.Created ASC;";
            break;
    }

    $post = run_database($query);

    for ($i=0; $i < count($post); $i++) {
        $currentPost = <<<currentPost
        <a href="/forum/posts/{$post[$i]->PostID}.php">
            <p>{$post[$i]->Title}</p>
            <p>By: {$post[$i]->Username}</p>
        </a>
        currentPost;
        echo $currentPost;
    }
}
?>