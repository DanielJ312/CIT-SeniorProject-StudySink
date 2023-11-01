<!-- Register - User creates account by entering username, email, and password -->
<?php 
require($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
check_login() ? header("Location: /account/profile.php") : null;
$pageTitle = "Create Account";

$errors = ($_SERVER['REQUEST_METHOD'] == "POST") ? ((count($errors = signup($_POST)) == 0) ? header("Location: login.php") : $errors) : [];
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
            <?php display_errors($errors);?>
        </div>
        <form method="post">
            <p>Username: <input type="text" name="username"></p>
            <p>Email Address: <input type="email" name="email"></p>
            <p>Password: <input type="password" name="password"></p>
            <p>Confirm Password: <input type="password" name="password2"></p>
            <input type="submit" formnovalidate value="Register">
            <p>Already have an account? <a href="login.php">Sign in</a></p>
        </form>
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>
</html>

<?php 
function signup($data) {
    $errors = array();

    // validate
    if (!preg_match('/^[a-zA-Z0-9]+$/', $data['username'])) {
        $errors[] = "Please enter a valid username.";
    }
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email.";
    }
    if (strlen(trim($data['password'])) < 4) {
        $errors[] = "Please enter a valid password.";
    }
    else if ($data['password'] != $data['password2']) {
        $errors[] = "Passwords must match.";
    }
    $checkEmail = run_database("SELECT * FROM USER_T WHERE Email = :Email LIMIT 1;",['Email'=>$data['email']]);
    if (is_array($checkEmail)) {
        $errors[] = "Email already exists.";
    }
    
    // save
    if (count($errors) == 0) {
        $values['UserID'] = generate_ID("User");
        $values['Username'] = $data['username'];
        $values['Email'] = $data['email'];
        $values['Password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $values['Created'] = get_local_time();
        
        $query = "INSERT INTO USER_T (UserID, Username, Email, Password, Created) VALUES (:UserID, :Username, :Email, :Password, :Created);";
        run_database($query, $values);
    }

    return $errors;
}