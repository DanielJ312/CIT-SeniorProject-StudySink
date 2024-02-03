<!-- Home - No current use other than for testing. -->
<?php
require($_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php");
update_session();
$pageTitle = "Home";?>
<!DOCTYPE html>
<html lang="en">
<head> 
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/head.php"); ?> 
</head>
<body>
    <header id="home-logout-header"> 
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
        <link rel="stylesheet" type="text/css" href="/styles/home/<?= !check_login() ? "logged-out" : "logged-in"; ?>.css">
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
                <!-- Left Container -->
                    <div class="left-container">
                        <div class="university-post-container">
                        <h2 class="home-container-title">
                            <u>Recent University Posts</u>
                        </h2>
                        <div class="university-posts">
                            <!--University Posts container goes here -->
                        </div>
                    </div>
                </div>
                <!-- Right Container -->
                    <div class="right-container">
                        <!-- Inner Container 1 within Right Container -->
                        <div class="recent-posts-container">
                        <h2 class="home-container-title">
                            <u>Recently Viewed Posts</u>
                        </h2>
                        <div class="post-tiles-container">
                            <?php
                                if (isset($_COOKIE['viewed_posts'])) {
                                    // Get array of viewed post IDs
                                    $viewedPosts = explode(',', $_COOKIE['viewed_posts']);
                                    // Display post tiles
                                    foreach ($viewedPosts as $postId) {
                                        // Fetch the post data from the database
                                        $post = get_post($postId);
                                        if ($post) { ?> <a href="/forum/posts/
                                            <?= $post->PostID; ?>" class="post-tile">
                            <div class="post-header">
                                <img src="<?= $post->Avatar; ?>" alt="Place Holder" class="post-profile-picture" />
                                <div class="post-info">
                                <p class="post-account"> <?= $post->Username; ?> </p>
                                <p class="post-date"> <?= display_time($post->PostCreated, "F j, Y"); ?> </p>
                                </div>
                            </div>
                            <h3 class="post-title"> <?= $post->Title; ?> </h3>
                            <div class="post-content"> <?= $post->Content; ?> </div>
                            <div class="vote">
                                <div class="post-iconsp">
                                <i class="fa-regular fa-heart"></i>
                                </div>
                                <div class="votes">20</div>
                            </div>
                            </a> <?php
                                }
                            }
                        } else {
                            echo "No posts viewed yet";
                        }?> 
                        </div>
                        </div>
                        <!-- Inner Container 2 within Right Container -->
                        <div class="recent-sets-container">
                        <h2 class="home-container-title">
                            <u>Recently Viewed Study Sets</u>
                        </h2>
                        <div class="study-sets-tiles-container"> 
                            <?php
                                if (isset($_COOKIE['viewed_study_sets'])) {
                                // Get array of viewed study set IDs
                                $viewedStudySets = explode(',', $_COOKIE['viewed_study_sets']);
                                // Display study set IDs for now
                                foreach ($viewedStudySets as $StudySetId) {
                                // Fetch the study set data from the database
                                $studySet = get_study_set($StudySetId);
                                if ($studySet) {
                            ?>      <a href="/study-sets/<?= $studySet->StudySetID; ?>" class="study-set-tile">
                                    <div class="study-set-header">
                                        <img src="<?= $studySet->Avatar; ?>" alt="Place Holder" class="study-set-profile-picture" />
                                        <div class="study-set-info">
                                            <p class="study-set-account"> <?= $studySet->Username; ?> </p>
                                            <p class="study-set-date"> <?= display_time($studySet->Created, "F j, Y"); ?> </p>
                                        </div>
                                    </div>
                                    <h3 class="study-set-title"> <?= $studySet->Title; ?> </h3>
                                    <div class="study-set-description"> <?= $studySet->Description; ?> </div>
                                    </a> 
                            <?php
                            }
                            }
                            } else {
                                    echo "No study sets viewed yet";
                            }
                            ?> </div>
                        </div>
                    </div>
                </div>
            </div> 
        <?php endif; ?> 
    </main>
    <footer> 
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
    </footer>
</body>
</html>