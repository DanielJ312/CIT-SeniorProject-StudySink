<?php
# Forum Functions - Runs functions relating to the forum
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/mail-functions.php");

// Switch for deciding which function to run in AJAX
if (isset($_POST['function'])) {
    switch ($_POST['function']) {
        case "add":
            add_comment();
            break;
        case "delete":
            delete_comment();
            break;
        case "edit":
            edit_comment();
            break;
        case "report":
            report_comment();
            break;
        case "sort": 
            update_sort();
            break;
        case "update-vote": 
            update_vote();
            break;
        default:
            break;
    }
}

# Post Functions
function get_posts() {
    $query = "SELECT * FROM POST_T INNER JOIN USER_T ON POST_T.UserID = USER_T.UserID ORDER BY POST_T.Created ASC;";
    return run_database($query);
}

function create_post($data) {
    $query = "SELECT UniversityID FROM UNIVERSITY_T WHERE Name = '{$data['university']}';";
    $universityID = run_database($query)[0]->UniversityID;

    $query = "SELECT SubjectID FROM SUBJECT_T WHERE Name = '{$data['subject']}';";
    $subjectID = run_database($query)[0]->SubjectID ?? 0;

    $errors = array();

    if (empty($data['university'])) {
        $errors[] = "Please select a Unviersity from the dropdown to associate your post with.";
    }
    if (empty($data['title'])) {
        $errors[] = "Please enter a post title.";
    }
    if (empty($data['content'])) {
        $errors[] = "Please enter content for the post.";
    }

    if (count($errors) == 0) {
        $values = [
            'PostID' => rand(100, 99999),
            'UniversityID' => $universityID,
            'SubjectID' => $subjectID,
            'Title' => $data['title'],
            'Content' => $data['content'],
            'UserID' => $_SESSION['USER']->UserID,
            'Created' => get_local_time()
        ];

        $query = "INSERT INTO POST_T (PostID, UniversityID, SubjectID, Title, Content, UserID, Created) VALUES (:PostID, :UniversityID, :SubjectID, :Title, :Content, :UserID, :Created);";
        run_database($query, $values);
        header("Location: /forum/posts/{$values['PostID']}.php");
    }

    return $errors;
}

function get_post($postID) {
    $values['PostID'] = $postID;
    $query = <<<query
    SELECT PostID, Title, Content, POST_T.Created AS PostCreated, Username, Avatar, UNIVERSITY_T.Name AS UniversityName, SUBJECT_T.Name AS SubjectName
    FROM POST_T INNER JOIN USER_T ON POST_T.UserID = USER_T.UserID 
        INNER JOIN UNIVERSITY_T ON POST_T.UniversityID = UNIVERSITY_T.UniversityID
        INNER JOIN SUBJECT_T ON POST_T.SubjectID = SUBJECT_T.SubjectID
    WHERE PostID = :PostID;
    query;
    return run_database($query, $values)[0];
}

function get_comments($postID) {
    $values['PostID'] = $postID;
    $query = <<<query
    SELECT USER_T.UserID, Username, Avatar, COMMENT_T.CommentID, PostID, Content, COMMENT_T.Created AS CommentCreated, sum(VoteType) AS Votes
    FROM USER_T INNER JOIN COMMENT_T ON USER_T.UserID = COMMENT_T.UserID
        INNER JOIN CVOTE_T ON COMMENT_T.CommentID = CVOTE_T.CommentID
    WHERE PostID = :PostID
    GROUP BY CommentID
    ORDER BY COMMENT_T.Created ASC;
    query;
    return run_database($query, $values);
}

function get_comment($commentID) {
    $values['CommentID'] = $commentID;
    $query = <<<query
    SELECT USER_T.UserID, Username, Avatar, COMMENT_T.CommentID, PostID, Content, COMMENT_T.Created AS CommentCreated, sum(VoteType) AS Votes
    FROM USER_T INNER JOIN COMMENT_T ON USER_T.UserID = COMMENT_T.UserID
        INNER JOIN CVOTE_T ON COMMENT_T.CommentID = CVOTE_T.CommentID
    WHERE COMMENT_T.CommentID = :CommentID
    query;
    return run_database($query, $values)[0];
}

# Comment Functions
function add_comment() {
    $values = [
        'CommentID' => $tempID = rand(100, 999),
        'PostID' => $_POST['postID'],
        'Content' => $_POST['content'],
        'UserID' => $_SESSION['USER']->UserID,
        'Created' => get_local_time()
    ];

    $query = "INSERT INTO COMMENT_T (CommentID, PostID, Content, UserID, Created) VALUES (:CommentID, :PostID, :Content, :UserID, :Created)";
    run_database($query, $values);
    $query = "INSERT INTO CVOTE_T (CommentID, UserID, VoteType) VALUES ($tempID, {$_SESSION['USER']->UserID}, 1);";
    run_database($query);
    
    $query = <<<query
    SELECT USER_T.UserID, Username, Avatar, COMMENT_T.CommentID, PostID, Content, COMMENT_T.Created AS CommentCreated, sum(VoteType) AS Votes
    FROM USER_T INNER JOIN COMMENT_T ON USER_T.UserID = COMMENT_T.UserID
        INNER JOIN CVOTE_T ON COMMENT_T.CommentID = CVOTE_T.CommentID
    WHERE COMMENT_T.CommentID = {$values['CommentID']}
    query;
    $comment = run_database($query)[0];
    include($_SERVER['DOCUMENT_ROOT'] . "/forum/posts/c-template.php");
}

function delete_comment() {
    $values['CommentID'] = $_POST['commentID'];
    $query = "DELETE FROM COMMENT_T WHERE CommentID = :CommentID";
    run_database($query, $values);
}

function edit_comment() {
    $values = [
        'CommentID' => $_POST['commentID'],
        'Content' => $_POST['content'],
        // 'Created' => get_local_time()
    ];
    $query = "UPDATE COMMENT_T SET Content = :Content WHERE CommentID = :CommentID;";
    run_database($query, $values);
    echo $values['Content'];
}

function report_comment() {
    // $commentID = $_POST['commentID'];
    $comment = get_comment($_POST['commentID']);
    $commentDate = display_time($comment->CommentCreated, "F j, Y @ h:i:s A");
    $userReporting = $_SESSION['USER'];
    $caseID = rand(10000, 99999);
    $recipient = "StudySinkLLC@gmail.com";

    $subject = "Report Case ID: $caseID (Comment)";
    $message = <<<message
    <p>The following comment (<b>#{$comment->CommentID}</b>)  submitted by <b>{$comment->Username}</b> has been reported by <b>{$userReporting->Username}</b>. The comment was submitted on <b>{$commentDate}</b> and has a total of <b>{$comment->Votes}</b> likes.</p>
    <p style="padding-left: 40px;">{$comment->Content}</p>
    <p>Review the comment and take appropiate actions.</p>
    message;

    send_mail($recipient, $subject, $message);
}

# General Functions
function update_sort() {
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
            include($_SERVER['DOCUMENT_ROOT'] . "/forum/posts/c-template.php");
        }
    }
}

function create_sort_query($type, $postID) {
    if ($type[0] == "p") { 
        $query = "SELECT * FROM POST_T INNER JOIN USER_T ON POST_T.UserID = USER_T.UserID ";
    }
    else if ($type[0] == "c") {
        $query = <<<query
        SELECT USER_T.UserID, Username, Avatar, COMMENT_T.CommentID, PostID, Content, COMMENT_T.Created AS CommentCreated, sum(VoteType) AS Votes
        FROM USER_T INNER JOIN COMMENT_T ON USER_T.UserID = COMMENT_T.UserID
            INNER JOIN CVOTE_T ON COMMENT_T.CommentID = CVOTE_T.CommentID
        WHERE PostID = $postID
        GROUP BY CommentID 
        query;
    }

    switch ($type) {
        case 'post-oldest':
            $query .= "ORDER BY POST_T.Created ASC;";
            break;
        case 'post-newest':
            $query .= "ORDER BY POST_T.Created DESC;";
            break;
        case 'post-popular':
            $query .= "SELECT * FROM POST_T INNER JOIN USER_T ON POST_T.UserID = USER_T.UserID ORDER BY POST_T.Created DESC;";
            // NOT WORKING FOR THE TIME BEING, POST HAS NO VOTE FUNCTIONALITY
            break;
        case 'comment-oldest':
            $query .= "ORDER BY COMMENT_T.Created ASC;";
            break;
        case 'comment-newest':
            $query .= "ORDER BY COMMENT_T.Created DESC;";
            break;    
        case 'comment-popular':
            $query .= "ORDER BY Votes DESC, CommentCreated ASC;";
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
?>