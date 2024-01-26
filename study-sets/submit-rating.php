<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/study-set-functions.php");

header('Content-Type: application/json');

if (!check_login()) {
    echo json_encode(['error' => 'User must be logged in to rate.']);
    exit;
}

$studySetID = $_POST['studySetID'] ?? null;
$rating = $_POST['rating'] ?? null;
$userID = $_SESSION['USER']->UserID ?? null;

if (!$studySetID || !$rating || !$userID) {
    echo json_encode(['error' => 'Error: Required data not received.']);
    exit;
}

$pdo = get_pdo_connection();
addOrUpdateRating($pdo, $studySetID, $userID, $rating);

$avgRatingQuery = "SELECT AVG(Rating) as AvgRating FROM STUDY_SET_RATINGS WHERE StudySetID = :StudySetID";
$avgRatingResult = run_database($avgRatingQuery, ['StudySetID' => $studySetID]);
$averageRating = $avgRatingResult ? round($avgRatingResult[0]->AvgRating, 2) : 'Not rated';

echo json_encode(['avgRating' => $averageRating, 'message' => 'Rating submitted successfully!']);
?>