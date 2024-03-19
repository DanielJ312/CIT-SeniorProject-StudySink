$(document).ready(function () {
    var currentPath = window.location.pathname;
    // if (currentPath.includes('/forum/post') || currentPath.includes('/study-sets/')) {
    if (currentPath.includes('/university/')) {
        $('.post-sort-container').html(updatePostSort("post-newest"));
    }
});

// Event handler for select change
$('.sort').on('change', function () {
    var sortType = $(this).val();
    console.log("worked");
    updatePostSort(sortType);
});

// Function to update the sorted data
function updatePostSort(sortType) {
    console.log("worked 2");

    url = window.location.pathname;
    parts = url.split("/");
    university = parts[parts.length - 1];
    $.ajax({
        url: '/functions/university-functions',
        type: 'POST',
        data: { function: "post-sort", university: university, sortType: sortType },
        success: function (response) {
            $('.post-sort-container').html(response);
            // console.log(response);
            var gridItems = document.querySelectorAll('.post-content');
            gridItems.forEach(function (item) {
                var text = item.textContent;
                if (text.length > 50) {
                    item.textContent = text.substring(0, 50) + '...';
                }
            }); 
        },
    });
}