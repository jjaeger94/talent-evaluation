<?php if ($talent) : ?>
    <div class="container">
        <?php include TE_DIR.'controls/talent.php'; ?>
        <button class="btn btn-primary mb-3" data-bs-toggle="collapse" data-bs-target="#personalDataCollapse" aria-expanded="true" aria-controls="personalDataCollapse">
            Alle Infos anzeigen
        </button>
        <button class="btn btn-primary mb-3" data-bs-toggle="collapse" data-bs-target="#jobsTableCollapse" aria-expanded="false" aria-controls="jobsTableCollapse">
            Jobs anzeigen
        </button>
        <button class="btn btn-primary mb-3" data-bs-toggle="collapse" data-bs-target="#chatCollapse" aria-expanded="false" aria-controls="chatCollapse">
            Chatverlauf anzeigen
        </button>
        <button class="btn btn-primary mb-3" data-bs-toggle="collapse" data-bs-target="#eventsCollapse" aria-expanded="false" aria-controls="eventsCollapse">
            Eventlogs anzeigen
        </button>
        <div class="collapse show" id="personalDataCollapse">
        <?php include TE_DIR.'profile/personal-data.php'; ?>
        <?php include TE_DIR.'tables/resume-table-template.php'; ?>
        <?php include TE_DIR.'profile/resume.php'; ?>
        <?php include TE_DIR.'profile/apprenticeship.php'; ?>
        <?php include TE_DIR.'profile/studies.php'; ?>
        <?php include TE_DIR.'profile/experience.php'; ?>
        <?php include TE_DIR.'profile/eq.php'; ?>
        </div>
        <div class="collapse" id="jobsTableCollapse">
            <?php include TE_DIR.'tables/talent-jobs-table-template.php'; ?>
        </div>
        <div class="collapse" id="chatCollapse">
            <?php include TE_DIR.'controls/chat.php'; ?>
        </div>
        <div class="collapse" id="eventsCollapse">
            <?php include TE_DIR.'tables/talent-events-table.php'; ?>
        </div>
    </div>
<?php else : ?>
    <p>Talent nicht gefunden.</p>
<?php endif; ?>
