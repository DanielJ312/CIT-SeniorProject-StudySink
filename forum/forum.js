// Forum.js - Runs any javascript function for the forum

/*  Sorting Functions */
$(document).ready(function () {
    var currentPath = window.location.pathname;
    if (currentPath.includes('/forum/post')) {
        $('.sort-container').html(updateSortedData("comment-oldest"));
    }
    if (currentPath.includes('/study-sets/')) {
        $('.sort-container').html(updateSortedData("comment-oldest"));
    }
});

// Event handler for select change
$('.sort').on('change', function () {
    var sortType = $(this).val();
    updateSortedData(sortType); 
});

// Function to update the sorted data
function updateSortedData(sortType) {
    $.ajax({
        url: '/functions/forum-functions',
        type: 'POST',
        data: { function: "sort", parentID: parentID, sortType: sortType },
        success: function (response) {
            $('.sort-container').html(response);
        },
    });
}

/*  Comment Functions */
function AddComment() {
    content = $('.commentInput').val();
    if (content.length > 0) {
        $.ajax({
            url: '/functions/forum-functions',
            type: 'POST',
            data: { function: "add", parentID: parentID, content: content },
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
    var cancel = $("<button>").attr({
        type: "submit",
        class: "addComment",
        onclick: `CancelEdit(${commentID})`
    }).text("Cancel");
    var save = $("<button>").attr({
        type: "submit",
        class: "addComment",
        onclick: `EditComment(${commentID})`
    }).text("Save");

    div.append(input, cancel, save);
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


                $(`#comment-${commentID} .edited`).html("edited now");
            },
        });
    }
    else {
        console.log("empty comment");
    }
}

function CancelEdit(commentID) {
    $(`#comment-${commentID}-c .edit-bar`).remove();
    $(`#comment-${commentID}-c p`).toggle();
    $(`#comment-${commentID} .dropdown`).toggle();
}

function ReportComment(commentID) {
    $.ajax({
        url: '/functions/forum-functions',
        type: 'POST',
        data: { function: "report", commentID: commentID },
        success: function (response) {
            $(`#comment-${commentID} .report`).html("Reported!");
        }
    });
    console.log('Reached1');
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

function handleKeyPress(event) {
        // Check if the pressed key is Enter (key code 13)
        if (event.keyCode === 13) {
            // Call the AddComment function
            AddComment();
        }
    }

    // Character Counter for Post Title textarea and Post Content textarea
function commentcountChar(commnetinput) {
    const maxLength = 2500;
    const currentLength = commentinput.value.length;
    const remainingChars = Math.max(maxLength - currentLength, 0);

    if (currentLength > maxLength) {
      commentinput.value = commentinput.value.substring(0, maxLength);
    }

    if (currentLength == 0) {
        document.getElementById('commentcharCount').innerText = ``;
      }
    else{
    document.getElementById('commentcharCount').innerText = `${remainingChars}`;
    }
}