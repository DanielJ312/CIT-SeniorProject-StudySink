<!DOCTYPE html>
<html lang="en">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
</head>
<header>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
</header>
<body class="confirmation-body">
  <div class="confirmation-container">
    <div class="confirmation-message">Thank you for contacting us!</div>

    <div class="confirmation-container">
      <div class="confirmation-label">Request ID:</div>
      <div class="confirmation-value">REQ123456 PHP value</div>

      <div class="confirmation-label">Username:</div>
      <div class="confirmation-value">USERNAME PHP value</div>

      <div class="confirmation-label">Provided Name:</div>
      <div class="confirmation-value">TEST name PHP value</div>

      <div class="confirmation-label">Provided Email:</div>
      <div class="confirmation-value">Example Email PHP value</div>

      <div class="confirmation-label">Message:</div>
      <div class="confirmation-value">Example message PHP value</div>
    </div>

    <div class="confirmation-message" style="margin-top: 3%;">We will get back to you within 2-5 business days.</div>

    <a href="/request/index.php" class="confirmation-return-button">Return to the Help page</a>
  </div>
</body>
</html>