// Forum.js - Runs any javascript function for the forum

/*  Sorting Functions */
$( document ).ready(function() {
    var currentPath = window.location.pathname;
    if (currentPath.startsWith('/forum/post')) {
        $('.sort-container').html(updateSortedData("comment-oldest"));
    }
});

$( document ).ready(function() {
    var currentPath = window.location.pathname;
    if (currentPath.startsWith('/forum/')) {
        $('.sort-container').html(updateSortedData("post-oldest"));
    }
});

// Event handler for select change
$('.sort').on('change', function() {
    var sortType = $(this).val(); // Get the selected value
    updateSortedData(sortType); // Call the function to update the sorted data
    console.log(sortType);
});

// Function to update the sorted data
function updateSortedData(sortType) {
    console.log(postID);
    $.ajax({
        url: '/functions/forum-functions.php', 
        type: 'POST',
        data: { function: "sort", postID: postID, sortType: sortType },
        success: function(response) {
            $('.sort-container').html(response); // Update the container with sorted data
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}

/*  Comment Functions */
function DeleteComment(commentIDToDelete) {
    $.ajax({
        url: '/functions/forum-functions.php', 
        type: "post",    //request type,
        dataType: 'json',
        data: {function: "delete", commentID: commentIDToDelete},
    });
    $("#comment-" + commentIDToDelete).remove(); 
    var total = $(".comment-total");
    total.text(Number(total.text())-1);
}

function updateCommentVote(commentID, userID, voteType) {
    // console.log(commentID, userID, voteType);
    $.ajax({
        url: '/functions/forum-functions.php',  
        type: 'POST',
        data: { function: "update-vote", commentID: commentID, userID: userID, voteType: voteType},
        success: function(response) {
            $("#comment-" + commentID + "-v").html(response);
            
            type = voteType == 1 ? "down" : "up";
            // newButton = `<input id="comment-${commentID}-${type}vote" type="button" value="${type}vote" onclick="updateCommentVote(${commentID}, ${userID}, '${-voteType}')">`

            newButton = `<a class="far fa-thumbs-${type}" id="comment-${commentID}-${type}vote" type="button" value="${type}vote" onclick="updateCommentVote(${commentID}, ${userID}, '${-voteType}')">`;

            $("#comment-" + commentID + "-vb").html(newButton);
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}

//Dropdown Functions
function toggleDropdown(dropdown) {
    dropdown.querySelector('.dropdown-content').classList.toggle('show');
}

// Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
    if (!event.target.matches('.ellipsis-icon')) {
        var dropdowns = document.getElementsByClassName('dropdown-content');
        var i;
        for (i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
}