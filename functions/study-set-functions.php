<?php
//////////* Study Set Functions - Contains functions for study set pages *//////////
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");

function create_study_set($data) {
    if (!check_login()) {
        die("User must be logged in to create a Study Set.");
    }

    $title = $data['setTitle'] ?? null;
    $description = $data['setDescription'] ?? null;

    $universityID = $data['setUniversity'] ?? null; 
    $subjectID = $data['setSubject'] ?? null; 
    $courseID = $data['setCourse'] ?? null;
    $instructor = $data['instructor'] ?? null;
    $userID = $_SESSION['USER']->UserID; 
    $time = time();

    $errors = [];
    if (empty($title)) $errors[] = "Title is required.";
    if (empty($courseID)) $errors[] = "Course is required.";

    if (!empty($errors)) {
        display_errors($errors);
        exit;
    }

    try {
        $pdo = get_pdo_connection();
        $studySetID = generate_ID('STUDY_SET');

        $stmt = $pdo->prepare("INSERT INTO STUDY_SET_T (StudySetID, UserID, CourseID, Title, Description, Instructor, Created)
                               VALUES (?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([$studySetID, $userID, $courseID, $title, $description, $instructor, $time]);

        if (isset($data['cards'])) {
            $cards = json_decode($data['cards'], true);
            foreach ($cards as $card) {
                $cardStmt = $pdo->prepare("INSERT INTO STUDY_CARD_T (StudySetID, Front, Back) VALUES (?, ?, ?)");
                $cardStmt->execute([$studySetID, $card['front'], $card['back']]);
            }
        }

        // Automatically rate the study set 5 stars after creation
        addOrUpdateRating($pdo, $studySetID, $userID, 5);

        header("Location: /study-sets/{$studySetID}");
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }
}

function edit_study_set($setID, $data) {
    global $pdo;
    $pdo = get_pdo_connection(); 

    try {
        $pdo->beginTransaction();

        // Update study set details
        $stmt = $pdo->prepare("UPDATE STUDY_SET_T 
            SET CourseID = ?, Title = ?, Description = ?, Instructor = ?, Modified = ? 
            WHERE StudySetID = ?");
        $stmt->execute([
            $data['course_id'], 
            $data['setTitle'], 
            $data['setDescription'], 
            $data['instructor'], 
            time(),
            $setID
        ]);

        // Handle cards
        if (isset($data['cards'])) {
            $cards = json_decode($data['cards'], true);
            
            foreach ($cards as $card) {
                if (!empty($card['deleted']) && $card['deleted'] === true) {
                    // Delete the card
                    $deleteStmt = $pdo->prepare("DELETE FROM STUDY_CARD_T WHERE CardID = ?");
                    $deleteStmt->execute([$card['id']]);
                } elseif (!empty($card['edited'])) {
                    // Update the card
                    $updateStmt = $pdo->prepare("UPDATE STUDY_CARD_T SET Front = ?, Back = ? WHERE CardID = ?");
                    $updateStmt->execute([
                        $card['front'], 
                        $card['back'], 
                        $card['id']
                    ]);
                } elseif (!empty($card['newCard'])) {
                    // Insert a new card
                    $insertStmt = $pdo->prepare("INSERT INTO STUDY_CARD_T (StudySetID, Front, Back) VALUES (?, ?, ?)");
                    $insertStmt->execute([
                        $setID, 
                        $card['front'], 
                        $card['back']
                    ]);
                }
            }
        }

        // Commit the transaction
        $pdo->commit();

        header("Location: /study-sets/{$setID}");
    } catch (PDOException $e) {
        // Rollback the transaction in case of error
        $pdo->rollBack();
        echo "Database error: " . $e->getMessage();
    }
}

function delete_study_set($setID) {
    // Ensure the user is logged in
    if (!check_login()) {
        // Handle not logged in case, redirect to login page
        header("Location: /login.php");
        exit;
    }

    $pdo = get_pdo_connection(); 

    // Check if the current user owns the study set
    $checkOwnershipQuery = "SELECT UserID FROM STUDY_SET_T WHERE StudySetID = :StudySetID";
    $stmt = $pdo->prepare($checkOwnershipQuery);
    $stmt->execute([':StudySetID' => $setID]);
    $studySet = $stmt->fetch();

    if (!$studySet || $studySet['UserID'] != $_SESSION['USER']->UserID) {
        // User does not own the study set or study set does not exist
        // Handle this case, redirect to an error page or display a message
        university_redirect();
        exit;
    }

    // Start a transaction
    $pdo->beginTransaction();

    try {
        // 1) delete all comments associated with the study set
        $deleteCommentsQuery = "DELETE FROM COMMENT_T WHERE StudySetID = :StudySetID";
        $pdo->prepare($deleteCommentsQuery)->execute([':StudySetID' => $setID]);

        // 2) Delete all ratings associated with the study set
        $deleteRatingsQuery = "DELETE FROM STUDY_SET_RATINGS WHERE StudySetID = :StudySetID";
        $pdo->prepare($deleteRatingsQuery)->execute([':StudySetID' => $setID]);

        // 3) Delete all study cards associated with the study set
        $deleteCardsQuery = "DELETE FROM STUDY_CARD_T WHERE StudySetID = :StudySetID";
        $pdo->prepare($deleteCardsQuery)->execute([':StudySetID' => $setID]);

        // 4) Delete the study set itself
        $deleteSetQuery = "DELETE FROM STUDY_SET_T WHERE StudySetID = :StudySetID";
        $pdo->prepare($deleteSetQuery)->execute([':StudySetID' => $setID]);

        // Commit the transaction
        $pdo->commit();

        // Redirect or inform the user of successful deletion
        university_redirect();

        exit;
    } catch (PDOException $e) {
        // Roll back the transaction in case of an error
        $pdo->rollBack();

        // Log and handle the error
        error_log("Database error: " . $e->getMessage());
        // Redirect or display a user-friendly error message
        university_redirect();
        exit;
    }
}

function get_study_set($StudySetID) {
    $values['StudySetID'] = $StudySetID;
    $query = <<<query
    SELECT STUDY_SET_T.StudySetID, Title, Description, STUDY_SET_T.Created AS SetCreated, Username, Avatar, UNIVERSITY_T.Name AS UniversityName, COURSE_T.Abbreviation AS Course, COUNT(DISTINCT CommentID) AS Comments,
    COALESCE((SELECT AVG(Rating) FROM STUDY_SET_RATINGS WHERE StudySetID = STUDY_SET_T.StudySetID), 0) AS Rating
    FROM STUDY_SET_T INNER JOIN USER_T ON STUDY_SET_T.UserID = USER_T.UserID
        INNER JOIN COURSE_T ON COURSE_T.CourseID = STUDY_SET_T.CourseID
        INNER JOIN SUBJECT_T ON SUBJECT_T.SubjectID = COURSE_T.SubjectID
        INNER JOIN UNIVERSITY_T ON UNIVERSITY_T.UniversityID = SUBJECT_T.UniversityID
        LEFT OUTER JOIN COMMENT_T ON COMMENT_T.StudySetID = STUDY_SET_T.StudySetID
    WHERE STUDY_SET_T.StudySetID = :StudySetID 
    GROUP BY STUDY_SET_T.StudySetID 
    query;
    $studySet = run_database($query, $values);
    if (is_array($studySet)) {
        return $studySet[0];
    }
}

function addOrUpdateRating($pdo, $studySetID, $userID, $rating) {
    $stmt = $pdo->prepare("SELECT RatingID FROM STUDY_SET_RATINGS WHERE StudySetID = ? AND UserID = ?");
    $stmt->execute([$studySetID, $userID]);
    $existingRating = $stmt->fetch();

    if ($existingRating) {
        // Since RatedOn field is removed, no need to update it
        $updateStmt = $pdo->prepare("UPDATE STUDY_SET_RATINGS SET Rating = ? WHERE RatingID = ?");
        $updateStmt->execute([$rating, $existingRating['RatingID']]);
    } else {
        // Remove RatedOn from INSERT statement as well
        $insertStmt = $pdo->prepare("INSERT INTO STUDY_SET_RATINGS (StudySetID, UserID, Rating) VALUES (?, ?, ?)");
        $insertStmt->execute([$studySetID, $userID, $rating]);
    }
}


function getAverageRating($pdo, $studySetID) {
    $stmt = $pdo->prepare("SELECT AVG(Rating) as AverageRating FROM STUDY_SET_RATINGS WHERE StudySetID = :StudySetID");
    $stmt->execute([':StudySetID' => $studySetID]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result ? round($result['AverageRating'], 1) : null; // Round to one decimal place
}

?>