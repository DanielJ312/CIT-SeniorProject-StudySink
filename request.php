<?php 
$pageTitle = "Request";
include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php");

$errors = array();
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $errors = submit_form($_POST);

    if (count($errors) == 0) {
        // REDIREC TO SUCCESS PAGE
    }
}

if (!check_login()) {
   header("Location: /account/login.php");
}

?>

<h2><?=isset($pageTitle) ? $pageTitle : "Page Header" ?></h2>
<div>
    <div>
        <?php display_errors($errors); ?>
    </div>
    <form method="post">
        <p>Username: <?=$_SESSION['USER']->username?></p>
        <p>Email: <?=$_SESSION['USER']->email?></p>
        <p>University: <input type="text" name="university"></p>
        <p>Message: <textarea name="message" rows="5" cols="40"></textarea></p>
        <input type="submit">
    </form>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>

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
        $values['requestID'] = rand(10000, 99999);
        $values['email'] = $_SESSION['USER']->email;
        $values['university'] = $data['university'];
        $values['message'] = $data['message'];

        // DATABASE INSERTION TEMPORARILY DISABLED
        // $query = "INSERT INTO request_t (requestID, email, university, subjects) VALUES (:requestID, :email, :university, :subjects)";
        // run_database($query, $values);
        
        $subject = "Request ID: ". $values['requestID'];
        $message = <<<message
        <p><b>Request #</b>: {$values['requestID']}</p>
        <p><b>Username</b>: {$_SESSION['USER']->username}</p>
        <p><b>University</b>: {$values['university']}</p>
        <p><b>Message</b>: {$values['message']}</p>
        message;

       $recipient = "StudySinkLLC@gmail.com";
       send_mail($recipient, $subject, $message);
        
    }

    return $errors;
}
?>