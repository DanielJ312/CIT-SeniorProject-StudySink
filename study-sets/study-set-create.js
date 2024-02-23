function addCard() {
    var cardContainer = document.getElementById("studyCards");
    var cardCount = cardContainer.children.length + 1;
    var card = document.createElement("div");
    card.className = "studyCard";
    card.setAttribute('data-new-card', 'true');
    
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

    // Existing event listener for the delete button
    card.querySelector('.deleteCardBtn').addEventListener('click', function() {
        var cardElement = this.closest('.studyCard');
        if (cardElement.getAttribute('data-card-id')) {
            // Mark the card for deletion
            console.log('Card marked for deletion');
            cardElement.setAttribute('data-deleted', 'true');
            cardElement.style.display = 'none'; // Hide the card
    
            // Set the hidden delete flag to true
            cardElement.querySelector('.delete-flag').value = 'true';
        } else {
            // If the card is new (not saved in the database), remove it
            cardElement.remove();
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
    var element = event.target;
    element.style.overflowY = 'hidden'; // Prevent scrollbar
    element.style.height = 'inherit'; // Reset the height
    element.style.height = `${element.scrollHeight}px`; // Set the height based on content
}

document.addEventListener('DOMContentLoaded', function() {
    var pageType = document.getElementById('pageIdentifier').dataset.pageType;
    element = document.querySelectorAll('.studySetContainer .create');

    var universitySelect = document.getElementById('setUniversity');
    var subjectSelect = document.getElementById('setSubject');
    var courseSelect = document.getElementById('setCourse');

    if (pageType === 'edit') {
        if (initialUniversityId) {
            universitySelect.value = initialUniversityId;
            fetchSubjectsForUniversity(initialUniversityId, initialSubjectId);
        }
    } else if (pageType === 'create') {
        // Call to create the initial 5 cards
        for (let i = 0; i < 5; i++) {
            addCard();
        }
    }

    document.querySelectorAll('.deleteCardBtn').forEach(button => {
        button.addEventListener('click', function() {
            var cardElement = this.closest('.studyCard');
            var totalCards = document.querySelectorAll('.studyCard').length;
    
            if (totalCards > 1) {
                if (cardElement.getAttribute('data-card-id')) {
                    console.log('Card marked for deletion');
                    cardElement.setAttribute('data-deleted', 'true');
                    cardElement.style.display = 'none'; // Hide the card
    
                    // Update the hidden delete flag
                    cardElement.querySelector('.delete-flag').value = 'true';
                } else {
                    // If the card is new and not saved in the database, remove it
                    cardElement.remove();
                }
            } else {
                // Display a message if it's the last card
                alert("Cannot delete the last study card in the Study Set");
            }
        });
    });    
    
    // Attach this function to the 'input' event of all textareas
    document.querySelectorAll('.studySetContainer .card-textarea').forEach(textarea => {
        textarea.addEventListener('input', autoExpandTextArea);
        // Trigger the expand function in case the textarea is pre-filled
        autoExpandTextArea({ target: textarea });
    });   

    document.querySelectorAll('.studyCard textarea').forEach(textarea => {
        textarea.addEventListener('input', function() {
            let cardElement = this.closest('.studyCard');
            cardElement.setAttribute('data-edited', 'true');
        });
    });    

    document.getElementById('addCardBtn').addEventListener('click', addCard);

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
    
    function fetchSubjectsForUniversity(universityId, selectedSubjectId) {
        fetch('./get-subjects.php?universityId=' + universityId)
            .then(response => response.json())
            .then(subjects => {
                updateSubjectOptions(subjects, selectedSubjectId);
                if (selectedSubjectId) {
                    fetchCoursesForSubject(selectedSubjectId, initialCourseId);
                }
            })
            .catch(error => console.error('Error fetching subjects:', error));
    }

    function fetchCoursesForSubject(subjectId) {
        fetch('./get-courses.php?subjectId=' + subjectId)
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                if (data.error) {
                    console.error('Error from server:', data.error);
                    alert('There was an error fetching courses: ' + data.error);
                } else if (pageType === 'edit') {
                    updateCourseOptions(data, initialCourseId);
                } else if (Array.isArray(data)) {
                    updateCourseOptions(data);
                    console.log('Received courses based on Subject ID:', subjectId, data);
                } else {
                    console.error('Received data is not an array:', data);
                }
            })
            .catch(function(error) {
                console.error('Error fetching courses:', error);
            });
    }

    function updateSubjectOptions(subjects, selectedSubjectId) {
        var subjectSelect = document.getElementById('setSubject');
        subjectSelect.innerHTML = '<option value="">Select Subject</option>'; // Clear existing options
    
        subjects.forEach(function(subject) {
            var option = document.createElement('option');
            option.value = subject.SubjectID;
            option.textContent = subject.Name;
            if (subject.SubjectID == selectedSubjectId) {
                option.selected = true;
                subjectSelect.classList.add('selected');
            }
            subjectSelect.appendChild(option);
        });

        if (selectedSubjectId) {
            fetchCoursesForSubject(selectedSubjectId, initialCourseId);
        }
    }
    
    function updateCourseOptions(courses, selectedCourseId) {
        var courseSelect = document.getElementById('setCourse');
        courseSelect.innerHTML = '<option value="">Select Course</option>';

        console.log('Selected Course ID:', selectedCourseId); // Debugging log

        courses.forEach(function(course) {
            var option = document.createElement('option');
            option.value = course.CourseID;
            option.textContent = course.Abbreviation; // or use course.Name

            // Check for type mismatch issues by converting both to strings
            if (String(course.CourseID) === String(selectedCourseId)) {
                option.selected = true;
                courseSelect.classList.add('selected');
                console.log('Setting selected course:', course); // Debugging log
            }

            courseSelect.appendChild(option);
        });

        console.log('Course options updated.');
    }

    // Listens to the change event on the course input
    courseSelect.addEventListener('change', function() {
        var selectedCourseId = this.value; // Get the selected CourseID

        if (selectedCourseId) {
            console.log("Selected Course ID:", selectedCourseId);
            // You can perform additional actions here if needed
        } else {
            console.log('No Course selected');
        }
    });

    document.querySelector('#studySetForm').addEventListener('submit', handleFormSubmit);

    function handleFormSubmit(e) {
        e.preventDefault(); // Stop the form from submitting initially

        // Get the selected course ID directly
        var courseSelect = document.getElementById('setCourse');
        var courseID = courseSelect.value.trim();

        if (courseID) {
            console.log('Submitting Course ID:', courseID); // Log the CourseID being submitted
            // Create a hidden input to hold the actual CourseID value
            var hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'course_id';
            hiddenInput.value = courseID;
            e.target.appendChild(hiddenInput);
        } else {
            console.error('Course ID not found or selected:', courseID);
            // Optionally, show an error to the user
        }

        // Collect card data
        var cards = [];
        var cardElements = document.querySelectorAll('.studyCard');
        cardElements.forEach(function(card) {
            var isEdited = card.getAttribute('data-edited') === 'true';
            var isNew = card.getAttribute('data-new-card') === 'true';
            var isDeleted = card.getAttribute('data-deleted') === 'true';
            var cardId = isNew ? null : card.getAttribute('data-card-id');
        
            var front = card.querySelector('.cardFront textarea').value;
            var back = card.querySelector('.cardBack textarea').value;
        
            var cardData = { 
                id: cardId, 
                front: front, 
                back: back, 
                edited: isEdited,
                newCard: isNew,
                deleted: isDeleted // Include the deleted flag
            };
            cards.push(cardData);
        });

        // Convert cards data to a JSON string and log it
        var cardsJSON = JSON.stringify(cards);
        console.log('Cards data before submission:', cardsJSON);

        // Add cards data to the form
        var cardsInput = document.createElement('input');
        cardsInput.type = 'hidden';
        cardsInput.name = 'cards';
        cardsInput.value = cardsJSON;
        e.target.appendChild(cardsInput);

        var cardsJSON = JSON.stringify(cards);
        console.log('Cards data before submission:', cardsJSON);
    
        // Now submit the form
        e.target.submit();
    }

    document.querySelectorAll('.studySetContainer select').forEach(select => {
        if (select.value) {
            select.classList.add('selected');
        }
        select.addEventListener('change', function() {
            if (this.value) {
                this.classList.add('selected');
            } else {
                this.classList.remove('selected');
            }
        });
    });
});

window.onload = function() {
    document.querySelectorAll('.studySetContainer select').forEach(select => {
        if (select.value) {
            select.classList.add('selected');
        }
    });
};