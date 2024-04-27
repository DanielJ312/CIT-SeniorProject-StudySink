<?php 
//////////* Search Functions - Contains functions used for searching *//////////
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");

function search_study_sets($searchTerm) {
    $studySetsQuery = "SELECT DISTINCT STUDY_SET_T.*, 
                        COURSE_T.Abbreviation AS CourseAbbreviation, 
                        SUBJECT_T.Name AS SubjectName, 
                        UNIVERSITY_T.Name AS UniversityName, 
                        UNIVERSITY_T.Abbreviation AS UniversityAbbreviation,
                        USER_T.Username AS Username,
                        USER_T.Avatar AS Avatar,
                        COUNT(DISTINCT CommentID) AS Comments,
                        COALESCE((SELECT AVG(Rating) FROM STUDY_SET_RATINGS WHERE StudySetID = STUDY_SET_T.StudySetID), 0) AS Rating,
                        (MATCH(STUDY_SET_T.Title, STUDY_SET_T.Description, STUDY_SET_T.Instructor) AGAINST(:searchTerm IN NATURAL LANGUAGE MODE)
                        + MATCH(COURSE_T.Name, COURSE_T.Abbreviation) AGAINST(:searchTerm IN BOOLEAN MODE)
                        + MATCH(SUBJECT_T.Name, SUBJECT_T.Abbreviation) AGAINST(:searchTerm IN BOOLEAN MODE)
                        + MATCH(UNIVERSITY_T.Name) AGAINST(:searchTerm IN BOOLEAN MODE)
                        + MATCH(STUDY_CARD_T.Front, STUDY_CARD_T.Back) AGAINST(:searchTerm IN NATURAL LANGUAGE MODE)) AS RelevanceScore
                        FROM STUDY_SET_T
                        INNER JOIN COURSE_T ON STUDY_SET_T.CourseID = COURSE_T.CourseID
                        INNER JOIN SUBJECT_T ON COURSE_T.SubjectID = SUBJECT_T.SubjectID
                        INNER JOIN UNIVERSITY_T ON SUBJECT_T.UniversityID = UNIVERSITY_T.UniversityID
                        LEFT JOIN STUDY_CARD_T ON STUDY_SET_T.StudySetID = STUDY_CARD_T.StudySetID
                        INNER JOIN USER_T ON STUDY_SET_T.UserID = USER_T.UserID
                        LEFT OUTER JOIN COMMENT_T ON COMMENT_T.StudySetID = STUDY_SET_T.StudySetID
                        WHERE MATCH(STUDY_SET_T.Title, STUDY_SET_T.Description, STUDY_SET_T.Instructor) AGAINST(:searchTerm IN NATURAL LANGUAGE MODE)
                        OR MATCH(COURSE_T.Name, COURSE_T.Abbreviation) AGAINST(:searchTerm IN BOOLEAN MODE)
                        OR MATCH(SUBJECT_T.Name, SUBJECT_T.Abbreviation) AGAINST(:searchTerm IN BOOLEAN MODE)
                        OR MATCH(UNIVERSITY_T.Name) AGAINST(:searchTerm IN BOOLEAN MODE)
                        OR UNIVERSITY_T.Abbreviation LIKE :searchTerm
                        OR MATCH(STUDY_CARD_T.Front, STUDY_CARD_T.Back) AGAINST(:searchTerm IN NATURAL LANGUAGE MODE)
                        OR USER_T.Username LIKE :searchTerm
                        GROUP BY STUDY_SET_T.StudySetID
                        ORDER BY RelevanceScore DESC";
    return run_database($studySetsQuery, ['searchTerm' => "%$searchTerm%"]);
}

function search_posts($searchTerm) {
    $postsQuery = "SELECT POST_T.*, USER_T.Username, USER_T.Avatar, 
                    UNIVERSITY_T.Name AS UniversityName, UNIVERSITY_T.Abbreviation AS UniversityAbbreviation, 
                    SUBJECT_T.Name AS SubjectName,
                    COUNT(DISTINCT CommentID) AS Comments, COALESCE((SELECT COUNT(*) FROM POST_LIKE_T WHERE PostID = POST_T.PostID AND VoteType = 1), 0) AS Likes
                    FROM POST_T
                    INNER JOIN USER_T ON POST_T.UserID = USER_T.UserID
                    LEFT JOIN UNIVERSITY_T ON POST_T.UniversityID = UNIVERSITY_T.UniversityID
                    LEFT JOIN SUBJECT_T ON POST_T.SubjectID = SUBJECT_T.SubjectID
                    LEFT OUTER JOIN COMMENT_T ON COMMENT_T.PostID = POST_T.PostID
                    WHERE POST_T.Title LIKE :searchTerm 
                    OR POST_T.Content LIKE :searchTerm
                    OR UNIVERSITY_T.Name LIKE :searchTerm
                    OR UNIVERSITY_T.Abbreviation LIKE :searchTerm
                    OR SUBJECT_T.Name LIKE :searchTerm
                    OR USER_T.Username LIKE :searchTerm
                    GROUP BY POST_T.PostID";
    return run_database($postsQuery, ['searchTerm' => "%$searchTerm%"]);
}

function search_users($searchTerm) {
    $usersQuery = "SELECT USER_T.Username, USER_T.Bio, USER_T.Avatar, USER_T.Created, UNIVERSITY_T.Name AS UniversityName, UNIVERSITY_T.Abbreviation AS UniversityAbbreviation
    FROM USER_T INNER JOIN UNIVERSITY_T ON USER_T.UniversityID = UNIVERSITY_T.UniversityID
    WHERE USER_T.Username LIKE :searchTerm
    GROUP BY USER_T.UserID";
    return run_database($usersQuery, ['searchTerm' => "%$searchTerm%"]);
}

function search_universities($searchTerm) {
    $universitiesQuery = "SELECT UNIVERSITY_T.Name AS UniversityName, UNIVERSITY_T.Abbreviation AS UniversityAbbreviation, UNIVERSITY_T.Logo AS UniversityLogo 
    FROM UNIVERSITY_T
    WHERE UNIVERSITY_T.Name LIKE :searchTerm
    OR UNIVERSITY_T.Abbreviation LIKE :searchTerm";
    return run_database($universitiesQuery, ['searchTerm' => "%$searchTerm%"]);
}

?>