<?php if ($talent) : ?>
    <div class="container">
        <?php include TE_DIR.'profile/info.php'; ?>
        <?php include TE_DIR.'profile/personal-data-start.php'; ?>
        <?php include TE_DIR.'profile/apprenticeship.php'; ?>
        <?php include TE_DIR.'profile/studies.php'; ?>
        <?php include TE_DIR.'profile/experience.php'; ?>
        <?php include TE_DIR.'profile/eq.php'; ?>
        <?php include TE_DIR.'profile/personal-data-end.php'; ?>
    </div>
<?php else : ?>
    <p>Talent nicht gefunden.</p>
<?php endif; ?>
