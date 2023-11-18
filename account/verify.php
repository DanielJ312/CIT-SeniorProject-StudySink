<!-- Verify - User recieves email with verifcation code to verify their account -->
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/mail-functions.php");
if (!check_login()) header("Location: /account/login.php"); 
update_session();
$pageTitle = "Verify Account";

if ($_SERVER['REQUEST_METHOD'] == "GET" && !check_verification()) $expiredCode = is_code_active("verify", $_SESSION['USER']->Email);
if ($_SERVER['REQUEST_METHOD'] == "POST") $errors = verify_account();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
</head>
<body>
    <header>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
        <h2><?=isset($pageTitle) ? $pageTitle : "Page Header" ?></h2>
    </header>
    <main>    
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
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>
</html>

<?php 
function verify_account() {
    $values = [
        'Email' => $_SESSION['USER']->Email,
        'Code' => $_POST['code']
    ];

    $query = "SELECT * FROM CODE_T where Email = :Email && Code = :Code;";
    $result = run_database($query, $values);
    if (is_array($result)) {
        $result = $result[0];

        if ($result->Expires > get_local_time()) {
            $email = $result->Email;
            $query = "UPDATE USER_T SET Verified = 1 WHERE Email = '$email' LIMIT 1;";
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