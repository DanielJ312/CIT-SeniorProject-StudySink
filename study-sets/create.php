<?php 
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/study-set-functions.php");
$pageTitle = "Create Study Set";
update_session();
$universities = get_universities_list();

// Check if the form was submitted with id="studySetForm"
if ($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['form_id'] == 'studySetForm') create_study_set($_POST);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
    <link rel="stylesheet" href="/styles/study-set-styles/create.css">
</head>
<body class="createStudySetBody">
    <header>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/to-top.php"); ?>
    </header>
    <main>
        <div class="fixed-action-bar">
            <div class="action-bar-buttons">
            <a href="javascript:history.back()" class="back-button">Cancel</a>
                <button type="submit" form="studySetForm" class="save-button">Save Study Set</button>
            </div>
        </div>
        <div id="pageIdentifier" data-page-type="create"></div>
        <div class="studySetContainer">
            <h2 class="header2"><?=isset($pageTitle) ? $pageTitle : "Create a Study Set" ?></h2>
            <form id="studySetForm" method="POST">
                <!-- This first input that has type=hidden is just a jank way to differentiate between forms being submitted -->
                <input type="hidden" name="form_id" value="studySetForm">
                <div class="titleContainer">
                    <input type="text" id="setTitle" placeholder="Enter Title Here: &quot;Computer Science 101 - Chapter 1&quot;" name="setTitle" maxlength="255" required>
                </div>
                <div class="studySetTags"> 
                    <div class="description">
                        <textarea type= "text" id="setDescription" placeholder="Add a Description..." name="setDescription" maxlength="500" required></textarea>
                    </div>
                    
                    <div class="columnTags">
                        <!-- University Select -->
                        <select id="setUniversity" name="setUniversity" required>
                            <option value="" disabled selected>Select University</option>
                            <?php foreach($universities as $university): ?>
                                <option value="<?= htmlspecialchars($university->UniversityID) ?>">
                                    <?= htmlspecialchars($university->Name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        
                        <!-- Subject Select -->
                        <select id="setSubject" name="setSubject" required>
                            <option value="">Select Subject</option>
                            <!-- Options will be added here by JavaScript after selecting a university -->
                        </select>
                        
                        <!-- Course Select -->
                        <select id="setCourse" name="setCourse" required>
                            <option value="">Select Course</option>
                            <!-- Options will be added here by JavaScript after selecting a subject -->
                        </select>
                        
                        <!-- Instructor Input -->
                        <input type="text" id="setInstructor" placeholder="Instructor" name="instructor" maxlength="65" required>
                    </div>
                </div>
                <div id="studyCards" class="studyCards create">
                    <!-- Study cards will be added here -->
                </div>
                <button type="button" id="addCardBtn" class="bigAddCardButton">+ ADD CARD</button>
            </form>
        </div>
    </main>

    <div id="modal-delete-last-card" style="display:none;">
        <div class="modal-content">
            <p>Cannot delete the last study card in the Study Set.</p>
            <div class="buttons-container">
                <button id="closeModal">OK</button>
            </div>
        </div>
    </div>

    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
    <script src="/study-sets/study-set-create.js"></script>
</body>
</html>