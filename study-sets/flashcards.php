<?php
// Connect to the database
require($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");

// Get the StudySetID from URL or default
$setID = isset($_GET['setID']) ? $_GET['setID'] : 'default';

// Check if shuffle is requested
$shuffle = isset($_GET['shuffle']) && $_GET['shuffle'] == 'true';

// Prepare the query
$query = "SELECT * FROM STUDY_CARD_T WHERE StudySetID = :StudySetID";
if ($shuffle) {
    $query .= " ORDER BY RAND()";
} else {
    $query .= " ORDER BY CardID";
}

$titleQuery = "SELECT Title FROM STUDY_SET_T WHERE StudySetID = :StudySetID";
$titleResult = run_database($titleQuery, ['StudySetID' => $setID]);
$studySetTitle = $titleResult ? $titleResult[0]->Title : 'Study Set';

// Fetch the cards
$values = ['StudySetID' => $setID];
$cards = run_database($query, $values);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
    <link rel="stylesheet" href="/styles/study-set-styles/flashcards.css">
</head>
<body class="flashcardsBody">
    <header>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
    </header>
    <main>
        <div class="flashcardsContainer">
            <div class="cardCounter">
                <span id="currentCardIndex">1</span>/<span id="totalCards"></span>
            </div>
            <h2>Flashcards for <?= htmlspecialchars($studySetTitle); ?></h2>
            <div class="cards">

                <?php foreach ($cards as $card): ?>
                    <div class="cardContainer">
                        <div class="cardFront"><?= nl2br(htmlspecialchars($card->Front)); ?></div>
                        <div class="cardBack"><?= nl2br(htmlspecialchars($card->Back)); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="navigationContainer">
                <div class="arrowButtons">
                    <button id="backButton"><i class="fa-solid fa-circle-arrow-left"></i></button>
                    <button id="nextButton"><i class="fa-solid fa-circle-arrow-right"></i></button>
                </div>
                <button id="shuffleButton" class="rightAlignedButton"><i class="fa-solid fa-arrows-rotate"></i></button>
            </div>
        </div>
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
    <script src="/study-sets/flashcards.js"></script>
</body>
</html>
