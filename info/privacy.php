<?php 
//////////* Privacy Page - Displays privacy policy about the website *//////////
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
$pageTitle = "Privacy Policy";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
    <link rel="stylesheet" type="text/css" href="/styles/info/privacy-policy.css">
</head>
<body>
    <header>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
    </header>
    <main>
        <section>
            <h2>Privacy Policy</h2>
            <p>This Privacy Policy explains how we collect, use, and protect your personal information on our website. By using our website, you consent to the practices described in this Privacy Policy.</p>
        </section>
        <section>
            <h2>Personal Information Collection</h2>
            <p>We collect your email address solely for the purpose of account functionality.</p>
        </section>
        <section>
            <h2>Purpose</h2>
            <p>The purpose of collecting your email address is for account creation and functionality.</p>
        </section>
        <section>
            <h2>Data Usage</h2>
            <p>Your email address is used solely for the purpose of creating and maintaining your account on our website.</p>
        </section>
        <section>
            <h2>Data Sharing</h2>
            <p>We do not share your personal information with any third parties.</p>
        </section>
        <section>
            <h2>Data Protection Measures</h2>
            <p>We employ industry-standard data protection measures, including encrypted SSL/TLS certificates using HTTPS for secure communication and database encryption using Amazon Web Services to safeguard your personal information.</p>
        </section>
        <section>
            <h2>Cookies</h2>
            <p>We use functional cookies to enhance your browsing experience. These cookies collect information about the Study Sets and Posts you have recently viewed. We only store the last 5 ID numbers of both for functional purposes.</p>
        </section>
        <section>
            <h2>User Rights</h2>
            >You have the right to delete your account and personal information at any time. Please contact us if you wish to exercise this right.</p>
        </section>
        <section>
            <h2>International Data Transfers</h2>
            <p>We do not transfer your personal data outside of the jurisdiction in which you reside.</p>
        </section>
        <section>
            <h2>Updates to the Privacy Policy</h2>
            <p>As of 2024, we have no plans to update this Privacy Policy. However, any changes made in the future will be reflected here.</p>
        </section>
        <section>
            <h2>Compliance</h2>
            <p>While we strive to adhere to best practices in privacy protection, we are not aware of any specific compliance requirements with official privacy laws or regulations.</p>
        </section>
        <section>
            <p style="font-family: 'Comfortaa', sans-serif; margin-top: 3%;">For any questions or concerns regarding this Privacy Policy, please contact us by using our Contact Support page found at the bottom of the page.</p>
        </section>
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>
</html>