<?php 
$pageTitle = "Reset Password";
include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php");

$errors = array();
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $errors = check_email($_POST);

    if (count($errors) == 0) {
        header("Location: reset.php");
    }
}

?>

<h2><?=isset($pageTitle) ? $pageTitle : "Page Header" ?></h2>
<div>
    <div>
        <?php display_errors($errors); ?>
    </div>
    <form method="post" novalidate>
        <p>Enter your email to reset your password:</p>
        <p>Email or Username: <input type="email" name="email"></p>
        <input type="submit" value="Send Code To Email">
    </form>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>

<?php 
function check_email($data) {
    $errors = array();

    // validate
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email.";
    }
    else {
        $values['email'] = $data['email'];

        $query = "SELECT * FROM user_t WHERE email = :email limit 1";
        $result = run_database($query, $values);
        $result = $result[0];
        
        if (!empty($result)) {
            send_code("reset", $result->email);
        }
        else {
            $errors[] = "There is no account associated with the email entered.";
        }
    } 
    
    return $errors;
}

?>