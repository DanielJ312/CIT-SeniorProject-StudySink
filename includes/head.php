<!-- Head - Contains HTML injected into the head tag -->
<!-- Main -->
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= isset($pageTitle) ? $pageTitle : "" ?></title>
<link rel="stylesheet" href="/styles/global.css">
<link rel="icon" type="image/x-icon" href="https://studysink.s3.amazonaws.com/assets/StudySinkIcon.ico">

<!-- Libraries -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://kit.fontawesome.com/f0dbd56a8f.js" crossorigin="anonymous"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@700&display=swap" rel="stylesheet">

<!-- Navbar -->
<link rel="stylesheet" href="/styles/includes/header.css"> 
<script defer src="/includes/header.js"></script>

<!-- Create Forum Post Pop up -->
<link rel="stylesheet" href="/styles/forum/post-create.css"> 
<script defer src="/includes/forum-popup.js"></script>