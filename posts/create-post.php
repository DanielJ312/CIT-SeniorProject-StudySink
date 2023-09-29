<?php 
$pageTitle = "Post Creation Page";
include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php");

$errors = array();
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $errors = create_post($_POST);

    if (count($errors) == 0) {
        // header("Location: profile.php");
        // die;
    }
}

?>

<h2><?=isset($pageTitle) ? $pageTitle : "Page Header" ?></h2>
<div>
    <div>
        <?php display_errors($errors); ?>
    </div>
    <form method="post">
        <p>Title: <input type="text" name="title"></p>
        <p>Content: <textarea name="content" rows="5" cols="40"></textarea></p>
        <input type="submit" value="Submit">
    </form>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>

<?php 
function create_post($data) {
    $errors = array();

    if(empty($data['title'])) {
        $errors[] = "Please enter a post title.";
    }
    if(empty($data['content'])) {
        $errors[] = "Please enter content for the post.";
    }

    if (count($errors) == 0) {
        $values['postID'] = rand(100, 999);
        $values['title'] = $data['title'];
        $values['content'] = $data['content'];
        $values['author'] = $_SESSION['USER']->userid;
        $values['created'] = get_local_time();

        $query = "INSERT INTO post_t (postID,title, content, author, created) VALUES (:postID, :title, :content, :author, :created)";
        run_database($query, $values);

        // copy("413.php","{$values['postID']}.php");
        header("Location: {$values['postID']}.php");
    }

    return $errors;
}

?>