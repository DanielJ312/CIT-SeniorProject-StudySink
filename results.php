<?php
//////////* Results - Displays search results based on user prompt */////////
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions/search-functions.php");
$searchTerm = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';

// Run search functions for each type of search
$studySets = search_study_sets($searchTerm);
$posts = search_posts($searchTerm);
$users = search_users($searchTerm);
$universities = search_universities($searchTerm);

$pageTitle = "Results for " . '"' . $searchTerm . '"';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
    <link rel="stylesheet" href="../styles/results.css" />
</head>
<body>
    <header>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/to-top.php"); ?>
    </header>
    <main>
        <div class="margin">
            <div class="university-info">
                <h2>Search Results for "<?= $searchTerm ?>"</h2>
            </div>
            <div class="column">
                <div class="study-set">
                    <div class="header">
                    <h2 id="toggleSet">Study Sets<i class="down"></i></h2>
                        <!-- Sorting options can be added here if needed -->
                    </div>
                    <div class="scrollbar" id="contentset">
                        <div class="displayCardArea">
                            <?php if ($studySets && count($studySets) > 0) : ?>
                                <?php foreach ($studySets as $set) : ?>
                                    <div class="cardContainer">
                                        <a href="/study-sets/<?= htmlspecialchars($set->StudySetID); ?>">
                                            <div class="cardHeaderTopLeft">
                                                <img src="<?= htmlspecialchars($set->Avatar); ?>" alt="<?= htmlspecialchars($set->Username); ?>'s avatar" class="profile-picture" />
                                                <div class="cardHeaderUsernameDate">
                                                    <p><?= isset($set->Username) ? htmlspecialchars($set->Username) : 'Unknown User'; ?></p>
                                                    <p><?= isset($set->Created) ? date('F j, Y', $set->Created) : 'Unknown Date'; ?></p>
                                                </div>
                                            </div>
                                            <div class="studySetDetailsBottom">
                                                <div class="studySetDetailsBottomLeft">
                                                    <h3><?= htmlspecialchars($set->Title); ?></h3>
                                                </div>
                                                <div class="studySetDetailsBottomRight">
                                                    <p><?= htmlspecialchars($set->UniversityName); ?></p>
                                                    <p><?= htmlspecialchars($set->CourseAbbreviation); ?></p>
                                                </div>
                                                <div class="lower-header">
                                                    <div class="comment">
                                                        <div class="post-iconsp">
                                                            <i class="fa-regular fa-comment"></i>
                                                        </div>
                                                        <div class="comments-count"><?= $set->Comments; ?></div>
                                                    </div>
                                                    <div class="vote">
                                                        <div class="post-iconsp">
                                                            <i class="fa-regular fa-star" aria-hidden="true"></i>
                                                        </div>
                                                        <div class="votes"><?= round($set->Rating, 1); ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <p>No study sets found.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="posts">
                    <div class="header">
                    <h2 id="togglePost">Posts<i class="down"></i></h2>
                        <!-- Sorting options can be added here if needed -->
                    </div>
                    <div class="scrollbar" id="contentpost">
                        <?php if ($posts && count($posts) > 0) : ?>
                            <?php foreach ($posts as $post) : ?>
                                <div class="post">
                                    <a href="/posts/<?= htmlspecialchars($post->PostID); ?>">
                                        <div class="post-header">
                                            <img src="<?= htmlspecialchars($post->Avatar); ?>" alt="<?= htmlspecialchars($post->Username); ?>'s avatar" class="post-profile-picture" />
                                            <div class="post-info">
                                                <p class="post-account"><?= htmlspecialchars($post->Username); ?></p>
                                                <p class="post-date"><?= date("F j, Y", $post->Created); ?></p>
                                            </div>
                                        </div>
                                        <h3 class="post-title"><?= htmlspecialchars($post->Title); ?></h3>
                                        <div class="post-content"><?= htmlspecialchars(substr($post->Content, 0, 100)) . '...'; ?></div>
                                        <div class="lower-header">
                                            <div class="comment">
                                                <div class="post-iconsp">
                                                    <i class="fa-regular fa-comment"></i>
                                                </div>
                                                <div class="comments-count"><?= $post->Comments; ?></div>
                                            </div>
                                            <div class="vote">
                                                <div class="post-iconsp">
                                                    <i class="fa-regular fa-heart"></i>
                                                </div>
                                                <div class="votes"><?= $post->Likes; ?></div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <p>No posts found.</p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="users">
                    <div class="header">
                        <h2 id="toggleUsers">Users<i class="down"></i></h2>
                        <!-- Sorting options can be added here if needed -->
                    </div>
                    <div class="scrollbar" id="contentuser">
                        <?php if ($users && count($users) > 0) : ?>
                            <?php foreach ($users as $user) : ?>
                                <div class="userCardContainer">
                                    <a href="/account/<?= htmlspecialchars($user->Username); ?>">
                                        <div class="userCardHeaderTopLeft">
                                            <img src="<?= htmlspecialchars($user->Avatar); ?>" class="profile-picture" />
                                            <div class="userCardHeaderUsernameDate">
                                                <p class="post-account"><?= htmlspecialchars($user->Username); ?></p>
                                                <p>Joined: <?= isset($user->Created) ? date('F j, Y', $user->Created) : 'Unknown Date'; ?></p>
                                            </div>
                                        </div>
                                        <div class="userDetailsBottom">
                                            <?php if (!is_null($user->UniversityName) && !is_null($user->UniversityAbbreviation)) : ?>
                                                <div class="userDetailsBottomLeft">
                                                    <h3 class="post-account"><?= htmlspecialchars($user->UniversityName); ?></h3>
                                                </div>
                                                <div class="userDetailsBottomRight">
                                                    <p class="post-account"><?= htmlspecialchars($user->UniversityAbbreviation); ?></p>
                                                </div>
                                            <?php endif; ?>
                                            <!--
                                            <div class="userBio">
                                                <?php if (!is_null($user->Bio)) : ?>
                                                    <p class="post-title"><?= htmlspecialchars($user->Bio); ?></p>
                                                <?php endif; ?>
                                            </div>
                                            -->
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <p>No users found.</p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="universities">
                    <div class="header">
                        <h2 id="toggleUniversities">Universities<i class="down"></i></h2>
                        <!-- Sorting options can be added here if needed -->
                    </div>
                    <div class="scrollbar" id="contentuniversities">
                        <?php if ($universities && count($universities) > 0) : ?>
                            <?php foreach ($universities as $university) : ?>
                                <div class="userCardContainer">
                                    <a href="/university/<?= htmlspecialchars($university->UniversityAbbreviation); ?>">
                                        <div class="userCardHeaderTopLeft">
                                        <img src="<?= htmlspecialchars($university->UniversityLogo); ?>" class="profile-picture" />
                                            <div class="userCardHeaderUsernameDate">
                                                <h2 class="post-account"><?= htmlspecialchars($university->UniversityName); ?></h2>
                                            </div>
                                        </div>
                                        <div class="userDetailsBottom">
                                            <div class="userDetailsBottomLeft">
                                                <h3 class="post-account"><?= htmlspecialchars($university->UniversityAbbreviation); ?></h3>
                                            </div>
                                            <div class="userDetailsBottomRight">
                                               <!-- <p class="post-account"><?= htmlspecialchars($user->UniversityAbbreviation); ?></p> -->
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <p>No universities found.</p>
                        <?php endif; ?>
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
    document.addEventListener('DOMContentLoaded', function() {
    var contentSet = document.getElementById('contentset');
    var contentPost = document.getElementById('contentpost');
    var contentUsers = document.getElementById('contentuser');
    var contentUniversities = document.getElementById('contentuniversities');

    document.getElementById('toggleSet').addEventListener('click', function() {
        if (window.innerWidth <= 850) {
            contentSet.style.display = (contentSet.style.display === 'none' || window.getComputedStyle(contentSet).display === 'none') ? 'block' : 'none';
        }
    });

    document.getElementById('togglePost').addEventListener('click', function() {
        if (window.innerWidth <= 850) {
            contentPost.style.display = (contentPost.style.display === 'none' || window.getComputedStyle(contentPost).display === 'none') ? 'block' : 'none';
        }
    });

    document.getElementById('toggleUsers').addEventListener('click', function() {
        if (window.innerWidth <= 850) {
            contentUsers.style.display = (contentUsers.style.display === 'none' || window.getComputedStyle(contentUsers).display === 'none') ? 'block' : 'none';
        }
    });

    document.getElementById('toggleUniversities').addEventListener('click', function() {
        if (window.innerWidth <= 850) {
            contentUniversities.style.display = (contentUniversities.style.display === 'none' || window.getComputedStyle(contentUniversities).display === 'none') ? 'block' : 'none';
        }
    });

    var gridItems = document.querySelectorAll('.post-content');
    gridItems.forEach(function(item) {
        var text = item.textContent;
        if (text.length > 50) {
            item.textContent = text.substring(0, 50) + '...';
        }
    });

    const userSection = document.getElementById('contentuser');
    const universitySection = document.getElementById('contentuniversities');

    if(userSection.textContent.includes('No users found')) {
        document.querySelector('.users').style.display = 'none';
    }

    if(universitySection.textContent.includes('No universities found')) {
        document.querySelector('.universities').style.display = 'none';
    }

});

</script>
</html>
