<?php 
$pageTitle = "Reset Password";
include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php");

$errors = array();
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $errors = reset_password($_POST);
    if (count($errors) == 0) {
        header("Location: login.php");
    }
}

?>

<h2><?=isset($pageTitle) ? $pageTitle : "Page Header" ?></h2>
<div>
    <div>
        <?php display_errors($errors); ?>
    </div>
    <form method="post" novalidate>
        <p>Code: <input type="text" name="code"></p> 
        <p>Password: <input type="password" name="password"></p>
        <p>Confirm Password: <input type="password" name="password2"></p>
        <input type="submit" value="Reset Password">
    </form>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>

<?php 
function reset_password($data) {
    $errors = array();

    $values = array();
    $values['code'] = $data['code'];
    $query = "SELECT * FROM verify_t WHERE code = :code limit 1";
    $result = run_database($query, $values);
        
    if (is_array($result)) {
        if (get_local_time() > $result[0]->expires) {
            $errors[] = 'Your code has expired so a new one has been sent. Please enter the new one.';
            is_code_active("reset", $result[0]->email);
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
            $values['email'] = $result->email;
            $values['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            echo "$result->email";
            // die;
            $query = "UPDATE user_t SET password = :password WHERE email = :email";
            run_database($query, $values);
            delete_code("reset", $result->email);
        }
    }
    else {
        $errors[] = "Verifcation code is incorrect.";
    }

    return $errors;
}

?>