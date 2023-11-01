<!-- Request - Form for users to request a new university or subject to be added -->
<?php
require($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
update_session();
check_login() ? null : header("Location: /account/login.php");
$pageTitle = "Request";

$errors = array();
$errors = $_SERVER['REQUEST_METHOD'] == "POST" ? submit_form($_POST) : [];
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
            <p>Username: <?= $_SESSION['USER']->Username ?></p>
            <p>Email: <?= $_SESSION['USER']->Email ?></p>
            <p>University: <input type="text" name="university"></p>
            <p>Message: <textarea name="message" rows="5" cols="40"></textarea></p>
            <input type="submit">
        </form>
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>
</html>

<?php
function submit_form($data) {
    $errors = array();

    if (empty($data['university'])) {
        $errors[] = "Please enter a university name.";
    }
    if (empty($data['message'])) {
        $errors[] = "Please enter at least one subject.";
    }

    if (count($errors) == 0) {
        $values['RequestID'] = rand(10000, 99999);
        $values['Email'] = $_SESSION['USER']->email;
        $values['University'] = $data['university'];
        $values['Message'] = $data['message'];

        // DATABASE INSERTION TEMPORARILY DISABLED
        // $query = "INSERT INTO REQUEST_T (RequestID, Email, University, Message) VALUES (:RequestID, :Email, :University, :Subjects)";
        // run_database($query, $values);

        $subject = "Request ID: " . $values['RequestID'];
        $message = <<<message
        <p><b>Request #</b>: {$values['RequestID']}</p>
        <p><b>Username</b>: {$_SESSION['USER']->username}</p>
        <p><b>University</b>: {$values['University']}</p>
        <p><b>Message</b>: {$values['Message']}</p>
        message;

        $recipient = "StudySinkLLC@gmail.com";
        send_mail($recipient, $subject, $message);
    }

    return $errors;
}
?>