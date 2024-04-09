<?php if ( $application ) : ?>
    <div class="candidate-details">
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
                        <option value="0">Automatische Einordnung</option>
                        <option value="1">Manuell positiv</option>
                        <option value="2">Manuell negativ</option>
                    </select>
                </p>
            </div>
        </div>
        <hr>
        <p><strong>Status der Prüfung:</strong></p>
        <div class="progress">
            <div class="progress-bar overflow-visible" role="progressbar" style="width: <?php echo esc_attr( ($active_status_index + 1) * 25 ); ?>%;" aria-valuenow="<?php echo esc_attr( $active_status_index + 1 ); ?>" aria-valuemin="1" aria-valuemax="4"><?php echo esc_html( $statuses[$active_status_index] ); ?></div>
        </div>
        <br>
        <p><strong>Ergebnis der Prüfung:</strong><br><?php echo esc_html( $prüfungsergebnis ); ?></p>
        <hr>
        <p><strong>Ergebnis Commitment Test:</strong><br><?php echo esc_html( $prüfungsergebnis ); ?></p>
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
        <button id="add-files-button" class="btn btn-primary">Dateien hinzufügen</button>
    </div>
<?php else : ?>
    <div class="alert alert-warning" role="alert">Es wurden keine Bewerbungsdetails gefunden.</div>
<?php endif; ?>
