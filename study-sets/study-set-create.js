document.addEventListener('DOMContentLoaded', function() {
    var universityInput = document.getElementById('setUniversity');
    var subjectInput = document.getElementById('setSubject');
    var courseInput = document.getElementById('setCourse');

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
    

    function fetchSubjectsForUniversity(universityId) {
        // console.log('Fetching Subject for university ID:', universityId); // For debugging
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
        // console.log('Fetching courses for subject ID:', subjectId); // For debugging
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
});