<!-- Forum Home - Lists all posts made and allows users to sort -->
<?php 
require($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
$pageTitle = "Forum";

$query = "SELECT * FROM STUDY_SET_T INNER JOIN USER_T ON STUDY_SET_T.UserID = USER_T.UserID ORDER BY STUDY_SET_T.Created ASC;";
$sets = run_database($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
    <!-- <script async src="/forum/forum.js"></script> -->
</head>
<body>
    <header>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
        <h2><?= isset($pageTitle) ? $pageTitle : "Page Header" ?></h2>
    </header>
    <main>
        <div>
            <p><a href="/study-sets/create.php">Create a new study set</a></p>
        </div>
        <h3>Study Sets</h3>
        <!-- <form action="" method="post">
            <select class="sort" name="sorts">
                <option value="oldest">Oldest</option>
                <option value="newest">Newest</option>
                <option value="popular">Popular</option>
            </select>
        </form> -->
        <!-- <button onclick="TestFunction('testing value')"></button> -->
        <div class="study-sets">
        <?php for ($i=0; $i < count($sets); $i++): ?> 
            <a href="/study-sets/<?=$sets[$i]->StudySetID; ?>.php">
                <p><?=$sets[$i]->Title?></p>
                <p>By: <?= $sets[$i]->Username ?></p>
            </a>
        <?php endfor; ?>
        </div>
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>
</html>