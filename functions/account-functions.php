<?php
# Functions - Contains functions relating to AWS S3
require($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

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

    if (isset($file['image']) && $file['image']['error'] === UPLOAD_ERR_OK) {
        $file_path = $file['image']['tmp_name'];
        $key = "avatars/{$_SESSION['USER']->UserID}_{$_SESSION['USER']->Username}_" . (time() - (60 * 60 * 7)) . ".png";

        try {
            $result = $s3->putObject([
                'Bucket' => $credentials['s3_bucket_name'],
                'Key' => $key,
                'SourceFile' => $file_path,
                'ACL' => 'public-read',
            ]);

            $values['UserID'] = $_SESSION['USER']->UserID;
            $values['Avatar'] = $result['ObjectURL'];
            $query = "UPDATE USER_T SET Avatar = :Avatar WHERE UserID = :UserID";
            run_database($query, $values);
            update_session();
        } catch (S3Exception $e) {
            echo "There was an error uploading the file: " . $e->getMessage();
        }
    } else {
        echo "Error: Please select a valid image file.";
    }
}

function signup($data) {
    $errors = array();

    // validate
    if (!preg_match('/^[a-zA-Z0-9]+$/', $data['username'])) {
        $errors[] = "Please enter a valid username.";
    }
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email.";
    }
    if (strlen(trim($data['password'])) < 4) {
        $errors[] = "Please enter a valid password.";
    }
    else if ($data['password'] != $data['password2']) {
        $errors[] = "Passwords must match.";
    }
    $checkEmail = run_database("SELECT * FROM USER_T WHERE Email = :Email LIMIT 1;",['Email'=>$data['email']]);
    if (is_array($checkEmail)) {
        $errors[] = "Email already exists.";
    }
    
    // save
    if (count($errors) == 0) {
        $values = [
            'UserID' => generate_ID("USER"),
            'Username' => $data['username'],
            'Email' => $data['email'],
            'Password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'Created' => get_local_time()
        ];
        
        $query = "INSERT INTO USER_T (UserID, Username, Email, Password, Created) VALUES (:UserID, :Username, :Email, :Password, :Created);";
        run_database($query, $values);
    }

    return $errors;
}


function login($data) {
    $errors = array();
    $loginType = "Email";
    
    if (filter_var($data['logininput'], FILTER_VALIDATE_EMAIL)) {
        $loginType = "Email";
    }
    else if (preg_match('/^[a-zA-Z0-9]+$/', $data['logininput'])) {
        $loginType = "Username";
    }
    else {
        $errors[] = "Please enter a valid email or username.";
    }
    if (strlen(trim($data['password'])) < 4) {
        $errors[] = "Please enter a valid password.";
    }

    // check
    if (count($errors) == 0) {
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
        $password = $data['password'];

        $query = "SELECT * FROM USER_T WHERE $loginType = :$loginType LIMIT 1;";
        $result = run_database($query, $values);
        

        if (!empty($result)) {
            $result = $result[0];
            if (password_verify($password, $result->Password)) {
                $_SESSION['USER'] = $result;
                $_SESSION['LOGGED_IN'] = true;
            } else {
                $errors[] = "Incorrect password.";
            }
        } else {
            $errors[] = "An account associated with this email does not exist.";
        }
    }

    return $errors;
}

function check_email($data) {
    $errors = array();

    // validate
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email.";
    }
    else {
        $values['Email'] = $data['email'];
        $query = "SELECT * FROM USER_T WHERE Email = :Email LIMIT 1;";
        $result = run_database($query, $values);
        if (!is_array($result)) {
            $errors[] = "There is no account associated with the email entered.";
        }
    } 
    
    return $errors;
}

function reset_password($data) {
    $errors = array();

    $values = array();
    $values['Code'] = $data['code'];
    $query = "SELECT * FROM CODE_T WHERE Code = :Code LIMIT 1;";
    $result = run_database($query, $values);
        
    if (is_array($result)) {
        if (get_local_time() > $result[0]->Expires) {
            $errors[] = 'Your code has expired so a new one has been sent. Please enter the new one.';
            is_code_active("reset", $result[0]->Email);
        }
        else if (strlen(trim($data['password'])) < 4) {
            $errors[] = "Please enter a valid password.";
        }
        else if ($data['password'] != $data['password2']) {
            $errors[] = "Passwords must match.";
        }
        if (count($errors) == 0) {
            $result = $result[0];
            $values = array();
            $values['Email'] = $result->Email;
            $values['Password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            echo "$result->Email";
            // die;
            $query = "UPDATE USER_T SET Password = :Password WHERE Email = :Email";
            run_database($query, $values);
            delete_code("reset", $result->Email);
        }
    }
    else {
        $errors[] = "Verifcation code is incorrect.";
    }

    return $errors;
}

function verify_account() {
    $values = [
        'Email' => $_SESSION['USER']->Email,
        'Code' => $_POST['code']
    ];

    $query = "SELECT * FROM CODE_T where Email = :Email && Code = :Code;";
    $result = run_database($query, $values);
    if (is_array($result)) {
        $result = $result[0];

        if ($result->Expires > get_local_time()) {
            $email = $result->Email;
            $query = "UPDATE USER_T SET Verified = 1 WHERE Email = '$email' LIMIT 1;";
            $result = run_database($query);
            delete_code("verify", $email);
            header("Location: profile.php");
            die;
        } else {
            $errors[] = "Code expired";
        }
    } else {
        $errors[] = "Wrong code.";
    }

    return $errors;
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

function get_universities() {
    $query = "SELECT Name FROM UNIVERSITY_T";
    $result = run_database($query);
    return $result;
}

function update_primary_university($data) {
    // Fetch the UniversityID from the UNIVERSITY_T table
    $values = ['UniversityName' => $data['updateUniversity']];
    $query = "SELECT UniversityID FROM UNIVERSITY_T WHERE Name = :UniversityName";
    $result = run_database($query, $values);
    $primaryUniversityID = $result[0]->UniversityID;

    // Use the UniversityID to update the USER_T table
    $values = [
        'UserID' => $_SESSION['USER']->UserID,
        'UniversityID' => $primaryUniversityID
    ];
    $query = "UPDATE USER_T SET UniversityID = :UniversityID WHERE UserID = :UserID";
    run_database($query, $values);
    update_session();
}

function delete_account($data) {
    $values = [
        'UserID' => $_SESSION['USER']->UserID
    ];
    // Posts created by the user will have there POST_T.UserID changed to DeletedUser's UserID
    //Post votes creted by the user will have there VOTE_T.UserID changed to DeletedUser's UserID
    //Study Sets created by the user will have there STUDY_SET_T.UserID changed to DeletedUser's UserID
    //Study Set Ratings created by the user will have there STUDY_SET_RATINGS.UserID changed to DeletedUser's UserID
    //Comments created by the user will have there COMMENT_T.UserID changed to DeletedUser's UserID
    //Comment votes created by the user will have there CVOTE_T.UserID changed to DeletedUser's UserID
    //The user will have there row deleted from USER_T WHERE USER_T.UserID = :UserID
    $deleteScript = "DELETE FROM USER_T WHERE UserID = :UserID";
    run_database($deleteScript, $values);
    session_destroy();
    header("Location: /");
    die;
}

?>