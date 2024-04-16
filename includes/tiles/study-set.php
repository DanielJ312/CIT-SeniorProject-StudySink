<div class="cardContainer">
    <div id="outerAnchor" data-id="<?= $set->StudySetID; ?>" class="<?= $subjectID != 0 ? "" : "post"; ?>">
        <div class="cardHeaderTopLeft">
            <a href="/account/<?= $set->Username; ?>.php" title="<?= $set->Username; ?>">
                <img src="<?= $set->Avatar; ?>" alt="<?= $set->Username; ?>" class="post-profile-picture" />
            </a>
            <div class="post-info">
                <a href="/account/<?= $set->Username; ?>.php" class="post-account"><?= $set->Username; ?></a>
                <p class="post-date"><?= date("F j, Y", $set->SetCreated); ?></p>
            </div>
        </div>
        <div class="studySetDetailsBottom">
            <div class="studySetDetailsBottomLeft">
                <h3><?= $set->Title; ?></h3>
            </div>
            <div class="studySetDetailsBottomRight">
                <p><?= $set->UniversityName; ?></p>
                <p><?= $set->Course; ?></p>
            </div>
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
</div>

<script>
    document.getElementById('outerAnchor').addEventListener('click', function(event) {
        // Check if the click was on the inner anchor tag
        if (event.target.tagName.toLowerCase() === 'a') {
            // Let the inner anchor handle the click
            return;
        }

        // Handle the click for the outer anchor
        window.location.href = "/study-sets/<?= $set->StudySetID; ?>";
    });
</script>
