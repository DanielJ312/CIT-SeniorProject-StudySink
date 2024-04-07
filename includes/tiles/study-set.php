<div class="cardContainer">
    <a href="/study-sets/<?= $set->StudySetID; ?>" class="">
        <div class="cardHeaderTopLeft">
            <img src="<?= htmlspecialchars($set->Avatar); ?>" alt="<?= htmlspecialchars($set->Username); ?>'s avatar" class="profile-picture" />
            <div class="cardHeaderUsernameDate">
                <p><?= $set->Username; ?></p>
                <p><?= date("F j, Y", $set->SetCreated); ?></p>
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
    </a>
</div>