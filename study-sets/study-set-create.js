function addCard() {
    var cardContainer = document.getElementById("studyCards");
    var cardCount = cardContainer.children.length + 1;
    var card = document.createElement("div");
    
    card.className = "studyCard";
    card.innerHTML = `
        <div class="cardHeader">
            <div class=topOfCard>
                <button type="button" class="deleteCardBtn" aria-label="Delete this card">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            <div class=frontAndBack>
                <div class="cardFront">
                    <textarea class="card-textarea" id="cardFront${cardCount}" placeholder="Enter term" name="cardFront${cardCount}" maxlength="999" required></textarea>
                </div>
                <div class="cardBack">
                    <textarea class="card-textarea" id="cardBack${cardCount}" placeholder="Enter definition" name="cardBack${cardCount}" maxlength="999" required></textarea>
                </div>
            </div>
        </div>
    `;
    cardContainer.appendChild(card);

    // Add event listener to delete button
    card.querySelector('.deleteCardBtn').addEventListener('click', function() {
        if (cardContainer.children.length > 1) {
            cardContainer.removeChild(card);
        } else {
            alert("You cannot delete the last study card.");
        }
    });

     // Attach autoExpandTextArea to the new textareas and initialize their height
     var newTextAreaFront = card.querySelector('.cardFront .card-textarea');
     var newTextAreaBack = card.querySelector('.cardBack .card-textarea');
     newTextAreaFront.addEventListener('input', autoExpandTextArea);
     newTextAreaBack.addEventListener('input', autoExpandTextArea);
     autoExpandTextArea({ target: newTextAreaFront });
     autoExpandTextArea({ target: newTextAreaBack });
}

function autoExpandTextArea(event) {
    event.target.style.height = 'auto'; // Reset the height
    event.target.style.height = event.target.scrollHeight + 'px'; // Set the height to scroll height
}

document.addEventListener('DOMContentLoaded', function() {
    element = document.querySelectorAll('.studySetContainer .create');
    
    // Call to create the initial 5 cards
    if (element.length > 0) {
        for (let i = 0; i < 5; i++) {
            addCard();
        }
    }
    
    // Attach this function to the 'input' event of all textareas
    document.querySelectorAll('.studySetContainer .card-textarea').forEach(textarea => {
        textarea.addEventListener('input', autoExpandTextArea);
        // Trigger the expand function in case the textarea is pre-filled
        autoExpandTextArea({ target: textarea });
    });   

    document.getElementById('addCardBtn').addEventListener('click', addCard);

    var universitySelect = document.getElementById('setUniversity');
    var subjectSelect = document.getElementById('setSubject');
    var courseSelect = document.getElementById('setCourse');

    // Listens to the change event on the university input
    universitySelect.addEventListener('change', function() {
        var universityId = this.value;
    
        if (universityId) {
            console.log("Selected University ID:", universityId);
            fetchSubjectsForUniversity(universityId);
        } else {
            console.log('No University selected');
        }
    
        // Clear subject and course selects
        subjectSelect.innerHTML = '<option value="">Select Subject</option>';
        courseSelect.innerHTML = '<option value="">Select Course</option>';
    });   

    // Listens to the change event on the subject input
    subjectSelect.addEventListener('change', function() {
        var selectedSubjectId = this.value; // Get the selected SubjectID
        
        if (selectedSubjectId) {
            console.log("Fetching Courses for Subject ID:", selectedSubjectId);
            fetchCoursesForSubject(selectedSubjectId);
        } else {
            console.log('No Subject selected');
            courseSelect.innerHTML = '<option value="">Select Course</option>';
        }
    });  
    
    function fetchSubjectsForUniversity(universityId) {
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
        var subjectSelect = document.getElementById('setSubject');
        subjectSelect.innerHTML = '<option value="">Select Subject</option>'; // Clear existing options
    
        subjects.forEach(function(subject) {
            var option = document.createElement('option');
            option.value = subject.SubjectID; // Ensure this is the SubjectID
            option.textContent = subject.Name;
            subjectSelect.appendChild(option);
        });
    }
    
    function updateCourseOptions(courses) {
        var courseSelect = document.getElementById('setCourse');
        courseSelect.innerHTML = '<option value="">Select Course</option>'; // Clear existing options
    
        courses.forEach(function(course) {
            var option = document.createElement('option');
            option.value = course.CourseID; 
            option.textContent = course.Name; 
            courseSelect.appendChild(option);
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
        } else {
            console.error('Selected course not found:', courseAbbreviation);
            // Optionally, show an error to the user
        }

        // Collect card data
        var cards = [];
        var cardElements = document.querySelectorAll('.studyCard');
        cardElements.forEach(function(card, index) {
            var front = card.querySelector('.cardFront textarea').value;
            var back = card.querySelector('.cardBack textarea').value;
            cards.push({ front: front, back: back });
        });

        // Convert cards data to a JSON string
        var cardsJSON = JSON.stringify(cards);

        // Add cards data to the form
        var cardsInput = document.createElement('input');
        cardsInput.type = 'hidden';
        cardsInput.name = 'cards';
        cardsInput.value = cardsJSON;
        e.target.appendChild(cardsInput);

        // Now submit the form
        e.target.submit();
    }
});