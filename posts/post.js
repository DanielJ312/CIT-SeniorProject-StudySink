function DeleteComment(commentIDToDelete) {
    $.ajax({
        url:"post-template.php",    //the page containing php script
        type: "post",    //request type,
        dataType: 'json',
        data: {commentID: commentIDToDelete, action: "delete"},
    });
}