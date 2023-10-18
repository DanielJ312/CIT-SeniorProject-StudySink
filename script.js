// Your JavaScript code goes here
function toggleComments(commentLink) {
    const commentsContainer = commentLink.nextElementSibling;
    if (commentsContainer.style.display === "none" || commentsContainer.style.display === "") {
        commentsContainer.style.display = "block";
        commentLink.textContent = "Hide Comments";
    } else {
        commentsContainer.style.display = "none";
        commentLink.textContent = "Show Comments";
    }
}
