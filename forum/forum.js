// Forum.js - Runs any javascript function for the forum

/*  Sorting Functions */
$(document).ready(function () {
    var currentPath = window.location.pathname;
    if (currentPath.includes('/forum/post')) {
        $('.sort-container').html(updateSortedData("comment-oldest"));
    }
    else if (currentPath.includes('/forum/')) {
        $('.sort-container').html(updateSortedData("post-oldest"));
    }
});

// Event handler for select change
$('.sort').on('change', function () {
    var sortType = $(this).val();
    updateSortedData(sortType); 
    console.log(sortType);
});

// Function to update the sorted data
function updateSortedData(sortType) {
    console.log(postID);
    $.ajax({
        url: '/functions/forum-functions',
        type: 'POST',
        data: { function: "sort", postID: postID, sortType: sortType },
        success: function (response) {
            $('.sort-container').html(response);
        },
        error: function (xhr, status, error) {
            console.error(error);
        }
    });
}

/*  Comment Functions */
function AddComment() {
    console.log(postID);
    content = $('.commentInput').val();
    console.log(content);
    
    if (content.length > 0) {
        console.log("comment has content"); 
        $.ajax({
            url: '/functions/forum-functions',
            type: 'POST',
            data: { function: "add", postID: postID, content: content },
            success: function (response) {
                $('.sort-container').append(response); 
                $('.commentInput').val("");
                var total = $(".comment-total");
                total.text(Number(total.text()) + 1);

                var comment = $('.sort-container .comment').last().attr('id');
                console.log(comment);
                $('html, body').scrollTop($(`#${comment}`).offset().top);
                $(`#${comment}`).css("background-color", "#FEFFB6");
            },
        });
    }
    else {
        console.log("empty comment");
    }
}

function DeleteComment(commentIDToDelete) {
    $.ajax({
        url: '/functions/forum-functions',
        type: "post",
        dataType: 'json',
        data: { function: "delete", commentID: commentIDToDelete },
    });
    $("#comment-" + commentIDToDelete).remove();
    var total = $(".comment-total");
    total.text(Number(total.text()) - 1);
}

function OpenCommentEditor(commentID) {
    content = $(`#comment-${commentID}-c p`).html();
    console.log(content);
    $(`#comment-${commentID}-c p`).toggle();
    var div = $("<div>").addClass("edit-bar");
    var input = $("<input>").attr({
        type: "text",
        class: "commentInput",
        value: content,
        name: "content"
    });
    var button = $("<button>").attr({
        type: "submit",
        class: "addComment",
        onclick: `EditComment(${commentID})`
    }).text("Save");

    div.append(input, button);
    $(`#comment-${commentID}-c`).append(div);
    $(`#comment-${commentID} .dropdown`).toggle();
}   

function EditComment(commentID) {
    content = $(`#comment-${commentID}-c .commentInput`).val();
    
    if (content.length > 0) {
        console.log("comment has content"); 
        $.ajax({
            url: '/functions/forum-functions',
            type: 'POST',
            data: { function: "edit", commentID: commentID, content: content },
            success: function (response) {
                $(`#comment-${commentID}-c p`).html(response);
                $(`#comment-${commentID}-c .edit-bar`).remove();
                $(`#comment-${commentID}-c p`).toggle();
                $(`#comment-${commentID} .dropdown`).toggle();
            },
        });
    }
    else {
        console.log("empty comment");
    }
}

function ReportComment(commentID) {
    $(`#comment-${commentID} .report`).html("Reported!");
    $.ajax({
        url: '/functions/forum-functions',
        type: 'POST',
        data: { function: "report", commentID: commentID},
    });
}

function updateCommentVote(commentID, userID, voteType) {
    // console.log(commentID, userID, voteType);
    $.ajax({
        url: '/functions/forum-functions',
        type: 'POST',
        data: { function: "update-vote", commentID: commentID, userID: userID, voteType: voteType },
        success: function (response) {
            $("#comment-" + commentID + "-v").html(response);

            type = voteType == 1 ? "down" : "up";
            // newButton = `<input id="comment-${commentID}-${type}vote" type="button" value="${type}vote" onclick="updateCommentVote(${commentID}, ${userID}, '${-voteType}')">`

            newButton = `<a class="far fa-thumbs-${type}" id="comment-${commentID}-${type}vote" type="button" value="${type}vote" onclick="updateCommentVote(${commentID}, ${userID}, '${-voteType}')">`;

            $("#comment-" + commentID + "-vb").html(newButton);
        },
        error: function (xhr, status, error) {
            console.error(error);
        }
    });
}

function updateVote(commentID, userID) {
    var check = $(`#comment-${commentID} .like`).hasClass("fa-solid");
    console.log(check);

    $.ajax({
        url: '/functions/forum-functions',
        type: 'POST',
        data: { function: "update-vote", commentID: commentID, userID: userID },
        success: function (response) {
            $("#comment-" + commentID + "-v").html(response);
            $(`#comment-${commentID} .like`).removeClass(check ? "fa-solid" : "fa-regular").addClass(check ? "fa-regular" : "fa-solid");
        }
    });
}

//Dropdown Functions
function toggleDropdown(dropdown) {
    dropdown.querySelector('.dropdown-content').classList.toggle('show');
}

// Close the dropdown if the user clicks outside of it
window.onclick = function (event) {
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