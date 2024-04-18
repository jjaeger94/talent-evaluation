<?php if ($application): ?>
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
    <div class="col-md-5 d-flex align-items-center text-end">
        <?php include 'columns/status.php'; ?>
    </div>
</div>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <p><strong>Beworben auf:</strong><br><a href="<?php echo esc_url(home_url('/job-details?id=' . $job->ID)); ?>"><?php echo esc_html($job->job_title); ?></a></p>
            </div>
            <!-- <div class="col-md-6">
                <p><strong>Einordnung:</strong>
                    <select class="form-select" id="classification" name="classification">
                        <option value="0" <?php echo ($application->classification == 0) ? 'selected' : ''; ?>>Automatische Einordnung</option>
                        <option data-comment="true" value="1" <?php echo ($application->classification == 1) ? 'selected' : ''; ?>>Manuell positiv</option>
                        <option data-comment="true" value="2" <?php echo ($application->classification == 2) ? 'selected' : ''; ?>>Manuell negativ</option>
                    </select>
                </p>
            </div> -->
        </div>
        <hr>
        <?php if ($application->state == 'new'): ?>
            Prüfung wurde noch nicht gestartet
        <?php else: ?>
        <div><strong>Ergebnis der Prüfung:</strong>
        <div class="row">
            <div class="col-md-4 d-flex align-items-center">
                <div class="p-2">Kriterien:</div>
                <?php include 'columns/criteria.php';?>
            </div>
            <div class="col-md-4 d-flex align-items-center">
                <div class="p-2">Vollständigkeit:</div>
                <?php include 'columns/completeness.php';?>
            </div>
            <div class="col-md-4 d-flex align-items-center">
                <div class="p-2">Screening:</div>
                <?php include 'columns/screening.php';?>
            </div>
        </div>
        </div>
        <hr>
        <p><strong>Ergebnis Commitment Test:</strong><br>
            <?php include 'columns/commitment-with-text.php';?>
        </p>
        <?php endif;?>
        <hr>
        <p><strong>Hochgeladene Dateien:</strong></p>
        <?php include 'blocks/file-template.php'; ?>
        <hr>
        <p><strong>Backlog:</strong></p>
        <!-- Container für den Backlog-Inhalt -->
        <?php if (isset($is_mail)): ?>
            <?php include 'blocks/backlog-template.php'; ?>
        <?php else: ?>
        <div id="backlog-container"></div>
        <!-- Button, der den Inhalt laden soll -->
        <button id="load-backlog-button" class="btn btn-primary">Backlog laden</button>
        <?php endif;?>
    </div>
<?php else: ?>
    <div class="alert alert-warning" role="alert">Es wurden keine Bewerbungsdetails gefunden.</div>
<?php endif;?>
