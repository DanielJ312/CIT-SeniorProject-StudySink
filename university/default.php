<!-- Post Template - Displays post for given Post ID  -->

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
    <!--<link rel="stylesheet" href="/styles/university/dark-mode.css" id="dark-theme"/>-->
    <link rel="stylesheet" href="/styles/university/university.css"/>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var gridItems = document.querySelectorAll('.post-content');
            gridItems.forEach(function(item) {
                var text = item.textContent;
                if (text.length > 50) {
                    item.textContent = text.substring(0, 50) + '...';
                }
            });
        });

        // Function to toggle dark mode
        function toggleDarkMode(event) {
    event.preventDefault(); // Prevent the default action

    const body = document.body;
    body.classList.toggle('dark-mode');

    // Save the current theme preference to localStorage
    const isDarkMode = body.classList.contains('dark-mode');
    localStorage.setItem('darkMode', isDarkMode);

    // Toggle the active theme stylesheet
    const lightTheme = document.getElementById('light-theme');
    const darkTheme = document.getElementById('dark-theme');
    lightTheme.disabled = isDarkMode;
    darkTheme.disabled = !isDarkMode;
}

        // Check the user's theme preference from localStorage
        const savedDarkMode = localStorage.getItem('darkMode');
        if (savedDarkMode === 'true') {
            document.body.classList.add('dark-mode');
            toggleDarkMode(); // Toggle the active theme stylesheet
        }


        function search_university() {
    let input = document.getElementById('searchbar').value.toLowerCase();
    let universities = document.querySelectorAll('.tiles a');

    universities.forEach(university => {
        let universityName = university.querySelector('.word').textContent.toLowerCase();
        let universityElement = university;

        if (!universityName.includes(input)) {
            universityElement.style.display = "none";
        } else {
            universityElement.style.display = "block"; 
        }

        if (!input) {
            universityElement.style.display = "block";
        } 
    });
}



        function search_university_mobile() {
    let input = document.getElementById('searchbar2').value.toLowerCase();
    let universities = document.querySelectorAll('.tiles a');

    universities.forEach(university => {
        let universityName = university.querySelector('.word').textContent.toLowerCase();
        let universityElement = university;

        if (!universityName.includes(input)) {
            universityElement.style.display = "none";
        } else {
            universityElement.style.display = "block"; 
        }

        if (!input) {
            universityElement.style.display = "block";
        } 
    });
}

function toggleSubject() {
        var contentSubject = document.getElementById('contentsubject');
        if (contentSubject.style.display === 'none' || contentSubject.style.display === '') {
            contentSubject.style.display = 'block';
        } else {
            contentSubject.style.display = 'none';
        }
    }


    </script>
</head>
<body>
    <header>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/to-top.php"); ?>
    </header>
    <main>
    <?php if (!check_login()) : ?>
        <div class="margin">
            <div class="university-info">
                <h2>Universitys</h2>
            </div>
                <div class="outer-box">
                    <div class="search-bar-university">
                        <input id="searchbar" type="text" name="search" onkeyup="search_university()" placeholder="Search Universitys..." />
                        <button><i class="fas fa-search"></i></button>
                    </div>
                    <div class="tiles">
                        <?php foreach ($universitiesforum as $universityforum) : ?>
                        <a class="names" href="/university/index.php">
                            <div class="word"><?= htmlspecialchars($universityforum->Name) ?></div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
        <?php endif; ?>
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>
</html>

<div class= mobileuniversitydefault>
<?php if (!check_login()) : ?>
        <div class="mobilemargin">
            <div class="university-info">
                <h2>Universitys</h2>
            </div>
                <div class="outer-box">
                    <div class="search-bar-university">
                        <input id="searchbar2" type="text" name="search" onkeyup="search_university_mobile()" placeholder="Search Universitys..." />
                        <button><i class="fas fa-search"></i></button>
                    </div>
                    <div class="tiles">
                        <?php foreach ($universitiesforum as $universityforum) : ?>
                        <a class="names" href="/university/index.php">
                            <div class="word"><?= htmlspecialchars($universityforum->Name) ?></div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
        <?php endif; ?>