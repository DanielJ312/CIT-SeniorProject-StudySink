<!-- FINISH LATER - No current use other than for testing. -->
<?php
require($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
update_session();
$pageTitle = "Subject";

$query = "SELECT * FROM UNIVERSITY_T WHERE Abbreviation = '{$_GET['university']}';";
$university = run_database($query)[0];

$query = "SELECT * FROM SUBJECT_T WHERE Abbreviation = '{$_GET['subject']}' && UniversityID = {$university->UniversityID};";
$subject = run_database($query)[0];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
    <script>
        // history.pushState(null, null, '/new-page.html');
    </script>
</head>
<body>
    <header>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
        <h2><?= isset($pageTitle) ? $pageTitle : "Page Header" ?></h2>
    </header>
    <main>
        <div>
            <p><?= $university->Name?></p>
            <p><?= $subject->Name?></p>
        </div>
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>
</html>