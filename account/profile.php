<?php
$pageTitle = "Profile";
include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php");
update_session($_SESSION['USER']);

if (!isset($_SESSION['LOGGED_IN'])) {
    header("Location: /account/login.php");
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $errors = upload_avatar($_FILES);
}

// check_login();
?>

<h2><?= isset($pageTitle) ? $pageTitle : "Page Header" ?></h2>
<div>
    <div>
        <p><?= check_login() ? "Hello <b>" . $_SESSION['USER']->username . "</b>. You are logged in." : "" ?></p>
    </div>
    <div>
        <h4>Account Verification</h4>
        <?php if (check_verification() == false) : ?>
            <p>Your account is not verified, press the button below to verify it.</p>
            <a href="verify.php"><button>Verify Profile</button></a>
        <?php else: ?>
            <p>Your account is verified.</p>    
        <?php endif; ?>
    </div>
    <div>
        <h4>Upload Avatar</h4>
        <form method="post" enctype="multipart/form-data">
            <label for="image">Select Image:</label>
            <input type="file" name="image" id="image" accept="image/*" required="">
            <button type="submit">Upload</button>
        </form>
    </div>
    <div>
        <h4>Account Information</h4>
        <p>UserID: <?= $_SESSION['USER']->userid ?></p>
        <p>Username: <?= $_SESSION['USER']->username ?></p>
        <p>Email: <?= $_SESSION['USER']->email ?></p>
        <p>Password: <?= $_SESSION['USER']->password ?></p>
        <p>Verified: <?= $_SESSION['USER']->verified == 1 ? "Yes" : "No" ?></p>
        <p>Account Created: <?= display_time($_SESSION['USER']->created, "F j, Y @ h:i:s A"); ?></p>
        <p>Avatar: <?= $_SESSION['USER']->avatar ?></p>
        <img src="<?= $_SESSION['USER']->avatar ?>">
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>