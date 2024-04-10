<?php if ( $application ) : ?>
    <div class="candidate-details">
        <div class="row">
            <div class="col-md-6">
                <h2><?php echo esc_html( $application->prename . ' ' . $application->surname ); ?></h2>
                <p><?php echo esc_html( $application->email ); ?></p>
            </div>
            <div class="col-md-6 d-flex justify-content-center align-items-center">
                <?php if ($application->state == 'new') : ?>
                    <button id="review-btn-start" class="btn btn-success" value="in_progress">Prüfung starten</button>
                <?php elseif ($application->state == 'in_progress') : ?>
                    <button class="btn btn-success review-btn" data-comment="true" value="waiting">Prüfung pausieren</button>
                    <button class="btn btn-success review-btn" value="finished">Prüfung beenden</button>
                <?php elseif ($application->state == 'waiting') : ?>
                    <button class="btn btn-success review-btn" value="in_progress">Prüfung fortsetzen</button>
                <?php elseif ($application->state == 'finished') : ?>
                    <button class="btn btn-success review-btn" data-comment="true" value="waiting">Prüfung erneut starten</button>
                <?php endif; ?>
                <!-- Weitere Aktionen je nach Status hier einfügen -->
            </div>
        </div>
        <hr>
        <?php include 'job-info-template.php'; ?>
        <hr>
        <?php if ($application->review_id) : ?>
            <?php include 'criteria-template.php'; ?>
            <hr>
            <?php include 'completeness-template.php'; ?>
            <hr>
            <?php include 'screening-template.php'; ?>
            <hr>
            <?php include 'commitment-template.php'; ?>
            <hr>
        <?php endif; ?>
        <p><strong>Hochgeladene Dateien:</strong></p>
        <?php include 'file-template.php'; ?>
        <button id="add-files-button" class="btn btn-primary">Dateien hinzufügen</button>
        <hr>
        <p><strong>Backlog:</strong></p>
        <?php include 'backlog-template.php'; ?>
    </div>
<?php else : ?>
    <div class="alert alert-warning" role="alert">Es wurden keine Bewerbungsdetails gefunden.</div>
<?php endif; ?>
