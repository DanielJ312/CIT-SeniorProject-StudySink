<?php
require($_SERVER['DOCUMENT_ROOT'] . "/functions.php");

if (isset($_POST['sortType'])) {
    $type = null;
    $type = $_POST['sortType'];
    // echo $data['type'];

    switch ($type) {
        case 'newest':
            $query = "SELECT * FROM post_t INNER JOIN user_t ON post_t.author = user_t.userid ORDER BY post_t.created DESC";
            break;
        default:
            $query = "SELECT * FROM post_t INNER JOIN user_t ON post_t.author = user_t.userid ORDER BY post_t.created ASC";
            break;
    }

    $post = run_database($query);

    for ($i=0; $i < count($post); $i++) {
        $currentPost = <<<currentPost
        <a href="/forum/posts/{$post[$i]->postID}.php">
            <p>{$post[$i]->title}</p>
            <p>By: {$post[$i]->username}</p>
        </a>
        currentPost;
        echo $currentPost;
    }
}

?>