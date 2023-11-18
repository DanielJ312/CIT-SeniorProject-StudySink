<!-- Reset - User enters verification code and new password to change password -->
<?php 
require($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/mail-functions.php");
check_login() ? header("Location: /account/profile.php") : null;
$pageTitle = "Reset Password";

$errors = ($_SERVER['REQUEST_METHOD'] == "POST") ? ((count($errors = reset_password($_POST)) == 0) ? header("Location: login.php") : $errors) : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
</head>
<body>
    <header>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
        <h2><?= isset($pageTitle) ? $pageTitle : "Page Header" ?></h2>
    </header>
    <main>
        <div>
            <?php display_errors($errors); ?>
        </div>
        <form method="post" novalidate>
            <p>Code: <input type="text" name="code"></p> 
            <p>Password: <input type="password" name="password"></p>
            <p>Confirm Password: <input type="password" name="password2"></p>
            <input type="submit" value="Reset Password">
        </form>
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>
</html>

<?php 
function reset_password($data) {
    $errors = array();

    $values = array();
    $values['Code'] = $data['code'];
    $query = "SELECT * FROM CODE_T WHERE Code = :Code LIMIT 1;";
    $result = run_database($query, $values);
        
    if (is_array($result)) {
        if (get_local_time() > $result[0]->Expires) {
            $errors[] = 'Your code has expired so a new one has been sent. Please enter the new one.';
            is_code_active("reset", $result[0]->Email);
        }
        else if (strlen(trim($data['password'])) < 4) {
            $errors[] = "Please enter a valid password.";
        }
        else if ($data['password'] != $data['password2']) {
            $errors[] = "Passwords must match.";
        }
        if (count($errors) == 0) {
            $result = $result[0];
            $values = array();
            $values['Email'] = $result->Email;
            $values['Password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            echo "$result->Email";
            // die;
            $query = "UPDATE USER_T SET Password = :Password WHERE Email = :Email";
            run_database($query, $values);
            delete_code("reset", $result->Email);
        }
    }
    else {
        $errors[] = "Verifcation code is incorrect.";
    }

    return $errors;
}
?>