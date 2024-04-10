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
                <?php endif; ?>
                <?php if ($application->state == 'in_progress') : ?>
                    <button id="review-btn" class="btn btn-success" value="waiting">Prüfung pausieren</button>
                    <button id="review-btn" class="btn btn-success" value="finished">Prüfung beenden</button>
                <?php endif; ?>
                <?php if ($application->state == 'waiting') : ?>
                    <button id="review-btn" class="btn btn-success" value="in_progress">Prüfung fortsetzen</button>
                <?php endif; ?>
                <?php if ($application->state == 'finished') : ?>
                    <button id="review-btn" class="btn btn-success" value="waiting">Prüfung erneut starten</button>
                <?php endif; ?>
                <!-- Weitere Aktionen je nach Status hier einfügen -->
            </div>
        </div>
        <hr>
        <?php include 'job-info-template.php'; ?>
        <hr>
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
