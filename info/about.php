<?php
//////////* About Page - Displays information about the website *//////////
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
$pageTitle = "About";
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
        <h2>About Us</h2>
        <p>
            Welcome to our Study Sink!&nbsp; Our website is designed to be a hub where students can connect, study, and engage with their university community.&nbsp; Drawing from the knowledge and skills acquired through our courses at CSUN our group has utilized technologies such as AWS, HTML, CSS, and project management techniques to bring this platform to life.&nbsp;

            Our primary mission is to empower students by providing a space where they can collaborate, share insights, and support each other's learning journeys.&nbsp; Through features like commenting on posts and creating study sets, we aim to enhance the academic experience and foster a sense of community among users.&nbsp;

            During the development process, we encountered various challenges, including technical issues like bugs that temporarily disrupted site functionality.&nbsp; However, through perseverance and continuous learning, we overcame these obstacles and improved the platform's performance.&nbsp;

            What sets us apart is our holistic approach, combining study resources with social interaction within a single platform.&nbsp; While there are many study aids and social media forums available, our website seamlessly integrates both aspects, offering a comprehensive solution for students.&nbsp;

            Looking ahead, we envision our project becoming the go-to study site for college students nationwide.&nbsp; As more users join and contribute, we anticipate creating unique university pages tailored to each institution's needs and culture.&nbsp;

            We extend our heartfelt gratitude to everyone who has supported us throughout this journey, including our team members, instructors, and advisors.&nbsp; Your encouragement and guidance have been invaluable in shaping our project.&nbsp;

            To learn more about our platform or get involved, please visit our website and reach out to us through the request page.&nbsp; Thank you for being part of our community, were there is always a steady flow of knowledge!&nbsp;
        </p>
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>
</html>