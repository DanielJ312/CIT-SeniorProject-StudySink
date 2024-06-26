<?php
//////////* Register - User creates account by entering username, email, and password with optional university *//////////
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/account-functions.php");
if (check_login()) header("Location: /account/profile.php");
$errors = ($_SERVER['REQUEST_METHOD'] == "POST") ? ((count($errors = signup($_POST)) == 0) ? header("Location: verify.php") : $errors) : [];
$universities = get_universities_list();
$pageTitle = "Sign Up";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
    <link rel="stylesheet" type="text/css" href="/styles/account/register.css">
</head>
<body class="register-body">
    <header>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/to-top.php"); ?>
    </header>
    <main class="register-main">
        <div class="register-container">
            <div class="lheader"><?= isset($pageTitle) ? $pageTitle : "Page Header" ?></div>
            <form method="post">
                <div class="register-form-error">
                    <?= isset($errors['username']) ? "<p>" . $errors['username'] . "</p>" : ""; ?>
                </div>
                <div class="register-username">
                    <p>Username</p>
                    <input type="text" name="username" value="<?= $_POST['username'] ?? ''; ?>">
                </div>
                <div class="register-form-error">
                    <?= isset($errors['email']) ? "<p>" . $errors['email'] . "</p>" : ""; ?>
                </div>
                <div class="register-email">
                    <p>Email</p>
                    <input type="email" name="email" value="<?= $_POST['email'] ?? ''; ?>">
                </div>
                <div class="register-form-error">
                    <?= isset($errors['password']) ? "<p>" . $errors['password'] . "</p>" : ""; ?>
                </div>
                <div class="register-password">
                    <p>Password</p>
                    <input type="password" name="password" value="<?= $_POST['password'] ?? ''; ?>">
                </div>
                <div class="register-form-error">
                    <?= isset($errors['password2']) ? "<p>" . $errors['password2'] . "</p>" : ""; ?>
                </div>
                <div class="register-password-confirm">
                    <p>Confirm Password</p>
                    <input type="password" name="password2">
                </div>
                <div class="register-primary-university">
                    <div style="display: flex; flex-direction: column;">
                        <p style="margin-bottom: 0;">Primary University</p>
                        <p style="margin: 0;">&lpar;optional&rpar;</p>
                    </div>
                    <select class="Uni-selection" name="useruni">
                        <option value="0">None</option>
                        <?php foreach ($universities as $university) : ?>
                            <option value="<?= $university->UniversityID ?>" <?=isset($_POST['useruni']) && $_POST['useruni'] == $university->UniversityID ? 'selected' : ''; ?>><?= $university->Name . " (" . $university->Abbreviation . ")"; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <input type="submit" formnovalidate value="Register">
            </form>
            <div class="register-signin">
                <p>Already have an account?</p>
                <a href="login.php">Sign in</a>
            </div>
        </div>
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>
</html>