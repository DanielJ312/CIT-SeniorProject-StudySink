// Forum.js - Runs any javascript function for the forum
function DeleteComment(commentIDToDelete) {
    $.ajax({
        url:"template.php",    //the page containing php script
        type: "post",    //request type,
        dataType: 'json',
        data: {commentID: commentIDToDelete, action: "delete"},
    });
    $("#comment-" + commentIDToDelete).remove();
}

function TestFunction(test) {
    console.log(test);
}

// Event handler for select change
$('.sort').on('change', function() {
    var sortType = $(this).val(); // Get the selected value
    updateSortedData(sortType); // Call the function to update the sorted data
    console.log(sortType);
});

// Function to update the sorted data
function updateSortedData(sortType) {
    $.ajax({
        url: '/functions/forum-functions.php', 
        type: 'POST',
        data: { function: "sort", sortType: sortType },
        success: function(response) {
            $('.forum-posts').html(response); // Update the container with sorted data
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}

function updateCommentVote(commentID, userID, voteType) {
    // console.log(commentID, userID, voteType);
    $.ajax({
        url: '/functions/forum-functions.php',  
        type: 'POST',
        data: { function: "update-vote", commentID: commentID, userID: userID, voteType: voteType},
        success: function(response) {
            $("#comment-" + commentID + "-v").html(response);
            
            type = voteType == 1 ? "Downvote" : "Upvote";
            $("#comment-" + commentID + "-vb").html(`<input id="comment-${commentID}-${type}" type="button" value="${type}" onclick="updateCommentVote(${commentID}, ${userID}, '${-voteType}')">`);
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}