<?php 
$pageTitle = "Profile";
include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php");
update_session($_SESSION['USER']);

if (!isset($_SESSION['LOGGED_IN'])) {
    header("Location: /account/login.php");
}

// check_login();
?>

<h2><?=isset($pageTitle) ? $pageTitle : "Page Header" ?></h2>
<div>
   <div> 
        <p><?=check_login() ? "Hello " . $_SESSION['USER']->username . ". You are logged in." : "" ?></p>
    </div>
    <div>
        <?php if (check_verification() == false):?>
            <p>Your account is not verified, press the button below to verify it.</p>
            <a href="verify.php"><button>Verify Profile</button></a>
        <?php endif;?>
        <h4>Account Information</h4>
        <p>UserID: <?=$_SESSION['USER']->userid?></p>
        <p>Username: <?=$_SESSION['USER']->username?></p>
        <p>Email: <?=$_SESSION['USER']->email?></p>
        <p>Password: <?=$_SESSION['USER']->password?></p>
        <p>Verified: <?=$_SESSION['USER']->verified == 1 ? "Yes" : "No"?></p>
        <p>Account Created: <?= display_time($_SESSION['USER']->created, "Y-m-d h:i:s A"); ?></p>
        <p>Account Created: <?= display_time($_SESSION['USER']->created, "F j, Y"); ?></p>
        
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>