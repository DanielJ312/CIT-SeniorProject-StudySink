<!-- Create Post - Creates a forum post -->
<?php 
require($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
update_session();
if (!check_login()) header("Location: /account/login.php"); 
$pageTitle = "Create Post";

$errors = $_SERVER['REQUEST_METHOD'] == "POST" ? create_post($_POST) : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
</head>
<body>
    <header>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/to-top.php"); ?>
        <h2><?= isset($pageTitle) ? $pageTitle : "Page Header" ?></h2>
        <h3 style="color: red">THIS PAGE DOES NOT CURRENTLY WORK</h3>
    </header>
    <main>
        <div>
            <?php display_errors($errors); ?>
        </div>
        <form method="post">
            <p>Title: <input type="text" name="title"></p>
            <p>Content: <textarea name="content" rows="5" cols="40"></textarea></p>
            <input type="submit" value="Submit">
        </form>
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>
</html>