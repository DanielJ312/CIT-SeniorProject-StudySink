//////////* Header.js - Contains functions used for the header/navbar *//////////
// Functions for profile picture menu
document.addEventListener("DOMContentLoaded", function () {
    // Get profile picture and dropdown elements
    var profilePicture = document.getElementById("profilePicture");
    var profileDropdown = document.getElementById("profileDropdown");

    // Add click event listener to profile picture
    profilePicture.addEventListener("click", function () {
        // Toggle the visibility of the profile dropdown
        profileDropdown.style.display = (profileDropdown.style.display === "block") ? "none" : "block";
    });

    // Close the dropdown when clicking outside of it
    window.addEventListener("click", function (event) {
        if (!event.target.matches("#profilePicture")) {
            profileDropdown.style.display = "none";
        }
    });
});

// functions for create icon menu
document.addEventListener("DOMContentLoaded", function () {
    // Get profile picture and dropdown elements
    var createIcon = document.getElementById("createIcon");
    var createDropdown = document.getElementById("createDropdown");

    // Add click event listener to profile picture
    createIcon.addEventListener("click", function () {
        // Toggle the visibility of the profile dropdown
        createDropdown.style.display = (createDropdown.style.display === "block") ? "none" : "block";
    });

    // Close the dropdown when clicking outside of it
    window.addEventListener("click", function (event) {
        if (!event.target.matches("#createIcon")) {
            createDropdown.style.display = "none";
        }
    });
});


// mobile nav bar scripts
document.addEventListener('DOMContentLoaded', function () {
    var menuIcon = document.getElementById('menuIcon');
    var nav = document.querySelector('nav');

    // Toggle the visibility of the navigation menu
    menuIcon.addEventListener('click', function (event) {
        // Prevent the click event from propagating to the document
        event.stopPropagation();

        nav.style.display = (nav.style.display === 'none' || nav.style.display === '') ? 'flex' : 'none';
    });

    var dropdowns = document.querySelectorAll('.dropdown');
    dropdowns.forEach(function (dropdown) {
        dropdown.addEventListener('click', function () {
            dropdown.classList.toggle('active');
        });
    });

    // Close the menu when clicking outside of it
    document.addEventListener('click', function (event) {
        if (!event.target.closest('nav') && nav.style.display === 'flex') {
            nav.style.display = 'none';
        }
    });
});


// Search bar functions
document.addEventListener("DOMContentLoaded", function () {
    var searchForm = document.getElementById("search-form");
    searchForm.addEventListener("submit", function (event) {
        event.preventDefault();
        var query = document.getElementById("searchBar").value;
        window.location.href = '/results.php?search=' + encodeURIComponent(query);
    });
});


// Functions for Universty and Subject Dropdowns for create post pop-up
document.addEventListener('DOMContentLoaded', function () {
    var universityPostSelect = document.getElementById('setPostUniversity');
    var subjectPostSelect = document.getElementById('setPostSubject');

    // Listens to the change event on the university input
    universityPostSelect.addEventListener('change', function () {
        var universityId = this.value;
        fetchSubjectsForPostUniversity(universityId);
        // Clear subject selects
        subjectPostSelect.innerHTML = '<option value=""></option>';
        console.log(universityId);
    });

    // Fetches subjects based on University Selection
    function fetchSubjectsForPostUniversity(universityId) {
        fetch('/includes/get-subjects.php?universityId=' + universityId)
            .then(response => response.json())
            .then(subjects => {
                // Update the subject options
                updatePostSubjectOptions(subjects);
            })
            .catch(error => console.error('Error:', error));
    }

    // Updates the subject options
    function updatePostSubjectOptions(subjects) {
        var subjectSelect = document.getElementById('setPostSubject');
        subjectSelect.innerHTML = '<option value=""></option>'; // Clear existing options

        subjects.forEach(function (postSubject) {
            var option = document.createElement('option');
            option.value = postSubject.SubjectID;
            option.textContent = postSubject.Name;
            subjectSelect.appendChild(option);
        });
    }

});