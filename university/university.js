function search_university() {
    let input = document.getElementById('searchbar').value.toLowerCase();
    let universities = document.querySelectorAll('.tiles a');

    universities.forEach(university => {
        let universityName = university.querySelector('.word').textContent.toLowerCase();
        let universityElement = university;

        if (!universityName.includes(input)) {
            universityElement.style.display = "none";
        } else {
            universityElement.style.display = "block";
        }

        if (!input) {
            universityElement.style.display = "block";
        }
    });
}

function search_university_mobile() {
    let input = document.getElementById('searchbar2').value.toLowerCase();
    let universities = document.querySelectorAll('.tiles a');

    universities.forEach(university => {
        let universityName = university.querySelector('.word').textContent.toLowerCase();
        let universityElement = university;

        if (!universityName.includes(input)) {
            universityElement.style.display = "none";
        } else {
            universityElement.style.display = "block";
        }

        if (!input) {
            universityElement.style.display = "block";
        }
    });
}