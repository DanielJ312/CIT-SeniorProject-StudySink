<!-- Post Redirect Page -->
<?php
//////////* Post Index - Redirects to user's university page *//////////
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
$location = isset($_SESSION['USER']->Abbreviation) ? "university/{$_SESSION['USER']->Abbreviation}" : "university";
header("Location: /$location.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
</head>
<body>
    <header>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
    </header>
    <main>
        <!-- No Conent required -->
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>
</html>