<!-- Login - Users enter account information to login with either email or username -->
<?php 
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
update_session();
$pageTitle = "Login";

if ($_SERVER['REQUEST_METHOD'] == "POST") $errors = login($_POST);
if (check_login()) header("Location: /account/profile.php"); 
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
        <form method="post">
            <p>Email or Username: <input type="text" name="logininput"></p>
            <p>Password: <input type="password" name="password"></p>
            <input type="submit" value="Login">
            <p>Don't have an account? <a href="register.php">Sign up</a></p>
            <p>Forgot password? <a href="forgot.php">Reset password</a></p>
        </form>
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>
</html>

<?php 
function login($data) {
    $errors = array();
    $loginType = "Email";
    
    if (filter_var($data['logininput'], FILTER_VALIDATE_EMAIL)) {
        $loginType = "Email";
    }
    else if (preg_match('/^[a-zA-Z0-9]+$/', $data['logininput'])) {
        $loginType = "Username";
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
            case 'Email':
                $values['Email'] = $data['logininput'];
                break;
            case 'Username':
                $values['Username'] = $data['logininput'];
                break;
            default:
                break;
        }
        $password = $data['password'];

        $query = "SELECT * FROM USER_T WHERE $loginType = :$loginType LIMIT 1;";
        $result = run_database($query, $values);
        

        if (!empty($result)) {
            $result = $result[0];
            if (password_verify($password, $result->Password)) {
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