<?php 
$pageTitle = "Request";
include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php");

$errors = array();
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $errors = submit_form($_POST);

    if (count($errors) == 0) {
        // header("Location: profile.php");
        // die;
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
        <p>Title: <input type="text" name="title"><span class="error">*</span></p>
        <p>University: <input type="text" name="university"><span class="error">*</span></p>
        <p>Subjects: <textarea name="subjects" rows="5" cols="40"></textarea><span class="error">*</span></p>
        <input type="submit">
    </form>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>

<?php 
function submit_form($data) {
    $errors = array();

    if (empty($data['title'])) {
        $errors[] = "Please enter a title.";
    }
    if (empty($data['university'])) {
        $errors[] = "Please enter a university name.";
    }
    if (empty($data['subjects'])) {
        $errors[] = "Please enter at least one subject.";
    }

    if (count($errors) == 0) {
        $values['requestID'] = rand(10000, 99999);
        $values['email'] = $_SESSION['USER']->email;
        $values['title'] = $data['title'];
        $values['university'] = $data['university'];
        $values['subjects'] = $data['subjects'];

        $query = "INSERT INTO request_t (requestID, email, title, university, subjects) VALUES (:requestID, :email, :title, :university, :subjects)";
        run_database($query, $values);
        
        $subject = "Request ID: ". $values['requestID'];

        $message = <<<message
        <p><b>Request #</b>: {$values['requestID']}</p>
        <p><b>Username</b>: {$_SESSION['USER']->username}</p>
        <p><b>Title</b>: {$values['title']}</p>
        <p><b>University</b>: {$values['university']}</p>
        <p><b>Subjects</b>: {$values['subjects']}</p>
        message;

       $recipient = "StudySinkLLC@gmail.com";
       send_mail($recipient, $subject, $message);
        
    }

    return $errors;
}
?>