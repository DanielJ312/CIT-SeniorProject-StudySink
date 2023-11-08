<?php
# Sort.php - Runs sorting functions for posts
require($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");

switch ($_POST['function']) {
    case "delete":
        # code...
        break;
    case "sort": 
        update_sort();
        break;
    case "update-vote": 
        update_vote();
        break;
    default:
        # code...
        break;
}

function update_sort() {
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

function update_vote() {
    // $action = $_POST['action'];
    $commentID = $_POST['commentID'];
    $userID = $_POST['userID'];
    $voteType = $_POST['voteType'];
    // echo $action, $commentID, $userID, $voteType;

    $query = <<<query
    INSERT INTO CVOTE_T (CommentID, UserID, VoteType)
    VALUES ($commentID, $userID, $voteType) 
    ON DUPLICATE KEY UPDATE
    VoteType = CASE
        WHEN VoteType = 1 THEN -1
        WHEN VoteType = -1 THEN 1
        ELSE VoteType
    END;
    query;
    run_database($query);

    $query = "SELECT sum(VoteType) AS VoteCount FROM CVOTE_T WHERE CommentID = $commentID";
    $voteTotal = run_database($query);
    $voteTotal = $voteTotal[0]->VoteCount;

    echo $voteTotal;
}

?>