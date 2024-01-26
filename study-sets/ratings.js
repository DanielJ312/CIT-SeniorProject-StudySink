function initializeRating(studySetID, userRating) {
    console.log('Initialized with studySetID:', studySetID);

    // Check if studySetID is valid
    if (!studySetID) {
        console.error('Error: studySetID is not defined');
        return; // Stop the function if studySetID is not valid
    }

    const stars = document.querySelectorAll('.star');

    // Color in stars based on user's existing rating
    if (userRating && userRating > 0) {
        highlightStars(userRating);
    }

    document.querySelectorAll('.star').forEach(function(star, index) {
        star.addEventListener('click', function() {
            var rating = this.getAttribute('data-value');
            console.log('Sending rating:', rating, 'for studySetID:', studySetID);

            highlightStars(rating);

            // Check if rating is valid
            if (!rating) {
                console.error('Error: Rating is not defined');
                return; // Stop the function if rating is not valid
            }

            // AJAX request to submit the rating
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '/study-sets/submit-rating', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            var params = 'studySetID=' + encodeURIComponent(studySetID) + '&rating=' + encodeURIComponent(rating);
            xhr.onload = function() {
                if (this.status == 200) {
                    var response = JSON.parse(this.responseText);
                    if (response.avgRating) {
                        document.querySelector('.averageRating p').textContent = 'Average Rating: ' + response.avgRating;
                    }
                } else {
                    console.error('Error in AJAX request');
                }
            };
            xhr.send(params);

            // Highlight selected stars
            highlightStars(rating);
        });

        // Add hover effect
        star.addEventListener('mouseover', function() {
            for (let i = 0; i <= index; i++) {
                stars[i].classList.add('hovered');
            }
        });

        star.addEventListener('mouseout', function() {
            stars.forEach(function(s) {
                s.classList.remove('hovered');
            });
        });
    });
}

function highlightStars(rating) {
    const stars = document.querySelectorAll('.star');
    stars.forEach(function(star, index) {
        if (index < rating) {
            star.classList.remove('fa-regular');
            star.classList.add('fa-solid');
        } else {
            star.classList.remove('fa-solid');
            star.classList.add('fa-regular');
        }
    });
}
