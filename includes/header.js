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