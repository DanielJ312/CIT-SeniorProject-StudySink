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


// async function sortType() {
// $(document).ready(function() {
/*
$(".sort").on("change", function(){
    var type = $(this).val();
    console.log(type);
    $.ajax({
        url: '/forum/index.php',
        type: 'POST',
        data: {sortType: type},
        success: function(response) {
            // Handle the response, which may contain the sorted data
            // Update your page with the sorted data
            $("#result").html(response);
        },
    });
});
*/

// });
// }
// sortType();


// $("select option").filter(function() {
//     //may want to use $.trim in here
//     return $(this).text() == text;
//   }).val('selected');


// const selectElement = document.querySelector(".sort");
// selectElement.addEventListener('change', (event) => {
//     // Get the selected option's value
//     const selectedValue = selectElement.value;
//     console.log(selectedValue);
// });

// $(document).ready(function() {
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
