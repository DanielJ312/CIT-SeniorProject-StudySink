<!-- Post Template - Displays post for given Post ID  -->
<?php
// require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/forum-functions.php");
update_session();
$postID = isset($_GET['url']) ? basename($_GET['url'], '.php') : 'default';
$post = get_post($postID);
if (empty($post)) {
    header("Location: " . (isset($_SESSION['USER']->Abbreviation) ? "/university/{$_SESSION['USER']->Abbreviation}.php" : "/index.php"));
}

$commentTotal = count_comments($postID);
$likeTotal = get_likes($postID);
$pageTitle = "$post->Title";

save_to_cookie("post");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
    <script async src="/posts/forum.js"></script>
    <link rel="stylesheet" href="/styles/forum/post-template.css" />
</head>
<body class="post-template-body">
    <header>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/to-top.php"); ?>
    </header>
    <main class="post-body">
        <div class="margin">
            <div class="university-info">
                <h2><?= $post->UniversityName; ?></h2>
                <?php if (isset($post->SubjectName)) : ?>
                    <h3><?= $post->SubjectName; ?></h3>
                <?php endif; ?>
            </div>
            <div class="posts">
                <div class="post">
                    <div class="post-header">
                        <img src="<?= $post->Avatar; ?>" title="<?= $post->Username; ?>" alt="Place Holder" class="profile-picture" />
                        <div class="post-info">
                            <p class="post-account"><?= $post->Username; ?></p>
                            <p class="post-date">
                                <?php if (!isset($post->PostModified)): ?>
                                    <span class="posted"><?= date("F j, Y  h:i A", $post->PostCreated); ?></span>
                                <?php else: ?>
                                    <span class="posted"><?= date("M j, Y", $post->PostCreated); ?><b> · </b></span>
                                    <span><i><span class="edited">edited on <?= date("M j, Y g:i A", $post->PostModified); ?></span></i></span>
                                <?php endif; ?>
                            </p>
                        </div>
                        <?php if (check_login()) : ?>
                            <div class="dropdown" onclick="toggleDropdown(this)">
                                <i class="fa-solid fa-ellipsis-vertical ellipsis-icon"></i>
                                <div class="dropdown-content">
                                    <?php if ($_SESSION['USER']->UserID != $post->UserID) : ?>
                                        <a class="report" onclick="ReportPost()">Report</a>
                                    <?php endif; ?>
                                    <?php if ($post->Username == $_SESSION['USER']->Username) : ?>
                                        <a onclick="OpenPostEditor()">Edit</a>
                                        <a onclick="openDeletePopup()">Delete</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <h3 class="post-title"><?= $post->Title; ?></h3>
                    <div class="post-content"><p class="content"><?= nl2br($post->Content); ?></p></div>
                    <div class="vote">
                        <div class="post-iconsp">
                            <?php if (check_login()) : ?>
                                <?php $userPVote = check_user_pvote($postID); ?>
                                <i class="like <?= $userPVote == 1 ? "fa-solid" : "fa-regular"; ?> fa-heart button fa-lg" onclick="updatePostLike()"></i>
                            <?php else : ?>
                                <a href="/account/login.php" style="color: #2778ff;"><i class="like fa-regular fa-heart button fa-lg" onclick=""></i></a>
                            <?php endif; ?>
                        </div>
                        
                        <div class="votes"><span class="post-votes"><?= isset($likeTotal) ? $likeTotal : "0"; ?></span></div>
                    </div>
                </div>
                <!-- Comments Section -->
                <div class="comments">
                    <div class="container">
                        <h4>Comments (<span class="comment-total"><?= $commentTotal; ?></span>)</h4>
                        <form id="sort-dropdown" method="">
                            <?= "<script>var parentID = $postID;</script>"; ?>
                            <select id="sort" class="sort" name="sorts">
                                <option value="comment-popular">Popular</option>
                                <option value="comment-oldest">Oldest</option>
                                <option value="comment-newest">Newest</option>
                            </select>
                        </form>
                    </div>
                    <?php if (check_login()) : ?>
                        <div id="add-comment">
                        <div class="comment-bar">
                            <textarea style="resize: auto; height: 30px; width: 612px;" id="commentinput" oninput="commentcountChar(this)"type="text" class="input-bar" placeholder="Add a comment..." name="content"></textarea>
                            <span id="commentcharCount"></span>
                            <button onclick="AddComment()" type="submit" value="Submit" class="addComment">Add</button>
                        </div>
                        </div>
                    <?php endif; ?>
                    <div class="comment-sort-container">
                        <!-- Comments will get inserted here -->
                    </div>
                </div>
            </div>
            <!-- Beginning of Create Forum Post Delete Pop up Window -->
<div id="forumBody">
    <div id="deletepopup">
        <form method="post">
            <div id="deletepopupContainer">
                <div class="contentitem">
                    <label for="universityforum" id="deletetitle">Are you sure you would like to DELETE post?</label>
                    <div class="buttons">
                    <button type="submit" onclick="closeDeletePopup()" class="canceldeletepostbutton">Cancel</button>
                    <button type="submit" onclick="DeletePost()" class="deletepostbutton">Delete</button>
                </div>
                </div>
            </div>
        </form>
    </div>
</div>
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>
</html>

