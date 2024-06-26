<?php
//////////* Account Functions - Contains functions relating to account pages *//////////
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/mail-functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

/////* Functions Switch */////
if (isset($_POST['function'])) {
    switch ($_POST['function']) {
        /////* Reset */////
        case "email":
            if (check_email($_POST) == true) {
                send_verify_code("reset", $_POST['email']);
                echo "true";
            }
            else {
                echo "false";
            }
            break;
        case "password":
            echo reset_password($_POST);
            break;
        /////* Verify */////
        case "delete":
            delete_unverified();
            break;
        default:
            break;
    }
}


/////* Register Functions */////
function signup($data) {
    $errors = array();
    $data['username'] = trim($data['username']);
    $data['password'] = trim($data['password']);

    // Username Checks
    $checkUsername = run_database("SELECT * FROM USER_T WHERE Username = :Username LIMIT 1;", ['Username' => $data['username']]);
    if (is_array($checkUsername)) {
        $errors['username'] = "Username already exists.";
    }
    else if (!check_alphanumeric($data['username'])) {
        $errors['username'] = "Username must be alphanumberic.";
    }
    else if (strlen(trim($data['username'])) < 4) {
        $errors['username'] = "Username must be 4 or more characters.";
    }
    
    //Email Checks
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Email is invalid.";
    }
    $checkEmail = run_database("SELECT * FROM USER_T WHERE Email = :Email LIMIT 1;", ['Email' => $data['email']]);
    if (is_array($checkEmail)) {
        $errors['email'] = "Email already exists.";
    }

    // Password Checks
    if (strlen(trim($data['password'])) < 6) {
        $errors['password'] = "Password must be 6 or more characters.";
    } 
    else if (!check_uppercase($data['password'])) {
        $errors['password'] = "Password must contain an uppercase charcter.";
    }
    else if (!check_number($data['password'])) {
        $errors['password'] = "Password must contain a number.";
    }
    else if (!check_specialchar($data['password'])) {
        $errors['password'] = "Password must contain a special charcter.";
    }
    else if ($data['password'] != $data['password2']) {
        $errors['password2'] = "Passwords must match.";
    }
    
    // Save to Database if valid
    if (count($errors) == 0) {
        $values = [
            'UserID' => generate_ID("USER"),
            'UniversityID' => $data['useruni'] == 0 ? null : $data['useruni'],
            'Username' => $data['username'],
            'Email' => $data['email'],
            'Password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'Created' => time()
        ];

        $query = "INSERT INTO USER_T (UserID, UniversityID, Username, Email, Password, Created) VALUES (:UserID, :UniversityID, :Username, :Email, :Password, :Created);";
        run_database($query, $values);

        $query = "SELECT * FROM USER_T WHERE Email = '{$values['Email']}' LIMIT 1;";
        $result = run_database($query);
        if (!empty($result)) {
            $_SESSION['USER'] = $result[0];
            $_SESSION['LOGGED_IN'] = true;
        }
        send_verify_code("verify", $values['Email']);
    }

    return $errors;
}

function check_alphanumeric($string) {
    return preg_match('/^[a-zA-Z0-9]+$/', $string);
}

function check_uppercase($string) {
    return preg_match('/[A-Z]/', $string);
}

function check_number($string) {
    return preg_match('/[0-9]/', $string);
}

function check_specialchar($string) {
    return preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $string);
}

/////* Login Functions */////
function login($data) {
    $loginType = "Email";
    $errors = array();

    // Input Type checks
    if (filter_var($data['logininput'], FILTER_VALIDATE_EMAIL)) {
        $loginType = "Email";
    } 
    else if (check_alphanumeric($data['logininput']) && strlen($data['logininput']) >= 4) {
        $loginType = "Username";
    } 
    else {
        $errors['logininput'] = "Email or username is not valid.";
    }

    // Password Checks
    if (strlen(trim($data['password'])) < 6) {
        $errors['password'] = "Password is not a valid form of password.";
    }

    // Check if valid logininput
    if (!isset($errors['logininput'])) {
        switch ($loginType) {
            case 'Email':
                $values['Email'] = $data['logininput'];
                break;
            case 'Username':
                $values['Username'] = $data['logininput'];
                break;
            default:
                break;
        }
        $query = "SELECT * FROM USER_T WHERE $loginType = :$loginType LIMIT 1;";
        $result = run_database($query, $values);
    }
    
    // Check if password and logininput match
    if (!empty($result)) {
        $password = $data['password'];
        $result = $result[0];
        if (password_verify($password, $result->Password)) {
            $_SESSION['USER'] = $result;
            $_SESSION['LOGGED_IN'] = true;
        } 
        else {
            $errors['password'] = "Password for this account is incorrect.";
        }
    }
    else {
        $errors['logininput'] = "Account not found.";
        unset($errors['password']);
    }

    return $errors;
}

/////* Verify Functions */////
function verify_email($data) {
    $values = [
        'Email' => $_SESSION['USER']->Email,
        'Code' => $data['code']
    ];

    $query = "SELECT * FROM CODE_T WHERE Email = :Email && Code = :Code;";
    $result = run_database($query, $values);
    if (is_array($result)) {
        $result = $result[0];
        if ($result->Expires > time()) {
            $email = $result->Email;
            $query = "UPDATE USER_T SET Verified = 1 WHERE Email = '$email' LIMIT 1;";
            $result = run_database($query);
            delete_code("verify", $email);
            update_user();
            header("Location: /account/profile.php");
        } else {
            $errors['code'] = "This code has expired.";
        }
    } else {
        $errors['code'] = "This code is incorrect.";
    }

    return $errors;
}

function delete_unverified() {
    logout();
    redirect("/account/register");
}

/////* Reset Functions */////
function check_email($data) {
    $valid = false;
    $values['Email'] = $data['email'];
    $query = "SELECT * FROM USER_T WHERE Email = :Email LIMIT 1;";
    $result = run_database($query, $values);
    if (is_array($result)) {
        $valid = true;
    }

    return $valid;
}

function reset_password($data) {
    $status = "none";
    $values = array();
    $values['Code'] = $data['code'];
    $query = "SELECT * FROM CODE_T WHERE Code = :Code LIMIT 1;";
    $result = run_database($query, $values);
    $password = trim($data['password']);

    if (is_array($result)) {
        $result = $result[0];
        if (time() > $result->Expires) {
            $status = "expired";
        } 
        else if (strlen($password) < 6 || !check_uppercase($password) || !check_number($password) || !check_specialchar($password)) {
            $status = "invalid";
        }
        if ($status == "none") {
            $values = array();
            $values = [
                'Email' => $result->Email,
                'Password' => password_hash($password, PASSWORD_DEFAULT)
            ];
            $query = "UPDATE USER_T SET Password = :Password WHERE Email = :Email";
            run_database($query, $values);
            delete_code("reset", $result->Email);
            $status = "valid";
        }
    } else {
        $status = "wrong";
    }

    return $status;
}

/////* Logout Functions */////
function logout() {
    unset($_SESSION['USER']);
    unset($_SESSION['LOGGED_IN']);
    session_destroy();
}

/////* Settings Functions */////
function upload_avatar($file) {
    $credentials = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . "/config.ini");
    $s3 = new S3Client([
        'version' => 'latest',
        'region' => $credentials['aws_region'],
        'credentials' => [
            'key' => $credentials['aws_access_key'],
            'secret' => $credentials['aws_secret_key'],
        ],
    ]);

    // Fetch the current avatar URL from the database
    if (isset($file['image']) && $file['image']['error'] === UPLOAD_ERR_OK) {
        $currentAvatarQuery = "SELECT Avatar FROM USER_T WHERE UserID = :UserID";
        $values = ['UserID' => $_SESSION['USER']->UserID];
        $currentAvatarResult = run_database($currentAvatarQuery, $values);

        // If the user already has an avatar, store the URL in a variable
        if ($currentAvatarResult && isset($currentAvatarResult[0]->Avatar)) {
            $currentAvatar = $currentAvatarResult[0]->Avatar;
        } else {
            $currentAvatar = null;
        }

        // Store the file path and key for the new avatar
        $file_path = $file['image']['tmp_name'];
        $key = "avatars/{$_SESSION['USER']->UserID}_{$_SESSION['USER']->Username}_" . (time() - (60 * 60 * 7)) . ".png";

        // Upload the new avatar to the S3 bucket
        try {
            $result = $s3->putObject([
                'Bucket' => $credentials['s3_bucket_name'],
                'Key' => $key,
                'SourceFile' => $file_path,
                'ACL' => 'public-read',
            ]);

            // Update the user's avatar URL in the database
            $values['UserID'] = $_SESSION['USER']->UserID;
            $values['Avatar'] = $result['ObjectURL'];
            $query = "UPDATE USER_T SET Avatar = :Avatar WHERE UserID = :UserID";
            run_database($query, $values);
            update_session();

            // Delete the old avatar from the S3 bucket
            if ($currentAvatar && $currentAvatar !== 'https://studysink.s3.amazonaws.com/assets/DefaultAvatar.jpg') {
                $oldKey = str_replace("https://{$credentials['s3_bucket_name']}.s3.amazonaws.com/", '', $currentAvatar);
                $s3->deleteObject([
                    'Bucket' => $credentials['s3_bucket_name'],
                    'Key' => $oldKey,
                ]);
            }
        } catch (S3Exception $e) {
            echo "There was an error uploading the file: " . $e->getMessage();
        }
    } else {
        echo "Error: Please select a valid image file.";
    }
}

function update_bio($data) {
    $values = [
        'UserID' => $_SESSION['USER']->UserID,
        'Bio' => $data['bio']
    ];
    $query = "UPDATE USER_T SET Bio = :Bio WHERE UserID = :UserID";
    run_database($query, $values);
    update_session();
}

function update_password($data) {
    $values = [
        'UserID' => $_SESSION['USER']->UserID,
        'Password' => password_hash($data['password'], PASSWORD_DEFAULT)
    ];
    $query = "UPDATE USER_T SET Password = :Password WHERE UserID = :UserID";
    run_database($query, $values);
    update_session();
}

function update_primary_university($data) {
    // If the input is blank, set the primary university to null
    if ($data['updateUniversity'] === '') {
        $primaryUniversityID = null;
    } else {
        $primaryUniversityID = $data['updateUniversity'];
    }
        // Use the UniversityID to update the USER_T table
        $values = [
            'UserID' => $_SESSION['USER']->UserID,
            'UniversityID' => $primaryUniversityID
        ];
        $query = "UPDATE USER_T SET UniversityID = :UniversityID WHERE UserID = :UserID";
        run_database($query, $values);
        update_session();
}

function delete_account() {
    $values = ['UserID' => $_SESSION['USER']->UserID];
    //Posts created by the user will have there POST_T.UserID changed to DeletedUser's UserID
    //Post likes creted by the user will be removed from the POST_LIKE_T table WHERE POST_LIKE_T.UserID = :UserID
    //Study Sets created by the user will have there STUDY_SET_T.UserID changed to DeletedUser's UserID
    //Study Set Ratings created by the user will have there STUDY_SET_RATINGS.UserID changed to DeletedUser's UserID
    //Comments created by the user will have there COMMENT_T.UserID changed to DeletedUser's UserID
    //Comment votes created by the user will have there COMMENT_LIKE_T.UserID changed to DeletedUser's UserID
    //The user will have there row deleted from USER_T WHERE USER_T.UserID = :UserID
    $deleteScript = "
    UPDATE POST_T SET UserID = 1 WHERE UserID = :UserID;
    DELETE FROM POST_LIKE_T WHERE UserID = :UserID;
    UPDATE STUDY_SET_T SET UserID = 1 WHERE UserID = :UserID;
    UPDATE STUDY_SET_RATINGS SET UserID = 1 WHERE UserID = :UserID;
    UPDATE COMMENT_T SET UserID = 1 WHERE UserID = :UserID;
    UPDATE  COMMENT_LIKE_T SET UserID = 1 WHERE UserID = :UserID;
    DELETE FROM USER_T WHERE UserID = :UserID;";
    run_database($deleteScript, $values);
    session_destroy();
    header("Location: /");
}

/////* Profile Functions */////
// This function is used to populate all the information on the profile page when a user clicks on another users profile
function get_user_info($username) {
    $values = ['Username' => $username];
    $query = "SELECT * FROM USER_T WHERE Username = :Username LIMIT 1;";
    $result = run_database($query, $values);
    if (is_array($result) && isset($result[0]->UniversityID)) {
        $query = "SELECT * FROM USER_T INNER JOIN UNIVERSITY_T ON UNIVERSITY_T.UniversityID = USER_T.UniversityID WHERE Username = :Username LIMIT 1;";
        $result = run_database($query, $values);
    }    
    return is_array($result) ? $result[0] : null; 
}

function get_profile_users_study_sets($userID) {
    $values['UserID'] = $userID;
    $query = <<<query
    SELECT STUDY_SET_T.StudySetID, Title, Description, STUDY_SET_T.Created AS SetCreated, Username, Avatar, UNIVERSITY_T.Name AS UniversityName, COURSE_T.Abbreviation AS Course, COUNT(DISTINCT CommentID) AS Comments,
    COALESCE((SELECT AVG(Rating) FROM STUDY_SET_RATINGS WHERE StudySetID = STUDY_SET_T.StudySetID), 0) AS Rating
    FROM STUDY_SET_T INNER JOIN USER_T ON STUDY_SET_T.UserID = USER_T.UserID
        INNER JOIN COURSE_T ON COURSE_T.CourseID = STUDY_SET_T.CourseID
        INNER JOIN SUBJECT_T ON SUBJECT_T.SubjectID = COURSE_T.SubjectID
        INNER JOIN UNIVERSITY_T ON UNIVERSITY_T.UniversityID = SUBJECT_T.UniversityID
        LEFT OUTER JOIN COMMENT_T ON COMMENT_T.StudySetID = STUDY_SET_T.StudySetID
    WHERE STUDY_SET_T.UserID = :UserID
    GROUP BY STUDY_SET_T.StudySetID
    ORDER BY STUDY_SET_T.Created DESC
    query;
    return run_database($query, $values = ['UserID' => $userID]);
}

function get_profile_users_posts($userID) {
    $values['UserID'] = $userID;
    $query = <<<query
    SELECT POST_T.PostID, Title, POST_T.Content, POST_T.Created AS PostCreated, POST_T.Modified AS PostModified, USER_T.UserID, Username, Avatar, UNIVERSITY_T.UniversityID, UNIVERSITY_T.Name AS UniversityName, UNIVERSITY_T.Abbreviation, SUBJECT_T.Name AS SubjectName, COUNT(DISTINCT CommentID) AS Comments, COALESCE((SELECT COUNT(*) FROM POST_LIKE_T WHERE PostID = POST_T.PostID AND VoteType = 1), 0) AS Likes
    FROM POST_T 
        INNER JOIN USER_T ON POST_T.UserID = USER_T.UserID
        INNER JOIN UNIVERSITY_T ON POST_T.UniversityID = UNIVERSITY_T.UniversityID
        INNER JOIN SUBJECT_T ON POST_T.SubjectID = SUBJECT_T.SubjectID
        LEFT OUTER JOIN COMMENT_T ON COMMENT_T.PostID = POST_T.PostID
    WHERE POST_T.UserID = :UserID
    GROUP BY POST_T.PostID
    ORDER BY POST_T.Created DESC
    query;
    return run_database($query, $values = ['UserID' => $userID]);
}

function get_profile_users_liked_posts($userID) {
    $values['UserID'] = $userID;
    $query = <<<query
    SELECT POST_T.PostID, Title, POST_T.Content, POST_T.Created AS PostCreated, POST_T.Modified AS PostModified, USER_T.UserID, Username, Avatar, UNIVERSITY_T.UniversityID, UNIVERSITY_T.Name AS UniversityName, UNIVERSITY_T.Abbreviation, SUBJECT_T.Name AS SubjectName, COUNT(DISTINCT CommentID) AS Comments, COALESCE((SELECT COUNT(*) FROM POST_LIKE_T WHERE PostID = POST_T.PostID AND VoteType = 1), 0) AS Likes
    FROM POST_T 
        INNER JOIN USER_T ON POST_T.UserID = USER_T.UserID
        INNER JOIN UNIVERSITY_T ON POST_T.UniversityID = UNIVERSITY_T.UniversityID
        INNER JOIN SUBJECT_T ON POST_T.SubjectID = SUBJECT_T.SubjectID
        LEFT OUTER JOIN COMMENT_T ON COMMENT_T.PostID = POST_T.PostID
    WHERE POST_T.PostID IN (SELECT PostID FROM POST_LIKE_T WHERE UserID = :UserID AND VoteType = 1) AND POST_T.UserID != :UserID
    GROUP BY POST_T.PostID
    ORDER BY POST_T.Created DESC
    query;
    return run_database($query, $values = ['UserID' => $userID]);
}

?>