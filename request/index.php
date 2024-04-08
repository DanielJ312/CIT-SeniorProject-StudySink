<?php
//////////* Request - Form for users to request a new university or subject to be added */////////
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/mail-functions.php");

$errors = array();
$errors = $_SERVER['REQUEST_METHOD'] == "POST" ? submit_form($_POST) : [];
$pageTitle = "Request";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
    <link rel="stylesheet" href="/styles/request/request.css">
    <script defer src="/request/request.js"></script>
</head>
<header>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
</header>
<body class="requestBody" id="requestBody">
    <main class="main-request">
        <div class="requestcontainer">
            <h1 class="requesttitle" id="pageTitle">Request Website Support</h1>
            <label class="switch-label">
                <span style="color: black;">Request Support</span>
                <div class="switch">
                    <input class="requestinput" type="checkbox" id="toggleSwitch" onchange="toggleDescription()">
                    <div class="slider round"></div>
                </div>
                <span style="color: black;">Request University</span>
            </label>
            <form id="helpForm" method="post">

                <label class="requestlabel" for="email">Email</label>
                <input class="requestinput" type="email" id="email" name="email" required>

                <div id="descriptionSection">
                    <label class="requestlabel" for="description">What can we help with?</label>
                    <textarea class="requesttextarea" id="description" name="description" rows="14" style="resize: none;" required></textarea>
                </div>

                <button class="requestbutton" type="submit">Submit</button>
            </form>
        </div>
        <script src="request.js"></script>
        <script>
            function toggleDescription() {
                var toggleSwitch = document.getElementById('toggleSwitch');
                var descriptionSection = document.getElementById('descriptionSection');

                // If "Request University" is selected, hide the description section
                if (toggleSwitch.checked) {
                    descriptionSection.style.display = 'none';
                    document.getElementById('description').removeAttribute('required');
                } else {
                    // If "Request Support" is selected, show the description section
                    descriptionSection.style.display = 'block';
                    document.getElementById('description').setAttribute('required', '');
                }
            }
        </script>
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>
</html>

<?php
function submit_form($data) {
    $errors = array();

    if (empty($data['email'])) {
        $errors[] = "Please enter an email.";
    }

    if (count($errors) == 0) {
        $values['Email'] = $data['email'] ?? null;
        $values['University'] = $data['universityName'] ?? null;
        $values['Address'] = $data['universityAddress'] ?? null;
        $values['OtherInfo'] = $data['otherInfo'] ?? null;
        $values['Message'] = $data['description'] ?? null;
        $IDnum = rand(10000, 9999999);

        if ($values['Message'] != null) {
            $values['RequestID'] = "H$IDnum";
            $subject = "Help Request - ID: " . $values['RequestID'];
            $message = <<<message
          <p><b>Request #</b>: {$values['RequestID']}</p>
          <p><b>Username</b>: {$_SESSION['USER']->Username}</p>
          <p><b>Email Provided</b>: {$values['Email']}</p>
          <p><b>Message</b>: {$values['Message']}</p>
          message;
        } else {
            $values['RequestID'] = "REQ$IDnum";
            $subject = "University Request - ID: " . $values['RequestID'];
            $message = <<<message
          <p><b>Request #</b>: {$values['RequestID']}</p>
          <p><b>Username</b>: {$_SESSION['USER']->Username}</p>
          <p><b>Email Provided</b>: {$values['Email']}</p>
          <p><b>University</b>: {$values['University']}</p>
          <p><b>University Address</b>: {$values['Address']}</p>
          <p><b>Other Information</b>: {$values['OtherInfo']}</p>
          message;
        }
        $recipient = "StudySinkLLC@gmail.com";
        send_mail($recipient, $subject, $message);

        $_SESSION['REQUEST'] = $values;
        header("Location: /request/confirmation.php");
    }

    return $errors;
}
?>