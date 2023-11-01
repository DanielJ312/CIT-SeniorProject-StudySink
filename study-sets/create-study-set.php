<?php 
$pageTitle = "Create Study Set";
include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php");
?>
<link rel="stylesheet" href="../styles/create-study-set.css">

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
                <div>
                    <input type="text" id="setUniversity" placeholder="University" name="setUniversity" required>
                </div>
                <div>
                    <input type="text" id="setSubject" placeholder="Subject" name="setSubject" required>
                </div>
                <div>
                    <input type="text" id="setCourse" placeholder="Course" name="setCourse" required>
                </div>
                <div>
                    <input type="text" id="setTeacher" placeholder="Teacher" name="setTeacher" required>
                </div>
            </div>
        </div>

        
        <div id="studyCards" class=studyCards>
            <!-- Study cards will be added here -->
        </div>

        <button type="button" onclick="addCard()">Add a Study Card</button>
        <button type="submit">Submit Study Set</button>
    </form>
</div>

<script>
    function addCard() {
        var cardContainer = document.getElementById("studyCards");
        var cardCount = cardContainer.children.length + 1;

        var card = document.createElement("div");
        card.innerHTML = `
            <div class=cardHeader>
                <div class=cardFront>
                    <label for="cardFront${cardCount}">Front:</label>
                    <input type="text" id="cardFront${cardCount}" name="cardFront${cardCount}" required>
                </div>
                <div class=cardBack>
                    <label for="cardBack${cardCount}">Back:</label>
                    <input type="text" id="cardBack${cardCount}" name="cardBack${cardCount}" required>
                </div>
            </div>
        `;
        cardContainer.appendChild(card);
    }

    // Initially add 5 blank cards
    for (let i = 0; i < 5; i++) {
        addCard();
    }
</script>


<?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
