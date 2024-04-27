<?php
//////////* Request - Form for users to request a new university or subject to be added */////////
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/mail-functions.php");
$_SERVER['REQUEST_METHOD'] == "POST" ? submit_request($_POST) : [];
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
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/to-top.php"); ?>
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