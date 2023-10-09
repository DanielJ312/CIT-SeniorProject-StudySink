<?php 
$pageTitle = "Reset Password";
include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php");

$errors = array();
$displayRedirect = false;
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $errors = check_email($_POST);

    if (count($errors) == 0) {
        $displayRedirect = is_code_active("reset", $_POST['email']);
        if ($displayRedirect == false) {
            header("Location: reset.php");
        }
    }
}
# SELECT * FROM verify_t WHERE type = 'reset' AND email = 'daniel.javaherian.730@my.csun.edu';
?>

<h2><?=isset($pageTitle) ? $pageTitle : "Page Header" ?></h2>
<div>
    <div>
        <?php display_errors($errors); ?>
    </div>
    <form method="post" novalidate>
        <?php if ($displayRedirect): ?>
            <p>A verification code for this email is already active. <a href="reset.php">Change your password.</a></p> 
        <?php endif; ?>
        <p>Enter your email to reset your password:</p>
        <p>Email: <input type="email" name="email"></p>
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
        if (!is_array($result)) {
            $errors[] = "There is no account associated with the email entered.";
        }
    } 
    
    return $errors;
}

?>