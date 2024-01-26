<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");

function create_study_set($data) {
    // Check if the user is logged in
    if (!check_login()) {
        // Redirect to login page or display an error
        die("User must be logged in to create a Study Set.");
    }

    // Assume the data from the form is in $_POST
    $title = $data['setTitle'] ?? null;
    $description = $data['setDescription'] ?? null;
    $universityID = $data['universityId'] ?? null;
    $subjectID = $data['subjectId'] ?? null;
    $courseID = $data['course_id'] ?? null;
    $instructor = $data['instructor'] ?? null;
    $userID = $_SESSION['USER']->UserID; // get the UserID from the session

    // Validate the input data
    $errors = [];
    if (empty($title)) $errors[] = "Title is required.";
    if (empty($courseID)) $errors[] = "Course is required.";
    // ...additional validations as needed...

    // Display errors if there are any
    if (!empty($errors)) {
        display_errors($errors);
        exit;
    }

    // No errors, proceed with saving the Study Set
    try {
        // Generate a new StudySetID
        $studySetID = generate_ID('STUDY_SET'); // Modify the generate_ID function to handle STUDY_SET type

        $query = "INSERT INTO STUDY_SET_T (StudySetID, UserID, CourseID, Title, Description, Instructor, Created, Modified)
              VALUES (:StudySetID, :UserID, :CourseID, :Title, :Description, :Instructor, :Created, :Modified)";

        // Current time for Created and Modified fields
        $currentTime = get_local_time();

        // Array of values to bind to the query
        $values = [
            ':StudySetID' => $studySetID,
            ':UserID' => $userID,
            ':CourseID' => $courseID,
            ':Title' => $title,
            ':Description' => $description,
            ':Instructor' => $instructor,
            ':Created' => $currentTime,
            ':Modified' => $currentTime
        ];

        // Execute the query for study set
        $result = run_database($query, $values);

        // Get the last inserted StudySetID
        $pdo = get_pdo_connection(); // This gets the PDO connection object

        // Handle the card data
        if (isset($data['cards'])) {
            $cards = json_decode($data['cards'], true);
            $cardQuery = "INSERT INTO STUDY_CARD_T (StudySetID, Front, Back)
                    VALUES (:StudySetID, :Front, :Back)";

            $currentTime = get_local_time();

            foreach ($cards as $card) {
                $stmt = $pdo->prepare($cardQuery);
                $stmt->execute([
                    ':StudySetID' => $studySetID,
                    ':Front' => $card['front'],
                    ':Back' => $card['back'],
                ]);
            }
        }

        header("Location: /study-sets/{$studySetID}");
    } catch (PDOException $e) {
        // Handle database errors (e.g., duplicate StudySetID)
        echo "Database error: " . $e->getMessage();
    }
}

function edit_study_set($setID, $data) {
    error_log(print_r($data, true));
    // Update the study set details
    $values = [
        ':StudySetID' => $setID,
        ':CourseID' => $data['course_id'],
        ':Title' => $data['setTitle'],
        ':Description' => $data['setDescription'],
        ':Instructor' => $data['instructor'],
        ':Modified' => get_local_time()
    ];

    $query = "UPDATE STUDY_SET_T 
        SET CourseID = :CourseID,
        Title = :Title,
        Description = :Description,
        Instructor = :Instructor,
        Modified = :Modified
        WHERE StudySetID = :StudySetID";
    run_database($query, $values);
    
    $pdo = get_pdo_connection(); 

    if (isset($data['cards'])) {
        $cards = json_decode($data['cards'], true);

        // Loop through each card
        foreach ($cards as $card) {
            if (isset($card['deleted']) && $card['deleted'] === true) { // Check for true explicitly
                // Execute delete query for this card
                $deleteQuery = "DELETE FROM STUDY_CARD_T WHERE CardID = :CardID";
                $pdo->prepare($deleteQuery)->execute([':CardID' => $card['id']]);
            } else if ($card['edited']) {
                // This card has been edited, update it in the database
                $updateQuery = "UPDATE STUDY_CARD_T SET Front = :Front, Back = :Back WHERE CardID = :CardID";
                $stmt = $pdo->prepare($updateQuery);
                $stmt->execute([
                    ':Front' => $card['front'],
                    ':Back' => $card['back'],
                    ':CardID' => $card['id']
                ]);
            } else if (isset($card['newCard']) && $card['newCard']) {
                // This is a new card, insert it into the database
                $insertQuery = "INSERT INTO STUDY_CARD_T (StudySetID, Front, Back) VALUES (:StudySetID, :Front, :Back)";
                $stmt = $pdo->prepare($insertQuery);
                $stmt->execute([
                    ':StudySetID' => $setID,
                    ':Front' => $card['front'],
                    ':Back' => $card['back']
                ]);
            } 
        }
    }

    header("Location: /study-sets/{$setID}");
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
        header("Location: /unauthorized.php"); // Needs to be implemented
        exit;
    }

    // Start a transaction
    $pdo->beginTransaction();

    try {
        // First, delete all study cards associated with the study set
        $deleteCardsQuery = "DELETE FROM STUDY_CARD_T WHERE StudySetID = :StudySetID";
        $pdo->prepare($deleteCardsQuery)->execute([':StudySetID' => $setID]);

        // Then, delete the study set itself
        $deleteSetQuery = "DELETE FROM STUDY_SET_T WHERE StudySetID = :StudySetID";
        $pdo->prepare($deleteSetQuery)->execute([':StudySetID' => $setID]);

        // Commit the transaction
        $pdo->commit();

        // Redirect or inform the user of successful deletion
        header("Location: /study-sets/");
        exit;
    } catch (PDOException $e) {
        // Roll back the transaction in case of an error
        $pdo->rollBack();

        // Log and handle the error
        error_log("Database error: " . $e->getMessage());
        // Redirect or display a user-friendly error message
        header("Location: /error.php");
        exit;
    }
}

?>