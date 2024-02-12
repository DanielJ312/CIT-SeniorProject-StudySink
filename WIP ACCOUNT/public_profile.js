// Sample data for posts with corresponding URLs
const postsData = [
    { text: "This is my first post!", url: "post1.html" },
    { text: "Excited to join this community!", url: "post2.html" },
    { text: "Learning new things every day.", url: "post3.html" },
    { text: "View More", url: "post3.html" },
];

// Function to dynamically add posts to the list
function addPostsToProfile() {
    const postList = document.getElementById("post-list");

    postsData.forEach((post) => {
        const postElement = document.createElement("li");
        postElement.className = "post";

        // Use an anchor tag to create a link
        const postLink = document.createElement("a");
        postLink.href = post.url;
        postLink.innerHTML = `<p>${post.text}</p>`;

        postElement.appendChild(postLink);
        postList.appendChild(postElement);
    });
}

// Calls function to add posts when the page is loaded
document.addEventListener("DOMContentLoaded", addPostsToProfile);
