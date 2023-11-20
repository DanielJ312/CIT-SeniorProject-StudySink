<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
// Check if the user is logged in
if (!check_login()) {
    // Redirect to login page or display an error
    die("User must be logged in to create a Study Set.");
}

// Assume the data from the form is in $_POST
$title = $_POST['setTitle'] ?? null;
$description = $_POST['setDescription'] ?? null;
$courseID = $_POST['course_id'] ?? null;
$teacher = $_POST['setTeacher'] ?? null;
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

    $query = "INSERT INTO STUDY_SET_T (StudySetID, UserID, CourseID, Title, Description, Teacher, Created, Modified)
              VALUES (:StudySetID, :UserID, :CourseID, :Title, :Description, :Teacher, :Created, :Modified)";

    // Current time for Created and Modified fields
    $currentTime = get_local_time();

    // Array of values to bind to the query
    $values = [
        ':StudySetID' => $studySetID,
        ':UserID' => $userID,
        ':CourseID' => $courseID,
        ':Title' => $title,
        ':Description' => $description,
        ':Teacher' => $teacher,
        ':Created' => $currentTime,
        ':Modified' => $currentTime
    ];

    // Execute the query for study set
    $result = run_database($query, $values);

    // Get the last inserted StudySetID
    $pdo = get_pdo_connection(); // This gets the PDO connection object

    // Handle the card data
    if (isset($_POST['cards'])) {
        $cards = json_decode($_POST['cards'], true);
        $cardQuery = "INSERT INTO STUDY_CARD_T (StudySetID, Front, Back, Created, Modified)
                    VALUES (:StudySetID, :Front, :Back, :Created, :Modified)";

        $currentTime = get_local_time();

        foreach ($cards as $card) {
            $stmt = $pdo->prepare($cardQuery);
            $stmt->execute([
                ':StudySetID' => $studySetID,
                ':Front' => $card['front'],
                ':Back' => $card['back'],
                ':Created' => $currentTime,
                ':Modified' => $currentTime
            ]);
        }
    }

    header('Location: /index.php?status=success');
} 

catch (PDOException $e) {
    // Handle database errors (e.g., duplicate StudySetID)
    echo "Database error: " . $e->getMessage();
}
?>