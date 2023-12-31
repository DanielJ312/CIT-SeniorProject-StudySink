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

// Function to validate if the input value is in the dropdown options
function isValidDropdownSelection(inputElement, dropdownId) {
    var options = document.querySelectorAll(`#${dropdownId} option`);
    var inputValue = inputElement.value.trim();
    for (var option of options) {
        if (option.value === inputValue) {
            return true; // Input matches an option in the dropdown
        }
    }
    return false; // No match found
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
                console.log('University ID:', universityId);
                break;
            }
        }

        if (universityId) {
            console.log("About to fetch Subjects for University");
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
            console.log("Before the fetchCoursesForSubject function");
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

    // Updating the event listeners for the dropdown inputs
    document.getElementById('setUniversity').addEventListener('change', function() {
        if (!isValidDropdownSelection(this, 'universities')) {
            alert("Please select a valid University from the list.");
            this.value = ''; // Clear the invalid input
        }
    });

    document.getElementById('setSubject').addEventListener('change', function() {
        if (!isValidDropdownSelection(this, 'subjects')) {
            alert("Please select a valid Subject from the list.");
            this.value = '';
        }
    });

    document.getElementById('setCourse').addEventListener('change', function() {
        if (!isValidDropdownSelection(this, 'courses')) {
            alert("Please select a valid Course from the list.");
            this.value = '';
        }
    });

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