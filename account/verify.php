<?php 
$pageTitle = "Verify";
include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php");

// check_login();

$errors = array();
if ($_SERVER['REQUEST_METHOD'] == "GET" && !check_verification()) {
    send_code("verify", $_SESSION['USER']->email);
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
        <p>Code: <input type="text" name="code"></p>
        <input type="submit" value="Verify">
    </form>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>

<?php 

function verify_account() {
    $errors = array();
    $values = array();
    $values['email'] = $_SESSION['USER']->email;
    $values['code'] = $_POST['code'];

    $query = "SELECT * FROM verify_t where code = :code && email = :email";
    $result = run_database($query, $values);
    if (is_array($result)) {
        $result = $result[0];
        $time = time();

        if ($result->expires > $time) {
            $email = $result->email;
            $query = "UPDATE user_t SET verified = 1 where email = '$email'  limit 1";
            $result = run_database($query);

            // $_SESSION['USER'] = $result;
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