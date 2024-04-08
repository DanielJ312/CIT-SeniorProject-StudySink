//////////* University.js - Contains functions for the university pages */////////
$(document).ready(function () {
    var currentPath = window.location.pathname;
    if (currentPath.includes('/university/')) {
        $('.post-sort-container').html(updatePostSort("post-" + (subjectID == 0 ? "newest" : "popular")));
    }
    if (currentPath.includes('/university/') && subjectID != 0) {
        $('.study-set-sort-container').html(updateStudySetSort("study-set-popular"));
    }
});

// Event handler for select change
$('.post-sort').on('change', function () {
    var sortType = $(this).val();
    console.log("worked");
    updatePostSort(sortType);
    
});

$('.study-set-sort').on('change', function () {
    var sortType = $(this).val();
    console.log("worked");
    updateStudySetSort(sortType);
    
});

// Function to update the sorted data
function updatePostSort(sortType) {
    console.log("worked 2");
    console.log(subjectID);
    $.ajax({
        url: '/functions/university-functions',
        type: 'POST',
        data: { function: "post-sort", universityID: universityID, subjectID: subjectID, sortType: sortType },
        success: function (response) {
            $('.post-sort-container').html(response);
            shortenPostTitles();
        },
    });
}

function updateStudySetSort(sortType) {
    console.log("worked 4");
    console.log(subjectID);
    $.ajax({
        url: '/functions/university-functions',
        type: 'POST',
        data: { function: "study-set-sort", universityID: universityID, subjectID: subjectID, sortType: sortType },
        success: function (response) {
            $('.study-set-sort-container').html(response);
            // console.log(response);
        },
    });
}


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
function shortenPostTitles() {
    var gridItems = document.querySelectorAll('.post-content');
    gridItems.forEach(function(item) {
        var text = item.textContent;
        if (text.length > 50) {
            item.textContent = text.substring(0, 50) + '...';
        }
    });
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