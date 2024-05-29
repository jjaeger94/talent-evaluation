<?php if ($talent) : ?>
    <div class="container">
        <?php if (current_user_can('dienstleister')) : ?>
        <?php include TE_DIR.'talents/meta.php'; ?>
        <button class="btn btn-primary mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#personalDataCollapse" aria-expanded="true" aria-controls="personalDataCollapse">
            Alle Infos anzeigen
        </button>
        <button class="btn btn-primary mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#jobsTableCollapse" aria-expanded="false" aria-controls="jobsTableCollapse">
            Jobs anzeigen
        </button>
        <button class="btn btn-primary mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#chatCollapse" aria-expanded="false" aria-controls="chatCollapse">
            Chatverlauf anzeigen
        </button>
        <div class="collapse" id="personalDataCollapse">
        <?php else: ?>
        <?php include TE_DIR.'talents/info.php'; ?>
        <?php endif; ?>
        <?php include TE_DIR.'talents/personal-data-start.php'; ?>
        <?php include TE_DIR.'talents/apprenticeship.php'; ?>
        <?php include TE_DIR.'talents/studies.php'; ?>
        <?php include TE_DIR.'talents/experience.php'; ?>
        <?php include TE_DIR.'talents/eq.php'; ?>
        <?php include TE_DIR.'talents/personal-data-end.php'; ?>
        <?php if (current_user_can('dienstleister')) : ?>
            </div>
            <div class="collapse" id="jobsTableCollapse">
                <?php include TE_DIR.'tables/talent-jobs-table-template.php'; ?>
            </div>
            <div class="collapse" id="chatCollapse">
                <?php include TE_DIR.'talents/chat.php'; ?>
            </div>
        <?php endif; ?>
    </div>
<?php else : ?>
    <p>Talent nicht gefunden.</p>
<?php endif; ?>
