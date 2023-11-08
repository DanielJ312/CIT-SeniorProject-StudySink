function addCard() {
    var cardContainer = document.getElementById("studyCards");
    var cardCount = cardContainer.children.length + 1;

    var card = document.createElement("div");
    card.className = "studyCard";
    card.innerHTML = `
        <div class="cardHeader">
            <div class="cardFront">
                <label for="cardFront${cardCount}">Front:</label>
                <input type="text" id="cardFront${cardCount}" name="cardFront${cardCount}" required>
            </div>
            <div class="cardBack">
                <label for="cardBack${cardCount}">Back:</label>
                <input type="text" id="cardBack${cardCount}" name="cardBack${cardCount}" required>
            </div>
        </div>
    `;
    cardContainer.appendChild(card);
}

document.addEventListener('DOMContentLoaded', function() {

    // Call to create the initial 5 cards
    for (let i = 0; i < 5; i++) {
        addCard();
    }

    document.getElementById('addCardBtn').addEventListener('click', addCard);

    var universityInput = document.getElementById('setUniversity');
    var subjectInput = document.getElementById('setSubject');
    var courseInput = document.getElementById('setCourse');

    // Listens to the change event on the university input
    universityInput.addEventListener('change', function() {
        var universityName = this.value.trim();
        var options = document.querySelectorAll('#universities option');
        var universityId;

        for (let option of options) {
            if (option.value === universityName) {
                universityId = option.getAttribute('data-id');
                break;
            }
        }

        if (universityId) {
            fetchSubjectsForUniversity(universityId);
        } else {
            console.log('University ID not found for the selected name:', universityName);
        }
    });

    // Listens to the change event on the subject input
    subjectInput.addEventListener('change', function() {
        var subjectName = this.value.trim();
        var options = document.querySelectorAll('#subjects option');
        var subjectId;
    
        for (let option of options) {
            if (option.value === subjectName) {
                subjectId = option.getAttribute('data-id');
                break;
            }
        }
    
        if (subjectId) {
            // console.log('Fetching courses for subject ID:', subjectId); // For debugging
            fetchCoursesForSubject(subjectId);
        } else {
            console.log('Subject ID not found for the selected name:', subjectName);
        }
    });
    
    // Functions works correctly to retreive the correct courses based on the subject selected
    function fetchSubjectsForUniversity(universityId) {
        console.log('Fetching Subject for university ID:', universityId); // For debugging
        fetch('./get-subjects.php?universityId=' + universityId)
            .then(function(response) {
                return response.json();
            })
            .then(function(subjects) {
                updateSubjectOptions(subjects);
            })
            .catch(function(error) {
                console.error('Error fetching subjects:', error);
            });
    }

    // Function works correctly to retreive the correct courses based on the subject selected
    function fetchCoursesForSubject(subjectId) {
        console.log('Fetching courses for subject ID:', subjectId); // For debugging
        fetch('./get-courses.php?subjectId=' + subjectId)
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                if (data.error) {
                    console.error('Error from server:', data.error);
                    alert('There was an error fetching courses: ' + data.error);
                } else if (Array.isArray(data)) {
                    updateCourseOptions(data);
                    console.log('Received courses based on Subject:', data);
                } else {
                    console.error('Received data is not an array:', data);
                }
            })
            .catch(function(error) {
                console.error('Error fetching courses:', error);
            });
    }   

    function updateSubjectOptions(subjects) {
        var subjectDatalist = document.getElementById('subjects');
        subjectDatalist.innerHTML = '';  // Clear existing options
    
        subjects.forEach(function(subject) {
            var option = document.createElement('option');
            option.value = subject.Name;
            option.setAttribute('data-id', subject.SubjectID);
            subjectDatalist.appendChild(option);
        });
    }
    
    function updateCourseOptions(courses) {
        var courseDatalist = document.getElementById('courses');
        courseDatalist.innerHTML = '';  // Clear existing options
    
        courses.forEach(function(course) {
            var option = document.createElement('option');
            option.value = course.Abbreviation; // This can be changed to Abbreviation or Name
            option.setAttribute('data-id', course.CourseID);
            courseDatalist.appendChild(option);
        });
    }

    document.querySelector('#studySetForm').addEventListener('submit', handleFormSubmit);

    function handleFormSubmit(e) {
        e.preventDefault(); // Stop the form from submitting initially

        // Get the displayed course abbreviation
        var courseAbbreviation = courseInput.value.trim();
        var courseID;

        // Find the option element that has the matching abbreviation and get its CourseID
        var options = document.querySelectorAll('#courses option');
        for (var option of options) {
            if (option.value === courseAbbreviation) {
                courseID = option.getAttribute('data-id');
                break;
            }
        }

        if (courseID) {
            // Create a hidden input to hold the actual CourseID value
            var hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'course_id';
            hiddenInput.value = courseID;
            e.target.appendChild(hiddenInput);

            // Now submit the form
            e.target.submit();
        } else {
            console.error('Selected course not found:', courseAbbreviation);
            // Optionally, show an error to the user
        }
    }

});