<?php if ( $application ) : ?>
    <div class="application-details">
        <div class="row">
            <div class="col-md-6">
                <h2><?php
                    $salutation = '';
                    if ($application->salutation == 1) {
                        $salutation = 'Herr ';
                    } elseif ($application->salutation == 2) {
                        $salutation = 'Frau ';
                    }
                    echo esc_html($salutation . $application->prename . ' ' . $application->surname);
                ?></h2>
                <p><?php echo esc_html($application->email); ?></p>
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
        <?php include 'blocks/job-info-template.php'; ?>
        <hr>
        <?php if ($application->review_id) : ?>
            <?php include 'blocks/criteria-template.php'; ?>
            <hr>
            <?php include 'blocks/completeness-template.php'; ?>
            <hr>
            <?php include 'blocks/screening-template.php'; ?>
            <hr>
            <?php include 'blocks/commitment-template.php'; ?>
            <hr>
        <?php endif; ?>
        <p><strong>Hochgeladene Dateien:</strong></p>
        <?php include 'blocks/file-template.php'; ?>
        <button id="add-files-button" class="btn btn-primary">Dateien hinzufügen</button>
        <hr>
        <p><strong>Backlog:</strong></p>
        <!-- Container für den Backlog-Inhalt -->
        <div id="backlog-container"></div>
        <!-- Button, der den Inhalt laden soll -->
        <button id="load-backlog-button" class="btn btn-primary">Backlog laden</button>
    </div>
<?php else : ?>
    <div class="alert alert-warning" role="alert">Es wurden keine Bewerbungsdetails gefunden.</div>
<?php endif; ?>
