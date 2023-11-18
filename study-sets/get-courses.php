<?php
// Include the functions file for database access
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");

// Function to fetch courses based on a subject ID using the run_database function
function get_courses_by_subject($subjectId) {
    // Prepare the query to fetch courses
    $query = "SELECT CourseID, Name, Abbreviation FROM COURSE_T WHERE SubjectID = :SubjectID ORDER BY Abbreviation ASC";
    $values = array(':SubjectID' => $subjectId);

    // Use the run_database function to execute the query
    return run_database($query, $values);
}

// Get the subject ID from the request
$subjectId = $_GET['subjectId'] ?? '';

// Validate the subject ID
if (!ctype_digit($subjectId)) {
    // Respond with an error if the subject ID is not a number
    header('Content-Type: application/json');
    echo json_encode(["error" => "Invalid subject ID"]);
    exit;
}

// Fetch the courses for this subject
$courses = get_courses_by_subject($subjectId);

// Return the courses as a JSON response
header('Content-Type: application/json');
echo json_encode($courses);
?>