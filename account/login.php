<?php 
$pageTitle = "Login";
include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php");

$errors = array();
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $errors = login($_POST);

    if (count($errors) == 0) {
        header("Location: profile.php");
    }
}
if (check_login()) {
    header("Location: /account/profile.php");
}

?>

<h2><?=isset($pageTitle) ? $pageTitle : "Page Header" ?></h2>
<div>
    <div>
        <?php display_errors($errors); ?>
    </div>
    <form method="post">
        <p>Email or Username: <input type="text" name="logininput"></p>
        <p>Password: <input type="password" name="password"></p>
        <input type="submit" value="Login">
        <p>Don't have an account? <a href="register.php">Sign up</a></p>
        <p>Forgot password? <a href="forgot.php">Reset password</a></p>
    </form>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>

<?php 
function login($data) {
    $errors = array();
    $loginType = "email";
    
    if (filter_var($data['logininput'], FILTER_VALIDATE_EMAIL)) {
        $loginType = "email";
    }
    else if (preg_match('/^[a-zA-Z0-9]+$/', $data['logininput'])) {
        $loginType = "username";
    }
    else {
        $errors[] = "Please enter a valid email or username.";
    }
    if (strlen(trim($data['password'])) < 4) {
        $errors[] = "Please enter a valid password.";
    }

    // check
    if (count($errors) == 0) {
        switch ($loginType) {
            case 'email':
                $values['email'] = $data['logininput'];
                break;
            case 'username':
                $values['username'] = $data['logininput'];
                break;
            default:
                break;
        }
        $password = $data['password'];

        $query = "SELECT * FROM user_t WHERE $loginType = :$loginType limit 1";
        $result = run_database($query, $values);
        

        if (!empty($result)) {
            $result = $result[0];
            if (password_verify($password, $result->password)) {
                $_SESSION['USER'] = $result;
                $_SESSION['LOGGED_IN'] = true;
            } else {
                $errors[] = "Incorrect password.";
            }
        } else {
            $errors[] = "An account associated with this email does not exist.";
        }
    }

    return $errors;
}

?>