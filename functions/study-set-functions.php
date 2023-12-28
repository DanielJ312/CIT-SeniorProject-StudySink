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
    echo "<script>console.log('Entered');</script>";
    echo "Test";
    $query = "DELETE FROM STUDY_CARD_T WHERE StudySetID = $setID;";
    run_database($query);

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
        $cardQuery = "INSERT INTO STUDY_CARD_T (StudySetID, Front, Back)
                VALUES (:StudySetID, :Front, :Back)";
        
        foreach ($cards as $card) {
            $stmt = $pdo->prepare($cardQuery);
            $stmt->execute([
                ':StudySetID' => $setID,
                ':Front' => $card['front'],
                ':Back' => $card['back']
            ]);
        }
    }
    header("Location: /study-sets/{$setID}");
}

?>