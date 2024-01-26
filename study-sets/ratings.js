function initializeRating(studySetID) {
    console.log('Initialized with studySetID:', studySetID);

    // Check if studySetID is valid
    if (!studySetID) {
        console.error('Error: studySetID is not defined');
        return; // Stop the function if studySetID is not valid
    }

    document.querySelectorAll('.star').forEach(function(star, index, stars) {
        star.addEventListener('click', function() {
            var rating = this.getAttribute('data-value');
            console.log('Sending rating:', rating, 'for studySetID:', studySetID);

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
                console.log('Response:', this.responseText);
            };
            xhr.onerror = function() {
                console.error('Error in AJAX request');
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
    document.querySelectorAll('.star').forEach(function(star, index) {
        if (index < rating) {
            star.classList.add('selected');
        } else {
            star.classList.remove('selected');
        }
    });
}
