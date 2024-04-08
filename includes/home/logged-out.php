<div class="home-logout-body">
    <section class="home-logout-section" id="section0">
        <div class="top-logo-buttons">
            <img src="https://studysink.s3.amazonaws.com/assets/StudySinkBanner.png" alt="Company Logo" class="company-logo">
            <!-- <p style="color: black; font-size: 2em;">A steady flow of information</p> -->
            <p style="color: black; font-size: 2em;">TESTING BRANCH</p>
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