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
        // Study Set Functions
        case "study-set-sort":
            update_set_sort(); 
            break;
}
}

//////////* University Sort Functions *//////////
function update_post_sort() {
    $sortType = $_POST['sortType'];
    $universityID = $_POST['universityID'];
    $subjectID = $_POST['subjectID'];

    $query = create_post_sort($sortType, $universityID, $subjectID);
    $sorted = run_database($query);
    
    if (is_array($sorted) > 0) {
        foreach ($sorted as $post) {
            include($_SERVER['DOCUMENT_ROOT'] . "/university/p-template.php");
        }
    }
    else {
        echo '<h3>No posts have been made yet.</h3>';
    }
}

function create_post_sort($sortType, $universityID, $subjectID) {
    $query = <<<query
    SELECT POST_T.PostID, Title, POST_T.Content, POST_T.Created AS PostCreated,  Username, Avatar, UNIVERSITY_T.UniversityID, UNIVERSITY_T.Name AS UniversityName, UNIVERSITY_T.Abbreviation, SUBJECT_T.Name AS SubjectName, COUNT(DISTINCT CommentID) AS Comments, COALESCE((SELECT COUNT(*) FROM POST_LIKE_T WHERE PostID = POST_T.PostID AND VoteType = 1), 0) AS Likes
    FROM POST_T 
        INNER JOIN USER_T ON POST_T.UserID = USER_T.UserID
        INNER JOIN UNIVERSITY_T ON POST_T.UniversityID = UNIVERSITY_T.UniversityID
        INNER JOIN SUBJECT_T ON POST_T.SubjectID = SUBJECT_T.SubjectID
        LEFT OUTER JOIN COMMENT_T ON COMMENT_T.PostID = POST_T.PostID
    WHERE UNIVERSITY_T.UniversityID = '$universityID' 
    query;

    if ($subjectID != 0) {
        $query .= " AND POST_T.SubjectID = '$subjectID' ";
    }
    $query .= " GROUP BY POST_T.PostID ";

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

function update_set_sort() {
    $sortType = $_POST['sortType'];
    $universityID = $_POST['universityID'];
    $subjectID = $_POST['subjectID'];

    $query = create_set_sort($sortType, $universityID, $subjectID);
    $sorted = run_database($query);
    
    if (is_array($sorted) > 0) {
        foreach ($sorted as $set) {
            include($_SERVER['DOCUMENT_ROOT'] . "/university/s-template.php");
        }
    }
    else {
        echo '<h3>No study sets have been made yet.</h3>';
    }
}

function create_set_sort($sortType, $universityID, $subjectID) {
    $query = <<<query
    SELECT STUDY_SET_T.StudySetID, Title, Description, STUDY_SET_T.Created AS SetCreated, Username, Avatar, UNIVERSITY_T.Name AS UniversityName, COURSE_T.Abbreviation AS Course, COUNT(DISTINCT CommentID) AS Comments,
    COALESCE((SELECT AVG(Rating) FROM STUDY_SET_RATINGS WHERE StudySetID = STUDY_SET_T.StudySetID), 0) AS Rating
    FROM STUDY_SET_T INNER JOIN USER_T ON STUDY_SET_T.UserID = USER_T.UserID
        INNER JOIN COURSE_T ON COURSE_T.CourseID = STUDY_SET_T.CourseID
        INNER JOIN SUBJECT_T ON SUBJECT_T.SubjectID = COURSE_T.SubjectID
        INNER JOIN UNIVERSITY_T ON UNIVERSITY_T.UniversityID = SUBJECT_T.UniversityID
        LEFT OUTER JOIN COMMENT_T ON COMMENT_T.StudySetID = STUDY_SET_T.StudySetID
    WHERE SUBJECT_T.SubjectID = '$subjectID' 
    GROUP BY STUDY_SET_T.StudySetID 
    query;

    switch ($sortType) {
        case 'study-set-oldest':
            $query .= "ORDER BY STUDY_SET_T.Created ASC;";
            break;
        case 'study-set-newest':
            $query .= "ORDER BY STUDY_SET_T.Created DESC;";
            break;    
        case 'study-set-popular':
            $query .= "ORDER BY Rating DESC, STUDY_SET_T.Created ASC;";
            break;  
    }
    return $query;
}