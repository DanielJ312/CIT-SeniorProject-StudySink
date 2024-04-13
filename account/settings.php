<?php
//////////* Settings - Allows users to change their profile picture, bio, password, & university and also delete account  *//////////
require($_SERVER['DOCUMENT_ROOT'] . "/functions/account-functions.php");
if (!check_login()) header("Location: /account/login.php");
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    switch (true) {
        case isset($_FILES['image']):
            upload_avatar($_FILES);
            break;
        case isset($_POST['bio']):
            update_bio($_POST);
            break;
        case isset($_POST['password']):
            update_password($_POST);
            break;
        case isset($_POST['updateUniversity']):
            update_primary_university($_POST);
            break;
        case isset($_POST['delete-password']):
            delete_account();
            break;
        default:
            break;
    }
}
$pageTitle = "Settings";
$settingsUniversities = get_universities_list();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
    <link rel="stylesheet" type="text/css" href="/styles/account/settings.css">
</head>

<body>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/to-top.php"); ?>
    <main class="account-settings">
        <div class="left-container">
            <div class="main-title">Account Settings</div>
        </div>
        <div class="right-container">
            <div class="pp-container">
                <div class="pp-setting-label">Profile Picture</div>
                <img src="<?= $_SESSION['USER']->Avatar ?>" alt="Avatar" class="settings-profile-picture <?= check_active('/account/profile'); ?>" id="settingsProfilePicture" title="Avatar">
                <div class="pp-inner-container">
                    <form method="post" enctype="multipart/form-data" class="pp-form" onsubmit="return validateFile()">
                        <label for="image" class="change-pp-btn">
                            <p>Change Profile Picture</p>
                        </label>
                        <input type="file" name="image" id="image" accept="image/*" required onchange="readURL(this);">
                        <button type="submit" class="pp-save-btn">Save</button>
                    </form>
                    <div id="ppError" class="ppError"></div>
                </div>
            </div>
            <div class="bio-container">
                <div class="bio-setting-label">Bio</div>
                <form method="post" class="bio-form" id="bioForm">
                    <div class="bio-textarea-container">
                        <textarea name="bio" class="bio-textarea" id="bio" placeholder="Write a bio..." onkeyup="biocountChar(this)"><?= $_SESSION['USER']->Bio ?></textarea>
                        <span id="biocharCount" class="bioCharCount"></span>
                        <button type="submit" class="bio-save-btn">Save</button>
                    </div>
                </form>
            </div>
            <div class="info-container">
                <div class="info-setting-label">Account Information</div>
                <div class="username-container">
                    <div class="username-setting-label">Username:</div>
                    <div class="username-value"><?= $_SESSION['USER']->Username ?></div>
                </div>
                <div class="email-container">
                    <div class="email-setting-label">Email:</div>
                    <div class="email-value"><?= $_SESSION['USER']->Email ?></div>
                </div>
            </div>
            <div class="password-container">
                <div class="password-setting-label">Change Password</div>
                <form method="post" class="password-form" id="passwordForm">
                    <div id="passwordError" class="password-mismatch"></div>
                    <input type="password" name="password" class="new-password-input" placeholder="New Password" id="password" required>
                    <input type="password" name="confirmPassword" class="confirm-password-input" placeholder="Confirm Password" id="confirmPassword" required>
                    <button type="submit" class="password-save-btn" id="submitBtn">Save</button>
                </form>
            </div>
            <div class="uni-container">
                <div class="uni-setting-label" id="Primary-University">Primary University</div>
                <div id="uniError" class="uniError"></div>
                <div class="request-link">Dont see your school? Request a school by clicking <a href="/request">Here</a>.</div>
                <div class="uniFormContainer">
                    <form method="post" class="uni-form">
                        <select class="uniDropdown" id="setUniversity" name="updateUniversity">
                            <option value=""></option>
                            <?php $currentUniversity = get_user_university_name();
                                  foreach ($settingsUniversities as $settingsUniversity) :
                                  $selected = ($currentUniversity == $settingsUniversity->Name) ? 'selected' : ''; ?>
                                <option value="<?= htmlspecialchars($settingsUniversity->UniversityID) ?>" <?= $selected ?>>
                                    <?= htmlspecialchars($settingsUniversity->Name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="uni-save-btn">Save</button>
                    </form>
                </div>
            </div>
            <div class="delete-container">
                <button class="delete-btn" id="delete-btn">Delete Account</button>
                <div id="delete-overlay" class="delete-overlay">
                    <div id="delete-popup" class="delete-popup">
                        <i class="fa-regular fa-circle-xmark fa-2xl" id="delete-closeButton" onclick="closePopup()"></i>
                        <div class="delete-content">
                            <h1>Are you sure?</h1>
                            <p>By deleting your account, you will lose all of your data and your account will be permanently deleted.</p>
                            <p>This action cannot be undone.</p>
                            <p style="font-size: 1em;">If you are sure you want to delete your account, please enter "Delete me" in the field below and click the button to delete your account.</p>
                            <form method="post" class="delete-form">
                                <div id="deleteMeError" class="deleteMeError"></div>
                                <input name="delete-password" class="delete-me-input" placeholder="Delete me" id="delete-me" required>
                                <button type="submit" class="delete-confirm-btn">Permanently Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>
<script>
    //code for the profile picture file validation
    function validateFile() {
        var fileInput = document.getElementById('image');
        var filePath = fileInput.value;
        var allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
        if (!allowedExtensions.exec(filePath)) {
            document.getElementById('ppError').textContent = ' Only JPEG, JPG, and PNG files are allowed';
            fileInput.value = '';
            return false;
        } else {
            document.getElementById('ppError').textContent = '';
            return true;
        }
    }

    //code for displaying the profile picture preview
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                document.getElementById('settingsProfilePicture').style.display = 'flex';
                document.getElementById('settingsProfilePicture').src = e.target.result;
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    //code for the character count in the bio textarea
    function biocountChar(contentinput) {
        const maxLength = 1000;
        const currentLength = contentinput.value.length;
        const remainingChars = Math.max(maxLength - currentLength, 0);

        if (currentLength > maxLength) {
            contentinput.value = contentinput.value.substring(0, maxLength);
        }

        if (currentLength == 0) {
            document.getElementById('biocharCount').innerText = ``;
        } else {
            document.getElementById('biocharCount').innerText = `${remainingChars}`;
        }
    }

    //code for the password confirmation
    document.getElementById('passwordForm').addEventListener('submit', function(event) {
        var password = document.getElementById('password').value;
        var confirmPassword = document.getElementById('confirmPassword').value;

        if (password !== confirmPassword) {
            document.getElementById('passwordError').textContent = 'Passwords do not match.';
            event.preventDefault(); // Prevent form from being submitted
        } else {
            document.getElementById('passwordError').textContent = '';
        }
    });

    //code for primary university value must match a value in the dropdown in order for the form to submit
    document.querySelector('.uni-form').addEventListener('submit', function(event) {
        var university = document.getElementById('setUniversity').value;
        var universities = document.getElementById('universities').children;
        var found = false;

        //If blank value is submitted, form will submit and set primary university to null
        if (university === '') {
            return;
        }
        document.getElementById('uniError').textContent = '';
        for (var i = 0; i < universities.length; i++) {
            if (universities[i].value === university) {
                found = true;
                break;
            }
        }
        if (!found) {
            document.getElementById('uniError').textContent = 'University entry must match a value from the dropdown.';
            event.preventDefault();
        }
    });

    //Code for the delete account popup
    document.getElementById('delete-btn').addEventListener('click', function() {
        document.getElementById('delete-overlay').style.display = 'block';
        document.getElementById('delete-popup').style.display = 'block';
    });
    document.getElementById('delete-closeButton').addEventListener('click', function() {
        document.getElementById('delete-overlay').style.display = 'none';
        document.getElementById('delete-popup').style.display = 'none';
    });

    //code for the delete account password to display an 'Incorrect Password' message if the password is incorrect
    document.querySelector('.delete-form').addEventListener('submit', function(event) {
        var deleteme = document.getElementById('delete-me').value;
        if (deleteme !== 'Delete me') {
            document.getElementById('deleteMeError').textContent = 'Text does not match "Delete me"';
            event.preventDefault(); // Prevent form from being submitted
        } else {
            document.getElementById('deleteMeError').textContent = '';
        }
    });
</script>

</html>