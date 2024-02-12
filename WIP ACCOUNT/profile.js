// confirm privting account
function confirmToggle() {
    var confirmation = confirm("Are you sure you want to private your account?");
    if (!confirmation) {
        document.getElementById("private-account").checked = false;
    }
}
// profile pic change
function updateProfilePicture(input) {
    var file = input.files[0];
    if (file) {
        var reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('profile-picture').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}