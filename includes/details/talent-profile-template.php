<div class="chat-bar fixed-top" style="z-index: 1000;">
    <div class="container">
        <div class="row align-items-center">
            <!-- Menü -->
            <div class="col-2">
                <?php include TE_DIR.'menu/burger.php'; ?>
            </div>
            <!-- Name von Dieter -->
            <div class="col-8 text-center">
                <h4>Mein Profil</h4>
            </div>
            <!-- Icon mit Fragezeichen -->
            <div class="col-2 text-right">
            <div class="menu-button" id="help-profile-open">
                    <i class="fa-regular fa-circle-question"></i>
                </div>
            </div>
        </div>
        <?php include TE_DIR.'menu/entries.php'; ?>
    </div>
</div>
<?php if ($talent) : ?>
    <div class="container top-bar-margin">
        <?php include TE_DIR.'profile/personal-data.php'; ?>
        <div class="alert alert-info mt-3">Lade deinen Lebenslauf hier hoch oder füge deine Infos weiter unten manuell hinzu.</div>
        <?php $docs = !empty($resumes) ? array($resumes[0]) : []; ?>
        <?php include TE_DIR.'tables/documents-table-template.php'; ?>
        <?php include TE_DIR.'profile/resume.php'; ?>
        <div class="alert alert-info mt-3">Außerdem kannst du hier weitere Dokumente wie Arbeitszeugnisse hinzufügen.</div>
        <?php $docs = $documents; ?>
        <?php include TE_DIR.'tables/documents-table-template.php'; ?>
        <?php include TE_DIR.'profile/documents.php'; ?>
        <?php include TE_DIR.'profile/apprenticeship.php'; ?>
        <?php include TE_DIR.'profile/studies.php'; ?>
        <?php include TE_DIR.'profile/experience.php'; ?>
        <?php include TE_DIR.'profile/eq.php'; ?>
        
    </div>
<?php else : ?>
    <p>Talent nicht gefunden.</p>
<?php endif; ?>

<!-- Modal für Tipps -->
<div class="modal fade" id="profileModal" tabindex="-1" role="dialog" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="profileModalLabel">Dein Profil</h5>
                <button class="btn-close" id="help-profile-close" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Gib hier ein paar Infos zu dir an, um Bewerbungen von passenden Unternehmen zu erhalten.</p>
                <p>Deine Angaben werden genutzt um dir passende Stellenvorschläge zu machen.</p>
                <p>Anschließend kannst du diese unter Stellen Bewerten.</p>
            </div>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    $('#help-profile-open').click(function() {
        $('#profileModal').modal('show');
        history.pushState({modalOpen: true}, null, null);
    });
    $('#help-profile-close').click(function() {
        $('#profileModal').modal('hide');
    });
});
</script>
