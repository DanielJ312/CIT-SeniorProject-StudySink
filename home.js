//////////* Home.js - Javascript functions for the home page */////////
// Event listener for the post tiles so it goes to the post page when clicking on the tile
let postTiles = document.querySelectorAll('.PostLinkTile');
postTiles.forEach(tile => {
    tile.addEventListener('click', function() {
        window.location.href = "/posts/" + this.dataset.id;
    });
});

// Event listener for the Study Set tiles so it goes to the study set page when clicking on the tile
let studySetTiles = document.querySelectorAll('.StudySetLinkTile');
studySetTiles.forEach(tile => {
    tile.addEventListener('click', function() {
        window.location.href = "/study-sets/" + this.dataset.id;
    });
});

// Truncate the post content if it's too long
// Select all post content
var contents = document.querySelectorAll('.post-content');
// Loop through each post content
contents.forEach(function(content) {
    // Check if the content is longer than 50 characters
    if (content.textContent.length > 50) {
        // If it is, truncate it to 50 characters and add an ellipsis
        content.textContent = content.textContent.substring(0, 50) + '...';
    }
});

// Same for the 3 below
var postTitles = document.querySelectorAll('.post-title');
postTitles.forEach(function(title) {
    if (title.textContent.length > 80) {
        title.textContent = title.textContent.substring(0, 50) + '...';
    }
});

var descriptions = document.querySelectorAll('.study-set-description');
descriptions.forEach(function(description) {
    if (description.textContent.length > 50) {
        description.textContent = description.textContent.substring(0, 50) + '...';
    }
});

var titles = document.querySelectorAll('.study-set-title');
titles.forEach(function(title) {
    if (title.textContent.length > 80) {
        title.textContent = title.textContent.substring(0, 50) + '...';
    }
});