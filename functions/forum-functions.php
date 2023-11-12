<?php
# Forum Functions - Runs functions relating to the forum
require($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");

// Switch for deciding which function to run in AJAX
if (isset($_POST['function'])) {
    switch ($_POST['function']) {
        case "delete":
            delete_comment();
            break;
        case "sort": 
            update_post_sort();
            break;
        case "update-vote": 
            update_vote();
            break;
        default:
            break;
    }
}

function update_post_sort() {
    $type = $_POST['sortType'];
    $postID = $_POST['postID'];

    $query = create_sort_query($type, $postID);
    $sorted = run_database($query);

    $type = $type[0];
    if ($type == "p") {
        foreach ($sorted as $post) {
            $currentPost = <<<currentPost
            <a href="/forum/posts/{$post->PostID}.php">
                <p>{$post->Title}</p>
                <p>By: {$post->Username}</p>
            </a>
            currentPost;
            echo $currentPost;
        }
    }
    
    if ($type == "c" && is_array($sorted) > 0) {
        $postUsername = run_database("SELECT Username FROM USER_T INNER JOIN POST_T ON USER_T.UserID = POST_T.UserID WHERE POST_T.PostID = $postID")[0]->Username;
        foreach ($sorted as $comment) {
            include "comment-template.php";
        }
    }
}

function create_sort_query($type, $postID) {
    switch ($type) {
        case 'post-oldest':
            $query = "SELECT * FROM POST_T INNER JOIN USER_T ON POST_T.UserID = USER_T.UserID ORDER BY POST_T.Created ASC;";
            break;
        case 'post-newest':
            $query = "SELECT * FROM POST_T INNER JOIN USER_T ON POST_T.UserID = USER_T.UserID ORDER BY POST_T.Created DESC;";
            break;
        case 'post-popular':
            $query = "SELECT * FROM POST_T INNER JOIN USER_T ON POST_T.UserID = USER_T.UserID ORDER BY POST_T.Created DESC;";
            // NOT WORKING FOR THE TIME BEING, POST HAS NO VOTE FUNCTIONALITY
            break;
        case 'comment-oldest':
            $query = <<<query
            SELECT USER_T.UserID, Username, Avatar, COMMENT_T.CommentID, PostID, Content, COMMENT_T.Created AS CommentCreated, sum(VoteType) AS Votes
            FROM USER_T INNER JOIN COMMENT_T ON USER_T.UserID = COMMENT_T.UserID
                INNER JOIN CVOTE_T ON COMMENT_T.CommentID = CVOTE_T.CommentID
            WHERE PostID = $postID
            GROUP BY CommentID
            ORDER BY COMMENT_T.Created ASC;
            query;
            break;
        case 'comment-newest':
            $query = <<<query
            SELECT USER_T.UserID, Username, Avatar, COMMENT_T.CommentID, PostID, Content, COMMENT_T.Created AS CommentCreated, sum(VoteType) AS Votes
            FROM USER_T INNER JOIN COMMENT_T ON USER_T.UserID = COMMENT_T.UserID
                INNER JOIN CVOTE_T ON COMMENT_T.CommentID = CVOTE_T.CommentID
            WHERE PostID = $postID
            GROUP BY CommentID
            ORDER BY COMMENT_T.Created DESC;
            query;
            break;    
        case 'comment-popular':
            $query = <<<query
            SELECT USER_T.UserID, Username, Avatar, COMMENT_T.CommentID, PostID, Content, COMMENT_T.Created AS CommentCreated, sum(VoteType) AS Votes
            FROM USER_T INNER JOIN COMMENT_T ON USER_T.UserID = COMMENT_T.UserID
                INNER JOIN CVOTE_T ON COMMENT_T.CommentID = CVOTE_T.CommentID
            WHERE PostID = $postID
            GROUP BY CommentID
            ORDER BY Votes DESC;
            query;
            break;  
    }
    return $query;
}

function update_vote() {
    $commentID = $_POST['commentID'];
    $userID = $_POST['userID'];
    $voteType = $_POST['voteType'];

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

function add_comment($data, $postID) {
    $errors = array();

    if(empty($data['content'])) {
        $errors[] = "Please enter content for the comment.";
    }

    if (count($errors) == 0) {
        $values['CommentID'] = $tempID = rand(100, 999);
        $values['PostID'] = $postID;
        $values['Content'] = $data['content'];
        $values['UserID'] = $_SESSION['USER']->UserID;
        $values['Created'] = get_local_time();

        $query = "INSERT INTO COMMENT_T (CommentID, PostID, Content, UserID, Created) VALUES (:CommentID, :PostID, :Content, :UserID, :Created)";
        run_database($query, $values);
        $query = "INSERT INTO CVOTE_T (CommentID, UserID, VoteType) VALUES ($tempID, {$_SESSION['USER']->UserID}, 1);";
        run_database($query);

        header("Location: $postID.php");
    }

    return $errors;
}

function delete_comment() {
    $values['CommentID'] = $_POST['commentID'];
    $query = "DELETE FROM COMMENT_T WHERE CommentID = :CommentID";
    run_database($query, $values);
}
?>