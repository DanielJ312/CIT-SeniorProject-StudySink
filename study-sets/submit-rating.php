<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/study-set-functions.php");

// Check if the user is logged in
if (!check_login()) {
    echo "User must be logged in to rate.";
    exit;
}

$studySetID = $_POST['studySetID'] ?? null;
$rating = $_POST['rating'] ?? null;
$userID = $_SESSION['USER']->UserID ?? null; // Added null coalescing for userID

if (!$studySetID || !$rating || !$userID) {
    echo 'Error: Required data not received. Received studySetID: ' . $studySetID . ', rating: ' . $rating . ', userID: ' . $userID;
    exit;
}

// Create a PDO connection
$pdo = get_pdo_connection(); // Replace with your actual function to get the PDO connection

if ($studySetID && $rating) {
    // Call a function to handle the rating logic
    addOrUpdateRating($pdo, $studySetID, $userID, $rating); // Pass the PDO object as the first argument
    echo "Rating submitted successfully!";
} else {
    echo 'Received studySetID: ' . $studySetID . ', rating: ' . $rating;
    exit("Error: Invalid data.");
}
?>
