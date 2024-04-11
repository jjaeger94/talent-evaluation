<?php if ( $application ) : ?>
    <div class="application-details">
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
        <hr>
        <div class="row">
            <div class="col-md-6">
                <p><strong>Beworben auf:</strong><br><a href="<?php echo esc_url(home_url('/job-details?id=' . $job->ID)); ?>"><?php echo esc_html( $job->job_title ); ?></a></p>
            </div>
            <div class="col-md-6">
                <p><strong>Einordnung:</strong>
                    <select class="form-select" id="classification" name="classification">
                        <option value="0" <?php echo ($application->classification == 0) ? 'selected' : ''; ?>>Automatische Einordnung</option>
                        <option data-comment="true" value="1" <?php echo ($application->classification == 1) ? 'selected' : ''; ?>>Manuell positiv</option>
                        <option data-comment="true" value="2" <?php echo ($application->classification == 2) ? 'selected' : ''; ?>>Manuell negativ</option>
                    </select>
                </p>
            </div>
        </div>
        <hr>
        <?php if ($application->state == 'new') : ?>
            Prüfung wurde noch nicht gestartet
        <?php else : ?>
        <div><strong>Ergebnis der Prüfung:</strong>
        <div class="row">
            <div class="col-md-4 d-flex align-items-center">
                <div class="p-2">Kriterien:</div>
                <?php include 'columns/criteria.php'; ?>
            </div>
            <div class="col-md-4 d-flex align-items-center">
                <div class="p-2">Vollständigkeit:</div>
                <?php include 'columns/completeness.php'; ?>
            </div>
            <div class="col-md-4 d-flex align-items-center">
                <div class="p-2">Screening:</div>
                <?php include 'columns/screening.php'; ?>
            </div>
        </div>
        </div>
        <hr>
        <p><strong>Ergebnis Commitment Test:</strong><br>
            <?php include 'columns/commitment-with-text.php'; ?>
        </p>
        <?php endif; ?>
        <hr>
        <p><strong>Hochgeladene Dateien:</strong></p>
        <?php
        $file_path = $application->filepath;
        if (!empty($file_path)) {
            $files = glob($file_path . '*.pdf'); // Nur PDF-Dateien anzeigen
            if ($files !== false) {
                echo '<ul>';
                foreach ($files as $file) {
                    echo '<li><a href="' . esc_url( add_query_arg( array(
                        'file' => basename($file),
                        'application_id' => $application->ID
                    ), home_url('/pdf-viewer-page') ) ) . '" target="_blank">' . basename($file) . '</a></li>';
                }                              
                echo '</ul>';
            }                        
        } else {
            echo '<p>Keine Dateien hochgeladen.</p>';
        }
        ?>
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
