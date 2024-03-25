<!-- Univeristy Selection Page  -->
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
update_session();
$pageTitle = "Universities";

$query = "SELECT * FROM UNIVERSITY_T ORDER BY Name ASC;";
$universities = run_database($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
    <!--<link rel="stylesheet" href="/styles/university/dark-mode.css" id="dark-theme"/>-->
    <link rel="stylesheet" href="/styles/university/university.css" />
    <script src="/university/university.js"></script>
</head>
<body>
    <header>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/to-top.php"); ?>
    </header>
    <main>
        <div class="university-main">
            <div class="margin">
                <div class="university-info">
                    <h2>Universities</h2>
                </div>
                <div class="outer-box">
                    <div class="search-bar-university">
                        <input id="searchbar" type="text" name="search" onkeyup="search_university()" placeholder="Search Universities..." />
                        <button><i class="fas fa-search"></i></button>
                    </div>
                    <div class="tiles">
                        <?php foreach ($universities as $university) : ?>
                            <a class="names" href="/university/<?= $university->Abbreviation ?>.php">
                                <div class="word"><?= htmlspecialchars($university->Name) ?></div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class=mobileuniversitydefault>
            <div class="mobilemargin">
                <div class="university-info">
                    <h2>Universities</h2>
                </div>
                <div class="outer-box">
                    <div class="search-bar-university">
                        <input id="searchbar2" type="text" name="search" onkeyup="search_university_mobile()" placeholder="Search Universities..." />
                        <button><i class="fas fa-search"></i></button>
                    </div>
                    <div class="tiles">
                        <?php foreach ($universities as $university) : ?>
                            <a class="names" href="/university/<?= $university->Abbreviation ?>.php">
                                <div class="word"><?= htmlspecialchars($university->Name) ?></div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>
</html>