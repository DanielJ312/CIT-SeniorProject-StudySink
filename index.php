<!-- Home -->
<?php
require($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
update_session();
$pageTitle = "Home";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?>
</head>

<body>
    <header id="home-logout-header">
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
        <link rel="stylesheet" type="text/css" href="/styles/home/<?= !check_login() ? "logged-out" : "logged-in"; ?>.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Concert+One&display=swap" rel="stylesheet">
    </header>
    <main>
        <?php if (!check_login()) : ?>
            <div class="home-logout-body">
                <section class="home-logout-section" id="section0">
                    <div class="top-logo-buttons">
                        <img src="https://studysink.s3.amazonaws.com/assets/StudySinkBanner.png" alt="Company Logo" class="company-logo">
                        <p style="color: black; font-size: 2em;">A steady flow of information</p>
                        <button onclick="location.href='/account/login.php'" class="home-logout-btn">Login</button>
                        <button onclick="scrollToSection('section1')" class="home-logout-btn">More Information</button>
                    </div>
                </section>
                <section class="home-logout-section" id="section1" style="background-color: #fefefe;">
                    <div class="home-logout-content">
                        <div class="home-logout-text-container">
                            <p style="font-size: 1.2em;">Your College Journey, Elevated. Welcome to StudySink, where collaboration meets effective study tools. Unleash your potential, share your insights, and study smarter, not harder. StudySink merges the best of both worlds - a buzzing college hub and a dynamic flashcard creator.</p>
                        </div>
                        <img src="https://studysink.s3.amazonaws.com/assets/homepage-logout-pictures/create-study-sets.png" alt="Company Logo" width="850" height="950">
                    </div>
                </section>
                <section class="home-logout-section" id="section2" style="background-color: darkgrey;">
                    <div class="home-logout-content">
                        <img src="https://studysink.s3.amazonaws.com/assets/homepage-logout-pictures/StudySink-browse-posts.gif" alt="Browse Posts Gif" width="50%" height="50%">
                        <div class="home-logout-text-container">
                            <p style="font-size: 1.3em;">StudySink is your passport to a world where college insights and powerful study tools converge. Join the community, harness the collective knowledge, and create your path to success.</p>
                        </div>
                    </div>
                </section>
                <section class="home-logout-section" id="section3" style="background-color: #fefefe;">
                    <div class="home-logout-content">
                        <div class="home-logout-text-container">
                            <p style="font-size: 1.3em;">StudySink is where community meets efficacy. Engage in meaningful college discussions and supercharge your study sessions with personalized flashcards. Ready to take control of your success?</p>
                        </div>
                        <img src="https://studysink.s3.amazonaws.com/assets/homepage-logout-pictures/top-down-desk.jpg" alt="Top Down Desk" width="700" height="900">
                    </div>
                </section>
                <section class="home-logout-section" id="section4" style="background-color: darkgrey;">
                    <div class="home-logout-content">
                        <img src="https://studysink.s3.amazonaws.com/assets/homepage-logout-pictures/newFeatures.gif" alt="New Features" width="400" height="300">
                        <div class="home-logout-text-container">
                            <p style="font-size: 1.55em;">New Features are always on the way! Dark Mode, Direct Messaging, Post Editing, and Downloadable Material</p>
                        </div>
                    </div>
                </section>
                <section class="home-logout-section" id="section5" style="background-color: #fefefe;">
                    <div class="home-logout-content-bottom">
                        <div class="home-logout-text-container-bottom">
                            <p>Create your account now and embark on a transformative learning experience!</p>
                        </div>
                        <div class="bottom-btn-container">
                            <button onclick="location.href='/account/login.php'" class="home-logout-btn-bottom">Login</button>
                            <button onclick="scrollToSection('home-logout-header')" class="home-logout-btn-bottom">Back to Top</button>
                        </div>
                    </div>
                </section>
                <script>
                    //button to scroll to top functionality
                    function scrollToSection(sectionId) {
                        document.getElementById(sectionId).scrollIntoView({
                            behavior: 'smooth'
                        });
                    }
                </script>
            </div>
        <?php else : ?>
            <div>
                <div class="home-screen">
                    <!-- Left Container with My University Posts -->
                    <div class="left-container">
                        <div class="university-post-container">
                            <h2 class="home-container-title">My University</h2>
                            <div class="university-posts-tiles-container">
                                <?php
                                // Get an array of recently posted University post IDs
                                $userUniversity = get_user_university();
                                if (isset($userUniversity)) {
                                    $recentUniPostIDs = get_recent_university_post_IDs($userUniversity);
                                    // Display post tiles
                                    foreach ($recentUniPostIDs as $postId) {
                                        // Fetch the post data from the database
                                        $post = get_post($postId);
                                        //Display it if it is not empty
                                        if ($post) { ?>
                                            <div class="university-post-tile PostLinkTile" data-id="<?= $post->PostID; ?>">
                                                <div class="post-header">
                                                    <a href="account/profile.php"><img src="<?= $post->Avatar; ?>" alt="Place Holder" class="post-profile-picture" /></a>
                                                    <div class="post-info">
                                                        <a href="account/profile.php" class="post-account"><?= $post->Username; ?></a>
                                                        <p class="post-date"> <?= date('F j, Y', $post->PostCreated); ?> </p>
                                                    </div>
                                                </div>
                                                <h3 class="post-title"> <?= $post->Title; ?> </h3>
                                                <div class="post-content" style="margin-top: 2px;"> <?= $post->Content; ?> </div>
                                                <div class="bottom-of-tile">
                                                    <div class="comment">
                                                        <i class="fa-regular fa-comment"></i>
                                                        <div class="comments-count"><?= $post->Comments; ?></div>
                                                    </div>
                                                    <div class="vote">
                                                        <i class="fa-regular fa-heart"></i>
                                                        <div class="votes-count">20</div>
                                                    </div>
                                                </div>
                                            </div>
                                    <?php
                                        }
                                    }
                                } else { ?>
                                    <p>No Primary University Set</p>
                                    <button onclick="location.href='/account/settings.php#Primary-University';" class="setPUButton">Set a Primary University</button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <!-- Right Container -->
                    <div class="right-container">
                        <!-- Recently Viewed Posts within Right Container -->
                        <div class="recent-posts-container">
                            <h2 class="home-container-title">Recently Viewed Posts</h2>
                            <div class="post-tiles-container">
                                <?php
                                if (isset($_COOKIE['viewed_posts'])) {
                                    // Get array of viewed post IDs
                                    $viewedPosts = explode(',', $_COOKIE['viewed_posts']);
                                    // Display post tiles
                                    foreach ($viewedPosts as $postId) {
                                        // Fetch the post data from the database
                                        $post = get_post($postId);
                                        if ($post) { ?>
                                            <div class="post-tile PostLinkTile" data-id="<?= $post->PostID; ?>">
                                                <div class="post-header">
                                                    <a href="account/profile.php"><img src="<?= $post->Avatar; ?>" alt="Place Holder" class="post-profile-picture" /></a>
                                                    <div class="post-info">
                                                        <a href="account/profile.php" class="post-account"> <?= $post->Username; ?> </a>
                                                        <p class="post-date"> <?= date('F j, Y', $post->PostCreated); ?> </p>
                                                    </div>
                                                </div>
                                                <h3 class="post-title"> <?= $post->Title; ?> </h3>
                                                <div class="post-content"> <?= $post->Content; ?> </div>
                                                <div class="bottom-of-tile">
                                                    <div class="comment">
                                                        <i class="fa-regular fa-comment"></i>
                                                        <div class="comments-count"><?= $post->Comments; ?></div>
                                                    </div>
                                                    <div class="vote">
                                                        <i class="fa-regular fa-heart"></i>
                                                        <div class="votes-count">20</div>
                                                    </div>
                                                </div>
                                            </div> <?php
                                                }
                                            }
                                        } else {
                                            echo "No posts viewed yet";
                                        } ?>
                            </div>
                        </div>
                        <!-- Recently Viewed Study Sets within Right Container -->
                        <div class="recent-sets-container">
                            <h2 class="home-container-title">Recently Viewed Study Sets</h2>
                            <div class="study-sets-tiles-container">
                                <?php
                                if (isset($_COOKIE['viewed_study_sets'])) {
                                    // Get array of viewed study set IDs
                                    $viewedStudySets = explode(',', $_COOKIE['viewed_study_sets']);
                                    foreach ($viewedStudySets as $StudySetId) {
                                        // Fetch the study set data from the database
                                        $studySet = get_study_set($StudySetId);
                                        $avgRatingQuery = "SELECT AVG(Rating) as AvgRating FROM STUDY_SET_RATINGS WHERE StudySetID = :StudySetID";
                                        $avgRatingResult = run_database($avgRatingQuery, ['StudySetID' => $StudySetId]);
                                        if ($avgRatingResult) {
                                            $averageRating = is_array($avgRatingResult[0]) ? round($avgRatingResult[0]['AvgRating'], 2) : round($avgRatingResult[0]->AvgRating, 2);
                                        } else {
                                            $averageRating = 'Not rated';
                                        }
                                        if ($studySet) {
                                ?> <div class="study-set-tile StudySetLinkTile" data-id="<?= $studySet->StudySetID; ?>">
                                                <div class="study-set-header">
                                                    <a href="account/profile.php"><img src="<?= $studySet->Avatar; ?>" alt="Place Holder" class="study-set-profile-picture" /></a>
                                                    <div class="study-set-info">
                                                        <a href="account/profile.php" class="study-set-account"> <?= $studySet->Username; ?></a>
                                                        <p class="study-set-date"> <?= date('F j, Y', $post->PostCreated); ?> </p>
                                                    </div>
                                                </div>
                                                <h3 class="study-set-title"> <?= $studySet->Title; ?> </h3>
                                                <div class="study-set-description"> <?= $studySet->Description; ?> </div>
                                                <div class="bottom-of-tile">
                                                    <div class="comment">
                                                        <i class="fa-regular fa-comment"></i>
                                                        <div class="comments-count"><?= $studySet->Comments; ?></div>
                                                    </div>
                                                    <div class="study-set-rating">
                                                        <i class="fa-regular fa-star"></i>
                                                        <div class="study-set-rating-count" style="margin-top: 1px;"><?= $averageRating; ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                <?php
                                        }
                                    }
                                } else {
                                    echo "No study sets viewed yet";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                // Event listener for the post tiles so it goes to the post page when clicking on the tile
                let postTiles = document.querySelectorAll('.PostLinkTile');
                postTiles.forEach(tile => {
                    tile.addEventListener('click', function() {
                        window.location.href = "/forum/posts/" + this.dataset.id;
                    });
                });

                // Event listener for the Study Set tiles so it goes to the study set page when clicking on the tile
                let studySetTiles = document.querySelectorAll('.StudySetLinkTile');
                studySetTiles.forEach(tile => {
                    tile.addEventListener('click', function() {
                        window.location.href = "/study-sets/" + this.dataset.id;
                    });
                });

                // Truncate the post content if it's too long
                // Select all post content
                var contents = document.querySelectorAll('.post-content');
                // Loop through each post content
                contents.forEach(function(content) {
                    // Check if the content is longer than 50 characters
                    if (content.textContent.length > 50) {
                        // If it is, truncate it to 50 characters and add an ellipsis
                        content.textContent = content.textContent.substring(0, 50) + '...';
                    }
                });

                // Same for the 3 below
                var postTitles = document.querySelectorAll('.post-title');
                postTitles.forEach(function(title) {
                    if (title.textContent.length > 80) {
                        title.textContent = title.textContent.substring(0, 50) + '...';
                    }
                });

                var descriptions = document.querySelectorAll('.study-set-description');
                descriptions.forEach(function(description) {
                    if (description.textContent.length > 50) {
                        description.textContent = description.textContent.substring(0, 50) + '...';
                    }
                });

                var titles = document.querySelectorAll('.study-set-title');
                titles.forEach(function(title) {
                    if (title.textContent.length > 80) {
                        title.textContent = title.textContent.substring(0, 50) + '...';
                    }
                });
            </script>
        <?php endif; ?>
    </main>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>

</html>