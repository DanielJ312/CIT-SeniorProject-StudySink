<?php
# Forum Functions - Runs functions relating to the forum
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/mail-functions.php");

//////////*  AJAX Functions Switch *//////////
if (isset($_POST['function'])) {
    switch ($_POST['function']) {
        // Post Functions
        case "post-delete":
            delete_post(); 
            break;
        case "post-edit":
            edit_post(); 
            break;
        case "post-report":
            report_post();
            break;
        case "post-like": 
            update_post_like();
            break;
        // Comment Functions
        case "comment-add":
            add_comment();
            break;
        case "comment-delete":
            delete_comment();
            break;
        case "comment-edit":
            edit_comment();
            break;
        case "comment-report":
            report_comment();
            break;
        case "comment-sort": 
            update_comment_sort();
            break;
        case "comment-like": 
            update_comment_like();
            break;
        default:
            break;
    }
}

//////////* Get Functions *//////////
function get_posts() {
    $query = "SELECT * FROM POST_T INNER JOIN USER_T ON POST_T.UserID = USER_T.UserID ORDER BY POST_T.Created ASC;";
    return run_database($query);
}

function get_post($postID) {
    $values['PostID'] = $postID;
    $query = <<<query
    SELECT POST_T.PostID, Title, POST_T.Content, POST_T.Created AS PostCreated, POST_T.Modified AS PostModified, USER_T.UserID, Username, Avatar, UNIVERSITY_T.UniversityID, UNIVERSITY_T.Name AS UniversityName, UNIVERSITY_T.Abbreviation, SUBJECT_T.Name AS SubjectName, COUNT(DISTINCT CommentID) AS Comments, COALESCE((SELECT COUNT(*) FROM POST_LIKE_T WHERE PostID = POST_T.PostID AND VoteType = 1), 0) AS Likes
    FROM POST_T 
        INNER JOIN USER_T ON POST_T.UserID = USER_T.UserID
        INNER JOIN UNIVERSITY_T ON POST_T.UniversityID = UNIVERSITY_T.UniversityID
        INNER JOIN SUBJECT_T ON POST_T.SubjectID = SUBJECT_T.SubjectID
        LEFT OUTER JOIN COMMENT_T ON COMMENT_T.PostID = POST_T.PostID
    WHERE POST_T.PostID = :PostID
    GROUP BY POST_T.PostID
    query;
    $post = run_database($query, $values);
    if (is_array($post)) {
        return $post[0];
    }
}

function get_comments($postID) {
    $values['PostID'] = $postID;
    $query = <<<query
    SELECT PostID, Content, COMMENT_T.Created AS CommentCreated, sum(VoteType) AS Votes, USER_T.UserID, Username, Avatar, COMMENT_T.CommentID
    FROM USER_T INNER JOIN COMMENT_T ON USER_T.UserID = COMMENT_T.UserID
        INNER JOIN COMMENT_LIKE_T ON COMMENT_T.CommentID = COMMENT_LIKE_T.CommentID
    WHERE PostID = :PostID
    GROUP BY CommentID
    ORDER BY COMMENT_T.Created ASC;
    query;
    return run_database($query, $values);
}

function count_comments($parentID) {
    if ($parentID[0] == 7) {
        $values['PostID'] = $parentID;
        $query = "SELECT COUNT(CommentID) AS Count FROM COMMENT_T WHERE PostID = :PostID;";
    }
    else if ($parentID[0] == 8) {
        $values['StudySetID'] = $parentID;
        $query = "SELECT COUNT(CommentID) AS Count FROM COMMENT_T WHERE StudySetID = :StudySetID;";
    }
    return run_database($query, $values)[0]->Count;
}

function get_comment($commentID) {
    $values['CommentID'] = $commentID;
    $query = <<<query
    SELECT PostID, Content, COMMENT_T.Created AS CommentCreated, sum(VoteType) AS Votes, USER_T.UserID, Username, Avatar, COMMENT_T.CommentID
    FROM USER_T INNER JOIN COMMENT_T ON USER_T.UserID = COMMENT_T.UserID
        INNER JOIN COMMENT_LIKE_T ON COMMENT_T.CommentID = COMMENT_LIKE_T.CommentID
    WHERE COMMENT_T.CommentID = :CommentID
    query;
    return run_database($query, $values)[0];
}

//////////* Post Functions *//////////
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
            'PostID' => generate_ID("POST"),
            'UniversityID' => $universityID,
            'SubjectID' => $subjectID,
            'Title' => $data['title'],
            'Content' => $data['content'],
            'UserID' => $_SESSION['USER']->UserID,
            'Created' => time()
        ];

        $query = "INSERT INTO POST_T (PostID, UniversityID, SubjectID, Title, Content, UserID, Created) VALUES (:PostID, :UniversityID, :SubjectID, :Title, :Content, :UserID, :Created);";
        run_database($query, $values);
        header("Location: /forum/posts/{$values['PostID']}.php");
    }

    return $errors;
}

function check_user_pvote($postID) {
    $query = "SELECT VoteType FROM POST_LIKE_T WHERE PostID = $postID AND UserID = {$_SESSION['USER']->UserID};";
    $result = run_database($query);
    if (is_array($result) && !$result[0]->VoteType == 0) {
        return $result[0]->VoteType;
    }
}

function delete_post() {
    $values['PostID'] = $_POST['postID'];
    $query = "DELETE FROM POST_T WHERE PostID = :PostID";
    run_database($query, $values);
    echo isset($_SESSION['USER']->Abbreviation) ? $_SESSION['USER']->Abbreviation : "none";
}

function edit_post() {
    $values = [
        'PostID' => $_POST['postID'],
        'Content' => $_POST['content'],
        'Modified' => time()
    ];
    $query = "UPDATE POST_T SET Content = :Content, Modified = :Modified WHERE PostID = :PostID;";
    run_database($query, $values);
    echo nl2br($values['Content']);
}

function report_post() {
    $post = get_post($_POST['postID']);
    echo "<script>console.log('Reached')</script>";
    $postDate = date("F j, Y @ h:i:s A", $post->PostCreated);
    $userReporting = $_SESSION['USER'];
    $caseID = rand(10000, 99999);
    $recipient = "StudySinkLLC@gmail.com";

    $subject = "Report Case ID: $caseID (Post)";
    $message = <<<message
    <p>The following post (<b>#{$post->PostID}</b>) submitted by <b>{$post->Username}</b> has been reported by <b>{$userReporting->Username}</b>. The post was submitted on <b>{$postDate}</b> and has a total of <b>{$post->Likes}</b> likes.</p>
    <p style="padding-left: 40px;">{$post->Content}</p>
    <p>Review the post and take appropiate actions.</p>
    message;

    send_mail($recipient, $subject, $message);
}

function update_post_like() {
    $postID = $_POST['postID'];
    $userID = $_SESSION['USER']->UserID;
    $voteType = 1;

    $query = <<<query
    INSERT INTO POST_LIKE_T (PostID, UserID, VoteType)
    VALUES ($postID, $userID, $voteType) 
    ON DUPLICATE KEY UPDATE
    VoteType = CASE
        WHEN VoteType = 1 THEN 0
        WHEN VoteType = 0 THEN 1
        ELSE VoteType
    END;
    query;
    run_database($query);

    $query = "SELECT sum(VoteType) AS VoteCount FROM POST_LIKE_T WHERE PostID = $postID";
    $voteTotal = run_database($query);
    $voteTotal = $voteTotal[0]->VoteCount;
    echo $voteTotal;
}

function get_likes($postID) {
    $query = "SELECT sum(VoteType) as Likes FROM POST_LIKE_T WHERE PostID = $postID;";
    return run_database($query)[0]->Likes;
}

//////////*  Comment Functionms *//////////
function add_comment() {
    $parentID = $_POST['parentID'];
    $values = [
        'CommentID' => $tempID = generate_ID("COMMENT"),
        'Content' => $_POST['content'],
        'UserID' => $_SESSION['USER']->UserID,
        'Created' => time()
    ];

    if ($parentID[0] == 7) {
        $values['PostID'] = $parentID;
        $query = "INSERT INTO COMMENT_T (CommentID, PostID, Content, UserID, Created) VALUES (:CommentID, :PostID, :Content, :UserID, :Created)";
    }
    else if ($parentID[0] == 8) {
        $values['StudySetID'] = $parentID;
        $query = "INSERT INTO COMMENT_T (CommentID, StudySetID, Content, UserID, Created) VALUES (:CommentID, :StudySetID, :Content, :UserID, :Created)";
    }
    run_database($query, $values);
    $query = "INSERT INTO COMMENT_LIKE_T (CommentID, UserID, VoteType) VALUES ($tempID, {$_SESSION['USER']->UserID}, 1);";
    run_database($query);
    
    $query = <<<query
    SELECT USER_T.UserID, Username, Avatar, COMMENT_T.CommentID, PostID, Content, COMMENT_T.Created AS CommentCreated, sum(VoteType) AS Votes
    FROM USER_T INNER JOIN COMMENT_T ON USER_T.UserID = COMMENT_T.UserID
        INNER JOIN COMMENT_LIKE_T ON COMMENT_T.CommentID = COMMENT_LIKE_T.CommentID
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
        'Modified' => time()
    ];
    $query = "UPDATE COMMENT_T SET Content = :Content, Modified = :Modified WHERE CommentID = :CommentID;";
    run_database($query, $values);
    echo nl2br($values['Content']);
}

function report_comment() {
    $comment = get_comment($_POST['commentID']);
    echo "<script>console.log('Reached')</script>";
    $commentDate = date("F j, Y @ h:i:s A", $comment->CommentCreated);
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

function update_comment_sort() {
    $sortType = $_POST['sortType'];
    $parentID = $_POST['parentID'];

    $query = create_comment_sort($sortType, $parentID);
    $sorted = run_database($query);
    
    if (is_array($sorted) > 0) {
        foreach ($sorted as $comment) {
            include($_SERVER['DOCUMENT_ROOT'] . "/forum/posts/c-template.php");
        }
    }
}

function create_comment_sort($sortType, $parentID) {
    if ($parentID[0] == 7) {
        $query = <<<query
        SELECT USER_T.UserID, Username, Avatar, COMMENT_T.CommentID, PostID, Content, COMMENT_T.Created AS CommentCreated, Modified, sum(VoteType) AS Votes
        FROM USER_T INNER JOIN COMMENT_T ON USER_T.UserID = COMMENT_T.UserID
            INNER JOIN COMMENT_LIKE_T ON COMMENT_T.CommentID = COMMENT_LIKE_T.CommentID
        WHERE PostID = $parentID
        GROUP BY CommentID 
        query;
    }
    else if ($parentID[0] == 8) {
        $query = <<<query
        SELECT USER_T.UserID, Username, Avatar, COMMENT_T.CommentID, PostID, Content, COMMENT_T.Created AS CommentCreated, Modified, sum(VoteType) AS Votes
        FROM USER_T INNER JOIN COMMENT_T ON USER_T.UserID = COMMENT_T.UserID
            INNER JOIN COMMENT_LIKE_T ON COMMENT_T.CommentID = COMMENT_LIKE_T.CommentID
        WHERE StudySetID = $parentID
        GROUP BY CommentID 
        query;
    }

    switch ($sortType) {
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

function check_user_cvote($commentID) {
    $query = "SELECT VoteType FROM COMMENT_LIKE_T WHERE CommentID = $commentID AND UserID = {$_SESSION['USER']->UserID};";
    $result = run_database($query);
    if (is_array($result) && !$result[0]->VoteType == 0) {
        return $result[0]->VoteType;
    }
}

function update_comment_like() {
    $commentID = $_POST['commentID'];
    $userID = $_SESSION['USER']->UserID;
    $voteType = 1;

    $query = <<<query
    INSERT INTO COMMENT_LIKE_T (CommentID, UserID, VoteType)
    VALUES ($commentID, $userID, $voteType) 
    ON DUPLICATE KEY UPDATE
    VoteType = CASE
        WHEN VoteType = 1 THEN 0
        WHEN VoteType = 0 THEN 1
        ELSE VoteType
    END;
    query;
    run_database($query);

    $query = "SELECT sum(VoteType) AS VoteCount FROM COMMENT_LIKE_T WHERE CommentID = $commentID";
    $voteTotal = run_database($query);
    $voteTotal = $voteTotal[0]->VoteCount;
    echo $voteTotal;
}

?>