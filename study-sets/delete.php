<?php
//////////* Delete - Deletes a study set */////////
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/study-set-functions.php");

$setID = $_GET['id'] ?? null;  // Get the Study Set ID from the URL

// If there's a valid Study Set ID, proceed with deletion
if ($setID) {
    delete_study_set($setID);
} else {
    // Redirect to an error page if the ID is invalid or not provided
    university_redirect();
    exit;
}
?>