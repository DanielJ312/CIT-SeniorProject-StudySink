<?php 
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
//$pageTitle = "Create Study Set";
$universities = get_universities_list();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
        <link rel="stylesheet" href="../styles/study-set-styles/create.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    </head>
    <body class="createStudySetBody">
        <header>
            <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
        </header>
        <main>
            <div class="studySetContainer">
                <h2 class="header2"><?=isset($pageTitle) ? $pageTitle : "Create a Study Set" ?></h2>
                <form id="studySetForm" method="POST" action="save-study-set">
                    <div class="titleContainer">
                        <input type="text" id="setTitle" placeholder="Enter Title Here: &quot;Computer Science 101 - Chapter 1&quot;" name="setTitle" maxlength="255" required>
                    </div>
                    <div class="studySetTags"> 
                        <div class="description">
                            <textarea type= "text" id="setDescription" placeholder="Add a Description..." name="setDescription" maxlength="500" required></textarea>
                        </div>
                       
                        <div class="columnTags">
                            <input list="universities" id="setUniversity" placeholder="University" name="setUniversity" required>
                            <datalist id="universities">
                                <?php foreach($universities as $university): ?>
                                    <option value="<?= htmlspecialchars($university->Name) ?>" data-id="<?= $university->UniversityID ?>">
                                        <?= htmlspecialchars($university->Name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </datalist>
                            
                            <input list="subjects" id="setSubject" placeholder="Subject" name="setSubject" required>
                            <datalist id="subjects">
                                <!-- Options will be added here by JavaScript after selecting a university -->
                            </datalist>
                            
                            <input list="courses" id="setCourse" placeholder="Course" name="setCourse" required>
                                <datalist id="courses">
                                    <?php foreach ($courses as $course): ?>
                                        <option value="<?php echo htmlspecialchars($course->Abbreviation); ?>" data-id="<?php echo htmlspecialchars($course->CourseID); ?>">
                                            <?php echo htmlspecialchars($course->Name); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </datalist>
                            
                            <input type="text" id="setTeacher" placeholder="Teacher" name="setTeacher" maxlength="65" required>
                        </div>
                    </div>
                    <div id="studyCards" class=studyCards>
                        <!-- Study cards will be added here -->
                    </div>
                    <div class="formButtons"> 
                        <button type="button" id="addCardBtn">Add a Study Card</button>
                        <button type="submit">Save Study Set</button>
                    </div>
                </form>
            </div>
        </main>
        <footer>
            <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
        </footer>
        <script src="./study-set-create.js"></script>
    </body>
</html>