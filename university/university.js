//////////*  University Index Page Functions *//////////
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

//////////*  University Page Functions *//////////
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


// JavaScript code
function search_subject() {
    let input = document.getElementById('searchbar').value.toLowerCase();
    let subjects = document.querySelectorAll('.subject-selection-c .subjects a');

    subjects.forEach(subject => {
        let subjectText = subject.textContent.toLowerCase();
        let subjectElement = subject.parentElement;

        if (!subjectText.includes(input)) {
            subjectElement.style.display = "none";
        } else {
            subjectElement.style.display = "block";
        }

        if (!input) {
            subjectElement.style.display = "block";
        }
    });
}

function search_subject_mobile() {
    let input = document.getElementById('searchbar2').value.toLowerCase();
    let subjects = document.querySelectorAll('.subject-selection-c .subjects a');

    subjects.forEach(subject => {
        let subjectText = subject.textContent.toLowerCase();
        let subjectElement = subject.parentElement;

        if (!subjectText.includes(input)) {
            subjectElement.style.display = "none";
        } else {
            subjectElement.style.display = "block";
        }

        if (!input) {
            subjectElement.style.display = "block";
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


