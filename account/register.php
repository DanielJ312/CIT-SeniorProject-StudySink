<?php 
$pageTitle = "Create Account";
include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php");

$errors = array();
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $errors = signup($_POST);

    if (count($errors) == 0) {
        header("Location: login.php");
    }
}
if (isset($_SESSION['LOGGED_IN'])) {
    header("Location: /account/profile.php");
}

?>

<h2><?=isset($pageTitle) ? $pageTitle : "Page Header" ?></h2>
<div>
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
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>

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
    $checkEmail = run_database("SELECT * FROM user_T WHERE email = :email limit 1",['email'=>$data['email']]);
    if (is_array($checkEmail)) {
        $errors[] = "Email already exists.";
    }
    
    // save
    if (count($errors) == 0) {
        $values['userid'] = generateID("user");
        $values['username'] = $data['username'];
        $values['email'] = $data['email'];
        $values['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $values['created'] = get_local_time();
        
        $query = "INSERT INTO user_t (userid, username, email, password, created) VALUES (:userid, :username, :email, :password, :created)";
        run_database($query, $values);
    }

    return $errors;
}

function createID() {
    do {
        $createdID = rand(101, 999);
        $query = "SELECT * FROM user_t WHERE userid = '$createdID' limit 1";
        $result = run_database($query);
        $result = $result[0];
    } while ($createdID == $result->userid);
    
    return $createdID;
}
    
?>