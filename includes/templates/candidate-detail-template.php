<?php if ( $application ) : ?>
    <div class="candidate-details">
        <h2><?php echo esc_html( $application->prename . ' ' . $application->surname ); ?></h2>
        <p><strong>E-Mail:</strong> <?php echo esc_html( $application->email ); ?></p>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <p><strong>Beworben auf:</strong> <?php echo esc_html( $job->job_title ); ?></p>
            </div>
            <div class="col-md-6">
                <p><strong>Einordnung:</strong>
                    <select class="form-control">
                        <option value="1">Option 1</option>
                        <option value="2">Option 2</option>
                        <option value="3">Option 3</option>
                    </select>
                </p>
            </div>
        </div>
        <hr>
        <p><strong>Status der Prüfung:</strong></p>
        <div class="progress">
            <div class="progress-bar bg-success" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">Kriterien werden überprüft</div>
            <div class="progress-bar bg-info" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">Vollständigkeit wird sichergestellt</div>
            <div class="progress-bar bg-warning" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">Referenzen werden überprüft</div>
            <div class="progress-bar bg-secondary" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">Prüfung abgeschlossen</div>
        </div>
        <hr>
        <p><strong>Ergebnis der Prüfung:</strong> <?php echo esc_html( $prüfungsergebnis ); ?></p>
    </div>
<?php else : ?>
    <div class="alert alert-warning" role="alert">Es wurden keine Bewerbungsdetails gefunden.</div>
<?php endif; ?>
