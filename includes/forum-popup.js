function openPopup() {
    document.getElementById("overlay").style.display = "block";
    document.getElementById("popupContainer").style.display = "block";
    document.getElementById("createIcon").classList.add("active");
}

function closePopup() {
    document.getElementById("overlay").style.display = "none";
    document.getElementById("popupContainer").style.display = "none";
    document.getElementById("createIcon").classList.remove("active");
}

//close window when closebutton is clicked
document.getElementById("closeButton").addEventListener("click", closePopup);

//It will only close if the close button or the post button is clicked.
document.getElementById("overlay").addEventListener("click", function (event) {
    if (event.target.id !== "closeButton" && event.target.id !== "postbutton") {
        openPopup();
    }
});

function openDeletePopup() {
    document.getElementById("deletepopup").style.display = "block";
    document.getElementById("deletepopupContainer").style.display = "block";
}

function closeDeletePopup() {
    document.getElementById("deletepopup").style.display = "none";
    document.getElementById("deletepopupContainer").style.display = "none";
}

// Close window when cancel button is clicked
document.getElementById("cancelButton").addEventListener("click", closeDeletePopup);

// It will only close if the cancel button or the delete button is clicked.
document.getElementById("deletepopup").addEventListener("click", function (event) {
    if (event.target.id !== "cancelButton" && event.target.id !== "deleteButton") {
        openDeletePopup();
    }
});


// Character Counter for Post Title textarea and Post Content textarea
function titlecountChar(titleinput) {
    const maxLength = 255;
    const currentLength = titleinput.value.length;
    const remainingChars = Math.max(maxLength - currentLength, 0);

    if (currentLength > maxLength) {
      titleinput.value = titleinput.value.substring(0, maxLength);
    }

    if (currentLength == 0) {
        document.getElementById('titlecharCount').innerText = ``;
      }
    else{
    document.getElementById('titlecharCount').innerText = `${remainingChars}`;
    }
}

function contentcountChar(contentinput) {
    const maxLength = 10000;
    const currentLength = contentinput.value.length;
    const remainingChars = Math.max(maxLength - currentLength, 0);

    if (currentLength > maxLength) {
      contentinput.value = contentinput.value.substring(0, maxLength);
    }

    if (currentLength == 0) {
        document.getElementById('contentcharCount').innerText = ``;
      }
    else{
    document.getElementById('contentcharCount').innerText = `${remainingChars}`;
    }
}


// Functionality for Dynamic Dropdown Menus
document.addEventListener('DOMContentLoaded', function() {
    var universityInput = document.getElementById('setUniversityforum');
    var subjectInput = document.getElementById('setSubjectforum');

    // Listens to the change event on the university input
    universityInput.addEventListener('change', function() {
        var universityName = this.value.trim();
        var options = document.querySelectorAll('#universitiesforum option');
        var universityId;
        console.log('listener is happening')

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
        var options = document.querySelectorAll('#subjectsforum option');
        var subjectId;
        console.log('Subject listener is happening')
    
        for (let option of options) {
            if (option.value === subjectName) {
                subjectId = option.getAttribute('data-id');
                console.log('Subject ID:', subjectId)
                break;
            }
        }

        if (universityId) {
            fetchSubjectsForUniversity(universityId);
        } else {
            console.log('University ID not found for the selected name:', universityName);
        }
    });
    
    function fetchSubjectsForUniversity(universityId) {
        console.log('Fetching Subject for university ID:', universityId); // For debugging
        fetch('/includes/get-subjects.php?universityId=' + universityId)
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

    function updateSubjectOptions(subjects) {
        var subjectDatalist = document.getElementById('subjectsforum');
        subjectDatalist.innerHTML = '';  // Clear existing options
    
        subjects.forEach(function(subject) {
            var option = document.createElement('option');
            option.value = subject.Name;
            option.setAttribute('data-id', subject.SubjectID);
            subjectDatalist.appendChild(option);
        });
    }
});