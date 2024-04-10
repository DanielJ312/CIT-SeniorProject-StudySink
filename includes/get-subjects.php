<?php
//////////* Get Subjects - Script that retrieves all subjects for a given university for the create post popup *//////////
// Include the functions file for database access
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");

// Function to fetch subjects based on a university ID using the run_database function
function get_subjects_by_university($universityId) {
    // Prepare the query to fetch subjects
    $query = "SELECT SubjectID, Name FROM SUBJECT_T WHERE UniversityID = :UniversityID ORDER BY Name ASC";
    $values = array(':UniversityID' => $universityId);

    // Use the run_database function to execute the query
    return run_database($query, $values);
}

// Get the university ID from the request
$universityId = $_GET['universityId'] ?? '';

// Validate the university ID
if (!ctype_digit($universityId)) {
    // Respond with an error if the university ID is not a number
    header('Content-Type: application/json');
    echo json_encode(["error" => "Invalid university ID"]);
    exit;
}

// Fetch the subjects for this university
$subjects = get_subjects_by_university($universityId);

// Return the subjects as a JSON response
header('Content-Type: application/json');
echo json_encode($subjects);
exit;
?>