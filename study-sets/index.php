<!-- Study-Set Display Page - Lists all Study Sets in Database -->
<?php 
require($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
$pageTitle = "Forum";

$query = "SELECT *, STUDY_SET_T.Created AS SetCreated FROM STUDY_SET_T INNER JOIN USER_T ON STUDY_SET_T.UserID = USER_T.UserID ORDER BY STUDY_SET_T.Created ASC;";
$sets = run_database($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
    <link rel="stylesheet" href="../styles/study-set-styles/index.css">
    <!-- <script async src="/forum/forum.js"></script> -->
</head>
<body class="studySetBrowsePageBody">
    <header>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
        <!-- <h2><?= isset($pageTitle) ? $pageTitle : "Page Header" ?></h2> -->
    </header>
    <main>
        <div class="studySetBrowsePageContainer">
            <h3>Study Sets</h3>
            <div class="displayCardArea">
                <?php for ($i = 0; $i < count($sets); $i++): ?> 
                    <a href="/study-sets/<?= htmlspecialchars($sets[$i]->StudySetID); ?>.php" class="card-link">
                        <div class="card">
                            <img src="/path/to/default-image.png" alt="Default Image"/>
                            <div>
                                <h3><?= htmlspecialchars($sets[$i]->Title) ?></h3>
                                <p>By: <?= htmlspecialchars($sets[$i]->Username) ?></p>
                                <p>Posted on <?= display_time($sets[$i]->SetCreated, "F j, Y") ?></p>
                                <p><?= htmlspecialchars(substr($sets[$i]->Description, 0, 100)) ?>...</p>
                            </div>
                        </div>
                    </a>
                <?php endfor; ?>
            </div>
        </div>
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>
</html>

<!-- <form action="" method="post">
    <select class="sort" name="sorts">
        <option value="oldest">Oldest</option>
        <option value="newest">Newest</option>
        <option value="popular">Popular</option>
    </select>
</form> -->
<!-- <button onclick="TestFunction('testing value')"></button> -->