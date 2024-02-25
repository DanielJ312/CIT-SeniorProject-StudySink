<?php
# Functions - Contains functions that are used by multiple pages
date_default_timezone_set('America/Los_Angeles');
session_start();

function run_database($query, $values = array()) {
    $connection = get_pdo_connection();
    if (!$connection) return false;

    $statement = $connection->prepare($query);
    $check = $statement->execute($values);

    if ($check) {
        $data = $statement->fetchAll((PDO::FETCH_OBJ));
        if (count($data) > 0) return $data;
    }
    return false;
}

function get_pdo_connection() {
    static $connection = null;
    if ($connection === null) {
        $database = read_config();
        $server = "mysql:host={$database['db_host']};port={$database['db_port']};dbname={$database['db_name']};";
        $connection = new PDO($server, $database['db_username'], $database['db_password']);
    }
    return $connection;
}

function read_config(){
    return parse_ini_file($_SERVER['DOCUMENT_ROOT'] . "/config.ini");
}

function check_login() {
    $loggedIn = false;
    if (isset($_SESSION['USER']) && isset($_SESSION['LOGGED_IN'])) $loggedIn = true;
    return $loggedIn;
}

function update_session() {
    if (isset($_SESSION['USER'])) {
        $values = array();
        $values['UserID'] = $_SESSION['USER']->UserID;
    
        $query = "SELECT * FROM USER_T WHERE UserID = :UserID LIMIT 1;";
        $result = run_database($query, $values);
        $result = $result[0];
    
        $_SESSION['USER'] = $result;
        $_SESSION['LOGGED_IN'] = true;
    }
}

function check_verification() {
    $userid = $_SESSION['USER']->UserID;
    $query = "SELECT * FROM USER_T where UserID = '$userid' LIMIT 1;";
    $result = run_database($query);
    if (is_array($result)) {
        $result = $result[0];
        if ($result->Verified == 1) return true;
    }
    return false;
}

function check_active($toCheck, $page = null) {
    if (str_contains($_SERVER['REQUEST_URI'], $toCheck) && $page == null) echo "active";
    else if ($_SERVER['REQUEST_URI'] == '/' && $page == 'home') echo "active";
}

function display_errors($errors) {
    if(count($errors) > 0) {
        foreach($errors as $errors) {
            echo "<p>$errors</p>";
        }
    }
}

function get_local_time() {
    return (time() - (60*60*7));
}

function display_time($time, $format) {
    return (new DateTime("@$time"))->format("$format");
}

function generate_ID($type) {
    do {
        $createdID = '';
        switch ($type) {
            case 'USER':
                $createdID = rand(101, 999);
                $query = "SELECT * FROM USER_T WHERE UserID = :createdID limit 1";
                break;
            case 'STUDY_SET':
                $createdID = get_local_time(); //+ mt_rand(1000, 9999)
                $query = "SELECT * FROM STUDY_SET_T WHERE StudySetID = :createdID limit 1";
                break;
            default:
                throw new Exception("Invalid ID type specified.");
                break;
        }

        $result = run_database($query, [':createdID' => $createdID]);
        
    } while (!empty($result));

    return $createdID;
}

function check_set_title($pageTitle) {
    return isset($pageTitle) ? $pageTitle : "Page Header";
}

function get_universities_list() {
    $query = "SELECT UniversityID, Name FROM UNIVERSITY_T ORDER BY Name ASC";
    return run_database($query);
}

function check_user_vote($userID, $commentID) {
    $query = "SELECT VoteType FROM CVOTE_T WHERE CommentID = $commentID AND UserID = $userID;";
    $result = run_database($query);
    if (is_array($result) && !$result[0]->VoteType == 0) {
        return $result[0]->VoteType;
    }
}

//New function to get study set info from db.
function get_study_set($StudySetID) {
    $values['StudySetID'] = $StudySetID;
    $query = <<<query
    SELECT S.StudySetID, U.Username, S.CourseID, S.Title, S.Description, S.Instructor, S.Created, U.Avatar, count(CommentID) as Comments
    FROM STUDY_SET_T S INNER JOIN USER_T U ON S.UserID = U.UserID
    LEFT OUTER JOIN COMMENT_T C ON C.StudySetID = S.StudySetID
    WHERE S.StudySetID = :StudySetID;
    query;
    return run_database($query, $values)[0];
}

//Get users main university ID given the users ID.
function get_user_university() {
    $values['UserID'] = $_SESSION['USER']->UserID;
    $query = <<<query
    SELECT UniversityID
    FROM USER_T
    WHERE UserID = :UserID;
    query;
    return run_database($query, $values)[0]->UniversityID;
}

//Get 10 most recently created University posts based on user's set university.
function get_recent_university_post_IDs($universityID) {
    $query = "SELECT PostID FROM POST_T WHERE UniversityID = :UniversityID ORDER BY Created DESC LIMIT 10";
    $result = run_database($query, $values = ['UniversityID' => $universityID]);
    $postIDs = [];
    foreach ($result as $row) {
        $postIDs[] = $row->PostID;
    }

    return $postIDs;
}
?>