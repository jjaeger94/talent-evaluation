<?php if ( $application ) : ?>
    <div class="application-details">
        <h2><?php echo esc_html( $application->prename . ' ' . $application->surname ); ?></h2>
        <p><?php echo esc_html( $application->email ); ?></p>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <p><strong>Beworben auf:</strong><br><?php echo esc_html( $job->job_title ); ?></p>
            </div>
            <div class="col-md-6">
                <p><strong>Einordnung:</strong>
                    <select class="form-control" id="classification" name="classification">
                        <option value="0" <?php echo ($application->classification == 0) ? 'selected' : ''; ?>>Automatische Einordnung</option>
                        <option data-comment="true" value="1" <?php echo ($application->classification == 1) ? 'selected' : ''; ?>>Manuell positiv</option>
                        <option data-comment="true" value="2" <?php echo ($application->classification == 2) ? 'selected' : ''; ?>>Manuell negativ</option>
                    </select>
                </p>
            </div>
        </div>
        <hr>
        <br>
        <p><strong>Ergebnis der Prüfung:</strong><br>
        <div class="row">
            <div class="col-md-4">
                Kriterien:
                <?php include 'columns/criteria.php'; ?>
            </div>
            <div class="col-md-4">
                Vollständigkeit:
                <?php include 'columns/completeness.php'; ?>
            </div>
            <div class="col-md-4">
                Screening:
                <?php include 'columns/screening.php'; ?>
            </div>
        </div>
        </p>
        <hr>
        <p><strong>Ergebnis Commitment Test:</strong><br>
            <?php include 'columns/commitment.php'; ?>
        </p>
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
        <?php include 'blocks/backlog-template.php'; ?>
    </div>
<?php else : ?>
    <div class="alert alert-warning" role="alert">Es wurden keine Bewerbungsdetails gefunden.</div>
<?php endif; ?>
