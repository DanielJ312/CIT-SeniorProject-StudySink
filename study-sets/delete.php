<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/study-set-functions.php");

$setID = $_GET['id'] ?? null;  // Get the Study Set ID from the URL

// If there's a valid Study Set ID, proceed with deletion
if ($setID) {
    delete_study_set($setID);
    // Note: No further code is executed after delete_study_set due to its internal redirections
} else {
    // Redirect to an error page if the ID is invalid or not provided
    header("Location: /error-page.php");  // Replace with your actual error page URL
    exit;
}
?>