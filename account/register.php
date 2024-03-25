<!-- Register - User creates account by entering username, email, and password -->
<?php 
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/account-functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/mail-functions.php");
if (check_login()) header("Location: /account/profile.php"); 
$pageTitle = "Create Account";

$errors = ($_SERVER['REQUEST_METHOD'] == "POST") ? ((count($errors = signup($_POST)) == 0) ? header("Location: verify.php") : $errors) : [];

$query = "SELECT UniversityID, Name, Abbreviation FROM UNIVERSITY_T;";
$universities = run_database($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
    <link rel="stylesheet" type="text/css" href="/styles/account/register.css">
</head>
<body>
    <header>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
    </header>
    <main>
        <div class="register-container">
        <div class="lheader"><?= isset($pageTitle) ? $pageTitle : "Page Header" ?></div>
        <div>
       
        </div>
        <form method="post">
            <p>Username&nbsp;*&nbsp; &nbsp; <input type="text" name="username"></p>
            <?= isset($errors['username']) ? "<p>" . $errors['username'] . "</p>": ""; ?>
            <p>Email&nbsp;*&nbsp; &nbsp; <input type="email" name="email"></p>
            <?= isset($errors['email']) ? "<p>" . $errors['email'] . "</p>": ""; ?>
            <p>Password&nbsp;*&nbsp; <input type="password" name="password"></p>
            <?= isset($errors['password']) ? "<p>" . $errors['password'] . "</p>": ""; ?>
            <p>Confirm Password&nbsp;* <input type="password" name="password2"></p>
            <?= isset($errors['password2']) ? "<p>" . $errors['password2'] . "</p>": ""; ?>

            <p>Select University &lpar;Optional&rpar;</p>
            <select class="" name="useruni">
                    <option value="0">None</option>
                <?php foreach ($universities as $university):?>
                    <option value="<?= $university->UniversityID?>"><?= $university->Name . " (" . $university->Abbreviation . ")";?></option>
                <?php endforeach; ?>
            </select>
            <input type="submit" formnovalidate value="Register">
            <p>Already have an account? <a href="login.php"> Sign in</a></p>
        </form>
        <div>
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>
</html>