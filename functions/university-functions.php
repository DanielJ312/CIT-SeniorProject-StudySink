<?php
# University Functions - Runs functions relating to the university page
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");

//////////*  AJAX Functions Switch *//////////
if (isset($_POST['function'])) {
    switch ($_POST['function']) {
        // Post Functions
        case "post-sort":
            update_post_sort(); 
            break;
    }
}

//////////* University Sort Functions *//////////
function update_post_sort() {
    $sortType = $_POST['sortType'];
    // $university = isset($_GET['url']) ? basename($_GET['url'], '.php') : 'default';
    // $university = "CSUN";
    $university = $_POST['university'];

    $query = create_post_sort($sortType, $university);
    $sorted = run_database($query);
    // echo $sorted[0]->Title;
    
    if (is_array($sorted) > 0) {
        foreach ($sorted as $post) {
            include($_SERVER['DOCUMENT_ROOT'] . "/university/p-template.php");
        }
    }
}

function create_post_sort($sortType, $university) {
    $query = <<<query
    SELECT POST_T.PostID, Title, POST_T.Content, POST_T.Created AS PostCreated, Username, Avatar, UNIVERSITY_T.UniversityID, UNIVERSITY_T.Name AS UniversityName, UNIVERSITY_T.Abbreviation, SUBJECT_T.Name AS SubjectName, COUNT(DISTINCT CommentID) AS Comments, COALESCE((SELECT COUNT(*) FROM POST_LIKE_T WHERE PostID = POST_T.PostID AND VoteType = 1), 0) AS Likes
    FROM POST_T 
        INNER JOIN USER_T ON POST_T.UserID = USER_T.UserID
        INNER JOIN UNIVERSITY_T ON POST_T.UniversityID = UNIVERSITY_T.UniversityID
        INNER JOIN SUBJECT_T ON POST_T.SubjectID = SUBJECT_T.SubjectID
        LEFT OUTER JOIN COMMENT_T ON COMMENT_T.PostID = POST_T.PostID
    WHERE UNIVERSITY_T.Abbreviation = '$university'
    GROUP BY POST_T.PostID 
    query;

    switch ($sortType) {
        case 'post-oldest':
            $query .= "ORDER BY POST_T.Created ASC;";
            break;
        case 'post-newest':
            $query .= "ORDER BY POST_T.Created DESC;";
            break;    
        case 'post-popular':
            $query .= "ORDER BY Likes DESC, POST_T.Created ASC;";
            break;  
    }
    return $query;
}