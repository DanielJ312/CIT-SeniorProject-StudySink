// Forum.js - Runs any javascript function for the forum
function DeleteComment(commentIDToDelete) {
    $.ajax({
        url: '/functions/forum-functions.php', 
        type: "post",    //request type,
        dataType: 'json',
        data: {function: "delete", commentID: commentIDToDelete, action: "delete"},
    });
    $("#comment-" + commentIDToDelete).remove();
}

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