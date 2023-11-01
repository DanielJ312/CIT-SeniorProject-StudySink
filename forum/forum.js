// Forum.js - Runs any javascript function for the forum
function DeleteComment(commentIDToDelete) {
    $.ajax({
        url:"post-template.php",    //the page containing php script
        type: "post",    //request type,
        dataType: 'json',
        data: {commentID: commentIDToDelete, action: "delete"},
    });
    $(".comment-" + commentIDToDelete).remove();
}

function TestFunction(test) {
    console.log(test);
}

// Function to update the sorted data
function updateSortedData(sortType) {
    $.ajax({
        url: 'sort.php', 
        type: 'POST',
        data: { sortType: sortType },
        success: function(response) {
            $('.forum-posts').html(response); // Update the container with sorted data
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}

// Event handler for select change
$('.sort').on('change', function() {
    var sortType = $(this).val(); // Get the selected value
    updateSortedData(sortType); // Call the function to update the sorted data
    console.log(sortType);
});
// });
