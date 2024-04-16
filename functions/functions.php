<?php
//////////* Functions - Contains general functions used by many pages  *//////////
date_default_timezone_set('America/Los_Angeles');
session_start();
update_session();

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
    if (isset($_SESSION['USER']) && $_SESSION['USER']->Verified == 0) {
        if (!($_SERVER['REQUEST_URI'] == "/account/verify")) {
            header("Location: /account/verify.php");
        }
        $query = "SELECT Expires FROM CODE_T WHERE Email = '{$_SESSION['USER']->Email}';";
        $expirationTime = run_database($query)[0]->Expires; 
        if (time() > $expirationTime) {
            $query = "DELETE FROM USER_T Where Email = '{$_SESSION['USER']->Email}';";
            run_database($query);
            header("Location: /account/logout.php");
        }
        return $expirationTime;
    }
    else if (isset($_SESSION['USER'])) {
        update_user();
    }
}

function update_user() {   
    $query = "SELECT USER_T.*, Abbreviation FROM USER_T LEFT OUTER JOIN UNIVERSITY_T ON USER_T.UniversityID = UNIVERSITY_T.UniversityID WHERE UserID = {$_SESSION['USER']->UserID} LIMIT 1;";
    $_SESSION['USER'] = run_database($query)[0];
    $_SESSION['LOGGED_IN'] = true;
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

function check_account_dir() {
    $pages = array("forgot", "login", "register");
    foreach ($pages as $page) {
        if (str_contains($_SERVER['REQUEST_URI'], $page)) {
            echo "active";
            break;
        }
    }
}

function background_check_active($toCheck, $page = null) {
    if (str_contains($_SERVER['REQUEST_URI'], $toCheck) && $page == null) echo "active-background";
    else if ($_SERVER['REQUEST_URI'] == '/' && $page == 'home') echo "active-background";
}

function redirect($directory) {
    echo "<script>window.location.href = '$directory.php';</script>'";
}

function console_log($string) {
    echo "<script>console.log('$string')</script>'";
}

function university_redirect() {
    $location = isset($_SESSION['USER']->Abbreviation) ? "university/{$_SESSION['USER']->Abbreviation}" : "university";
    header("Location: /$location.php");
}

function get_end_url() {
    return isset($_GET['url']) ? basename($_GET['url'], '.php') : 'default';
}

function display_errors($errors) {
    if(count($errors) > 0) {
        foreach($errors as $errors) {
            echo "<p>$errors</p>";
        }
    }
}

function generate_ID($type) {
    do {
        $createdID = rand(10000, 99999);
        switch ($type) {
            case 'USER':
                $createdID = prepend(9, $createdID);
                $query = "SELECT * FROM USER_T WHERE UserID = :createdID limit 1";
                break;
            case 'STUDY_SET':
                $createdID = prepend(8, $createdID);
                $query = "SELECT * FROM STUDY_SET_T WHERE StudySetID = :createdID limit 1";
                break;
            case 'POST':
                $createdID = prepend(7, $createdID);
                $query = "SELECT * FROM POST_T WHERE PostID = :createdID limit 1";
                break;
            case 'COMMENT':
                $createdID = prepend(6, $createdID);
                $query = "SELECT * FROM COMMENT_T WHERE CommentID = :createdID limit 1";
                break;
            default:
                throw new Exception("Invalid ID type specified.");
                break;
        }

        $result = run_database($query, [':createdID' => $createdID]);
        
    } while (!empty($result));

    return $createdID;
}

function prepend ($var1, $var2){ 
    return $var1 . $var2;
}

function get_universities_list() {
    $query = "SELECT UniversityID, Name, Abbreviation FROM UNIVERSITY_T ORDER BY Name ASC";
    return run_database($query);
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

//Get users main university name given the users ID.
function get_user_university_name() {
    $values['UserID'] = $_SESSION['USER']->UserID;
    $query = <<<query
    SELECT UNI.Name
    FROM UNIVERSITY_T UNI
    INNER JOIN USER_T U ON U.UniversityID = UNI.UniversityID
    WHERE U.UserID = :UserID;
    query;
    $result = run_database($query, $values);
    if (is_array($result)) {
        return $result[0]->Name;
    }
    else {
        return "";
    }
}

//Get users manin university abbreviation given the users ID.
function get_user_university_abbreviation() {
    $values['UserID'] = $_SESSION['USER']->UserID;
    $query = <<<query
    SELECT UNI.Abbreviation
    FROM UNIVERSITY_T UNI
    INNER JOIN USER_T U ON U.UniversityID = UNI.UniversityID
    WHERE U.UserID = :UserID;
    query;
    $result = run_database($query, $values);
    if (is_array($result)) {
        return $result[0]->Abbreviation;
    }
    else {
        return "";
    }
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

function get_recent_university_posts($universityID) {
    $query = <<<query
    SELECT POST_T.PostID, Title, POST_T.Content, POST_T.Created AS PostCreated, POST_T.Modified AS PostModified, USER_T.UserID, Username, Avatar, UNIVERSITY_T.UniversityID, UNIVERSITY_T.Name AS UniversityName, UNIVERSITY_T.Abbreviation, SUBJECT_T.Name AS SubjectName, COUNT(DISTINCT CommentID) AS Comments, COALESCE((SELECT COUNT(*) FROM POST_LIKE_T WHERE PostID = POST_T.PostID AND VoteType = 1), 0) AS Likes
    FROM POST_T 
        INNER JOIN USER_T ON POST_T.UserID = USER_T.UserID
        INNER JOIN UNIVERSITY_T ON POST_T.UniversityID = UNIVERSITY_T.UniversityID
        INNER JOIN SUBJECT_T ON POST_T.SubjectID = SUBJECT_T.SubjectID
        LEFT OUTER JOIN COMMENT_T ON COMMENT_T.PostID = POST_T.PostID
    WHERE POST_T.UniversityID = :UniversityID
    GROUP BY POST_T.PostID
    ORDER BY POST_T.Created DESC LIMIT 10
    query;
    return run_database($query, $values = ['UniversityID' => $universityID]);
}

function save_to_cookie($type) {
    $urlPath = $_SERVER['REQUEST_URI']; // e.g., "/posts/6969"
    $segments = explode('/', $urlPath);

    switch ($type) {
        case 'post':
            $postId = end($segments); // grab the end segement
            // Verify that the post ID is valid
            $post = get_post($postId);
            if ($post) {
            // Check if cookie exists
            if (isset($_COOKIE['viewed_posts'])) {
                $viewedPosts = explode(',', $_COOKIE['viewed_posts']);   // Get array of viewed post IDs
                // Check if post ID already exists in array
                if (($key = array_search($postId, $viewedPosts)) !== false) {
                    unset($viewedPosts[$key]);    // Remove existing post ID from array
                }
                array_unshift($viewedPosts, $postId);     // Add new post ID to the start of the array
                $viewedPosts = array_slice($viewedPosts, 0, 5);    // Limit array to last 5 post IDs
            } else {
                $viewedPosts = array($postId);    // Create new array with the post ID
            }
            // Update cookie
            setcookie('viewed_posts', implode(',', $viewedPosts), time() + (86400 * 3652.5), "/"); // Expires in 10 years
            }
            break;
        case 'study-set':
            $studySetId = end($segments); // grab the end segement
            // Verify that the study set ID is valid
            $studySet = get_study_set($studySetId);
            if ($studySet) {
                // Check if cookie exists
                if (isset($_COOKIE['viewed_study_sets'])) {
                    $viewedStudySets = explode(',', $_COOKIE['viewed_study_sets']);   // Get array of viewed study set IDs
                    // Check if Study Set ID already exists in array
                    if (($key = array_search($studySetId, $viewedStudySets)) !== false) {
                        unset($viewedStudySets[$key]);    // Remove existing Study Set ID from array
                    }
                    array_unshift($viewedStudySets, $studySetId);     // Add new study set ID to the start of the array
                    $viewedStudySets = array_slice($viewedStudySets, 0, 5);    // Limit array to last 5 Study Set IDs
                } else {
                    $viewedStudySets = array($studySetId);    // Create new array with the study set ID
                }
                // Update cookie
                setcookie('viewed_study_sets', implode(',', $viewedStudySets), time() + (86400 * 3652.5), "/"); // Expires in 10 years
            }
            break;
    }
}

function get_recent_posts($postIDs) {
    for ($i = 0; $i < 5; $i++) { 
        if (!isset($postIDs[$i])) {
            $postIDs[$i] = 0;
        }
    }

    $query = <<<query
    SELECT POST_T.PostID, Title, POST_T.Content, POST_T.Created AS PostCreated, POST_T.Modified AS PostModified, USER_T.UserID, Username, Avatar, UNIVERSITY_T.UniversityID, UNIVERSITY_T.Name AS UniversityName, UNIVERSITY_T.Abbreviation, SUBJECT_T.Name AS SubjectName, COUNT(DISTINCT CommentID) AS Comments, COALESCE((SELECT COUNT(*) FROM POST_LIKE_T WHERE PostID = POST_T.PostID AND VoteType = 1), 0) AS Likes
    FROM POST_T 
        INNER JOIN USER_T ON POST_T.UserID = USER_T.UserID
        INNER JOIN UNIVERSITY_T ON POST_T.UniversityID = UNIVERSITY_T.UniversityID
        INNER JOIN SUBJECT_T ON POST_T.SubjectID = SUBJECT_T.SubjectID
        LEFT OUTER JOIN COMMENT_T ON COMMENT_T.PostID = POST_T.PostID
    WHERE POST_T.PostID IN ({$postIDs[0]}, {$postIDs[1]}, {$postIDs[2]}, {$postIDs[3]}, {$postIDs[4]})
    GROUP BY POST_T.PostID
    ORDER BY FIELD(POST_T.PostID, {$postIDs[0]}, {$postIDs[1]}, {$postIDs[2]}, {$postIDs[3]}, {$postIDs[4]})
    query;
    return run_database($query);
}

function get_recent_study_sets($studySetIDs) {
    for ($i = 0; $i < 5; $i++) { 
        if (!isset($studySetIDs[$i])) {
            $studySetIDs[$i] = 0;
        }
    }

    $query = <<<query
    SELECT STUDY_SET_T.StudySetID, Title, Description, STUDY_SET_T.Created AS SetCreated, Username, Avatar, UNIVERSITY_T.Name AS UniversityName, COURSE_T.Abbreviation AS Course, COUNT(DISTINCT CommentID) AS Comments,
    COALESCE((SELECT AVG(Rating) FROM STUDY_SET_RATINGS WHERE StudySetID = STUDY_SET_T.StudySetID), 0) AS Rating
    FROM STUDY_SET_T INNER JOIN USER_T ON STUDY_SET_T.UserID = USER_T.UserID
        INNER JOIN COURSE_T ON COURSE_T.CourseID = STUDY_SET_T.CourseID
        INNER JOIN SUBJECT_T ON SUBJECT_T.SubjectID = COURSE_T.SubjectID
        INNER JOIN UNIVERSITY_T ON UNIVERSITY_T.UniversityID = SUBJECT_T.UniversityID
        LEFT OUTER JOIN COMMENT_T ON COMMENT_T.StudySetID = STUDY_SET_T.StudySetID
    WHERE STUDY_SET_T.StudySetID IN ({$studySetIDs[0]}, {$studySetIDs[1]}, {$studySetIDs[2]}, {$studySetIDs[3]}, {$studySetIDs[4]})
    GROUP BY STUDY_SET_T.StudySetID 
    ORDER BY FIELD(STUDY_SET_T.StudySetID, {$studySetIDs[0]}, {$studySetIDs[1]}, {$studySetIDs[2]}, {$studySetIDs[3]}, {$studySetIDs[4]})
    query;
    return run_database($query);
}

?>