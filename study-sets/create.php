<?php 
require($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
$pageTitle = "Create Study Set";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
    <script async src="/study-sets/study-set.js"></script>
    <link rel="stylesheet" href="/styles/study-set-create.css">
</head>
<body>
    <header>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
        <h2><?= isset($pageTitle) ? $pageTitle : "Page Header" ?></h2>
    </header>
    <main>
        <div class="studySetContainer">
            <h2><?=isset($pageTitle) ? $pageTitle : "Create a Study Set" ?></h2>
            <form id="studySetForm">
                <div class="titleContainer">
                    <input type="text" id="setTitle" placeholder="Enter Title Here: &quot;Computer Science 101 - Chapter 1&quot;" name="setTitle" required>
                </div>
                <div class="studySetTags"> 
                    <div class="description">
                        <textarea id="setDescription" placeholder=" Add a Description..." name="setDescription" required></textarea>
                    </div>
                    <div class="columnTags">
                            <input type="text" id="setUniversity" placeholder="University" name="setUniversity" required>
                            <input type="text" id="setSubject" placeholder="Subject" name="setSubject" required>
                            <input type="text" id="setCourse" placeholder="Course" name="setCourse" required>
                            <input type="text" id="setTeacher" placeholder="Teacher" name="setTeacher" required>
                    </div>
                </div>
                <div id="studyCards" class=studyCards>
                    <!-- Study cards will be added here -->
                </div>
                <button type="button" onclick="addCard()">Add a Study Card</button>
                <button type="submit">Submit Study Set</button>
            </form>
        </div>
        </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>
</html>

<script>
function addCard() {
    var cardContainer = document.getElementById("studyCards");
    var cardCount = cardContainer.children.length + 1;

    var card = document.createElement("div");
    card.classList.add("cardHeader");
    card.id = "card-" + cardCount;
    card.innerHTML = `
        <div class=cardFront>
            <label for="front-${cardCount}">Front:</label>
            <input type="text" id="front-${cardCount}" name="front-${cardCount}" required>
        </div>
        <div class=cardBack>
            <label for="back-${cardCount}">Back:</label>
            <input type="text" id="back-${cardCount}" name="back-${cardCount}" required>
        </div>
    `;
    cardContainer.appendChild(card);
}

// Initially add 5 blank cards
for (let i = 0; i < 5; i++) {
    addCard();
}
</script>