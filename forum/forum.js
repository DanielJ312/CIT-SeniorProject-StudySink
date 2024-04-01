// Forum.js - Runs any javascript function for the forum

//////////* Sorting Functions *//////////
$(document).ready(function () {
    var currentPath = window.location.pathname;
    if (currentPath.includes('/forum/post') || currentPath.includes('/study-sets/')) {
        $('.comment-sort-container').html(updateSortedData("comment-popular"));
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
        data: { function: "comment-sort", parentID: parentID, sortType: sortType },
        success: function (response) {
            $('.comment-sort-container').html(response);
        },
    });
}

//////////* Post Functions *//////////
function DeletePost() {
    var postID = parentID;
    var uni;
    $.ajax({
        url: '/functions/forum-functions',
        type: "post",
        dataType: 'json',
        data: { function: "post-delete", postID: postID },
        complete: function (response) {
            uni = response.responseText;
            window.location.replace(uni === "none" ? "/index.php" : `/university/${uni}.php`);
        },
    });
}


function OpenPostEditor() {
    content = $(`.content`).html();
    height = countLineBreaks(content) + 1;
    height = (height > 2) ? height * 15 : 30;
    content = convertBrToNewline(content);

    $(`.content`).toggle();
    var div = $("<div>").addClass("edit-bar");
    var textarea = $("<textarea>").attr({
        type: "text",
        class: "input-bar",
        name: "content",
        style: `height: ${height}px;`
    }).text(content);
    var cancel = $("<button>").attr({
        type: "submit",
        class: "cancelComment",
        onclick: `CancelPostEdit()`
    }).text("Cancel");
    var save = $("<button>").attr({
        type: "submit",
        class: "addComment",
        onclick: `EditPost()`
    }).text("Save");

    div.append(textarea, cancel, save);
    $(`.post-content`).append(div);
    $(`.post .dropdown`).toggle();
} 

function EditPost() {
    var postID = parentID;
    content = $(`.post .input-bar`).val();
    if (content.length > 0) {
        console.log("comment has content"); 
        $.ajax({
            url: '/functions/forum-functions',
            type: 'POST',
            data: { function: "post-edit", postID: postID, content: content },
            success: function (response) {
                $(`.post-content p`).html(response);
                $(`.post-content .edit-bar`).remove();
                $(`.post-content p`).toggle();
                $(`.post .dropdown`).toggle();

                $(`.post .edited`).html("edited now");
            },
        });
    }
    else {
        console.log("empty comment");
    }
}

function CancelPostEdit(commentID) {
    $(`.post-content .edit-bar`).remove();
    $(`.post-content p`).toggle();
    $(`.post .dropdown`).toggle();
}

function ReportPost() {
    var postID = parentID;
    $.ajax({
        url: '/functions/forum-functions',
        type: 'POST',
        data: { function: "post-report", postID: postID },
        success: function (response) {
            $(`.post .report`).html("Reported!");
        }
    });
}

function updatePostLike() {
    var check = $(".post .like").hasClass("fa-solid");
    var postID = parentID;
    $.ajax({
        url: '/functions/forum-functions',
        type: 'POST',
        data: { function: "post-like", postID: postID },
        success: function (response) {
            $(".post-votes").html(response);
            $(".post .like").removeClass(check ? "fa-solid" : "fa-regular").addClass(check ? "fa-regular" : "fa-solid");
        }
    });

}

//////////* Comment Functions *//////////
function AddComment() {
    content = $('.input-bar').val();
    if (content.length > 0) {
        $.ajax({
            url: '/functions/forum-functions',
            type: 'POST',
            data: { function: "comment-add", parentID: parentID, content: content },
            success: function (response) {
                $('.comment-sort-container').append(response); 
                $('.input-bar').val("");
                var total = $(".comment-total");
                total.text(Number(total.text()) + 1);

                var comment = $('.comment-sort-container .comment').last().attr('id');
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
        data: { function: "comment-delete", commentID: commentIDToDelete },
    });
    $("#comment-" + commentIDToDelete).remove();
    var total = $(".comment-total");
    total.text(Number(total.text()) - 1);
}

function OpenCommentEditor(commentID) {
    content = $(`#comment-${commentID}-c p`).html();
    height = countLineBreaks(content) + 1;
    height = (height > 2) ? height * 15 : 30;
    content = convertBrToNewline(content);
    
    $(`#comment-${commentID}-c p`).toggle();
    var div = $("<div>").addClass("edit-bar");
    var textarea = $("<textarea>").attr({
        type: "text",
        class: "input-bar",
        name: "content",
        style: `height: ${height}px;`
    }).text(content);
    var cancel = $("<button>").attr({
        type: "submit",
        class: "cancelComment",
        onclick: `CancelCommentEdit(${commentID})`
    }).text("Cancel");
    var save = $("<button>").attr({
        type: "submit",
        class: "addComment",
        onclick: `EditComment(${commentID})`
    }).text("Save");

    div.append(textarea, cancel, save);
    $(`#comment-${commentID}-c`).append(div);
    $(`#comment-${commentID} .dropdown`).toggle();
}   

function EditComment(commentID) {
    content = $(`#comment-${commentID}-c .input-bar`).val();
    
    if (content.length > 0) {
        console.log("comment has content"); 
        $.ajax({
            url: '/functions/forum-functions',
            type: 'POST',
            data: { function: "comment-edit", commentID: commentID, content: content },
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

function CancelCommentEdit(commentID) {
    $(`#comment-${commentID}-c .edit-bar`).remove();
    $(`#comment-${commentID}-c p`).toggle();
    $(`#comment-${commentID} .dropdown`).toggle();
}

function ReportComment(commentID) {
    $.ajax({
        url: '/functions/forum-functions',
        type: 'POST',
        data: { function: "comment-report", commentID: commentID },
        success: function (response) {
            $(`#comment-${commentID} .report`).html("Reported!");
        }
    });
}

function updateCommentLike(commentID) {
    var check = $(`#comment-${commentID} .like`).hasClass("fa-solid");
    $.ajax({
        url: '/functions/forum-functions',
        type: 'POST',
        data: { function: "comment-like", commentID: commentID },
        success: function (response) {
            $("#comment-" + commentID + "-v").html(response);
            $(`#comment-${commentID} .like`).removeClass(check ? "fa-solid" : "fa-regular").addClass(check ? "fa-regular" : "fa-solid");
        }
    });
}

//////////* Dropdown Functions *//////////
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

//////////* Miscellaneous Functions *//////////
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

function convertBrToNewline(content) {
    if (content) {
        let step1 = content.replace(/<br\s*\/?>/g, '\n');
        let step2 = step1.replace(/^\s*[\r\n]/gm, '');
        return step2;
    }
}

function countLineBreaks(text) {
    var lines = text.split('<br>');
    return lines.length;
}
