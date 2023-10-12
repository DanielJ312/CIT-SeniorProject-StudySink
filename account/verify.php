<?php 
$pageTitle = "Verify Account";
include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php");

$expiredCode = false;

if (!isset($_SESSION['LOGGED_IN'])) {
    header("Location: /account/login.php");
}

$errors = array();
if ($_SERVER['REQUEST_METHOD'] == "GET" && !check_verification()) {
    $expiredCode = is_code_active("verify", $_SESSION['USER']->email);
}
else {
    header('Location: profile.php');
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (!check_verification()) {
        $errors = verify_account();
    } else {
        $errors[] = "You are already verified.";
    }
}

?>

<h2><?=isset($pageTitle) ? $pageTitle : "Page Header" ?></h2>
<h4>Access when logged in</h4>
<div>
    <div>
        <?php display_errors($errors); ?>
    </div>
    <p>A verification code has been sent to your email. Enter the code below.</p>
    <form method="post">
        <?php if ($expiredCode == true): ?>
            <p>Your previous verifcation code has expired. A new one has been sent.</p> 
        <?php else: ?>
            <p>You currently have an active verifcation code.</p> 
        <?php endif; ?>
        <p>Code: <input type="text" name="code"></p>
        <input type="submit" value="Verify">
    </form>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>

<?php 
function verify_account() {
    $values['email'] = $_SESSION['USER']->email;
    $values['code'] = $_POST['code'];

    $query = "SELECT * FROM verify_t where code = :code && email = :email";
    $result = run_database($query, $values);
    if (is_array($result)) {
        $result = $result[0];

        if ($result->expires > get_local_time()) {
            $email = $result->email;
            $query = "UPDATE user_t SET verified = 1 where email = '$email' limit 1";
            $result = run_database($query);
            delete_code("verify", $email);
            header("Location: profile.php");
            die;
        } else {
            $errors[] = "Code expired";
        }
    } else {
        $errors[] = "Wrong code.";
    }

    return $errors;
}

?>