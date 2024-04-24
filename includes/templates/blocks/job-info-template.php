<div class="row">
    <div class="col-md-6">
        <p><strong>Stelle:</strong><br><?php echo esc_html( $job->job_title ); ?></p>
        <p><strong>Firma:</strong><br><?php echo esc_html( $company ); ?></p>
        <p><strong>Test:</strong><br><?php echo esc_html( $test->title ); ?></p>
    </div>
    <div class="col-md-6">
        <p><strong>Kriterien:</strong><br>
            <?php
            // Überprüfen, ob Kriterien vorhanden sind
            if (!empty($job->criteria1) || !empty($job->criteria2) || !empty($job->criteria3)) {
                // Wenn Kriterien vorhanden sind, zeigen Sie sie an
                echo '<ul>';
                if (!empty($job->criteria1)) {
                    echo '<li>' . esc_html($job->criteria1) . '</li>';
                }
                if (!empty($job->criteria2)) {
                    echo '<li>' . esc_html($job->criteria2) . '</li>';
                }
                if (!empty($job->criteria3)) {
                    echo '<li>' . esc_html($job->criteria3) . '</li>';
                }
                echo '</ul>';
            } else {
                // Wenn keine Kriterien angegeben sind, geben Sie einen entsprechenden Hinweis aus
                echo 'Keine Kriterien angegeben';
            }
            ?>
        </p>
    </div>
</div>
<br>
<div class="row">
    <div class="form-group col-md-6">
        <label><strong>Vollständigkeit:</strong></label>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="completeness1" name="completeness1" value="0" <?php echo $job->completeness & 1 ? 'checked' : ''; ?> disabled>
            <label class="form-check-label" for="completeness1">Zeugnisse auf Vollständigkeit prüfen</label>
        </div>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="completeness2" name="completeness2" value="0" <?php echo $job->completeness & 2 ? 'checked' : ''; ?> disabled>
            <label class="form-check-label" for="completeness2">Arbeitszeugnisse auf Vollständigkeit prüfen</label>
        </div>
    </div>
    <div class="form-group col-md-6">
        <label><strong>Screening:</strong></label>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="screening1" name="screening1" value="0" <?php echo $job->screening & 1 ? 'checked' : ''; ?> disabled>
            <label class="form-check-label" for="screening1">LinkedIn checken</label>
        </div>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="screening2" name="screening2" value="0" <?php echo $job->screening & 2 ? 'checked' : ''; ?> disabled>
            <label class="form-check-label" for="screening2">Höchstes Bildungszeugnis prüfen</label>
        </div>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="screening3" name="screening3" value="0" <?php echo $job->screening & 4 ? 'checked' : ''; ?> disabled>
            <label class="form-check-label" for="screening3">Arbeitszeugnis prüfen</label>
        </div>
    </div>
</div>
