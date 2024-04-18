<div class="row">
    <div class="col-md-8">
        <p><strong>Einverständnis:</strong></p>
        <?php if ($review->consent == -1) : ?>
            <p>Noch nicht gesendet</p>
        <?php elseif ($review->consent == 0) : ?>
            <p>In Prüfung</p>
        <?php else: ?>
            <?php if ($review->consent & 1) : ?>
        <p>LinkedIn erlaubt</p>
            <?php else: ?>
        <p>LinkedIn nicht erlaubt</p>
        <?php endif; ?>
        <?php if ($review->consent & 2) : ?>
        <p>alte Arbeitgeber erlaubt</p>
            <?php else: ?>
        <p>alte Arbeitgeber nicht erlaubt</p>
        <?php endif; ?>
        <?php if ($review->consent & 4) : ?>
        <p>aktueller Arbeitgeber erlaubt</p>
            <?php else: ?>
        <p>aktueller Arbeitgeber nicht erlaubt</p>
        <?php endif; ?>
        <?php endif; ?>
    </div>
    <div class="col-md-4 d-flex justify-content-center align-items-center">
        <?php if ($review->consent == -1) : ?>
            <button class="btn btn-success set-review-btn" data-type="consent" value="0">Email senden</button>
        <?php elseif($review->consent == 0):?>
            <button class="btn btn-success set-review-btn" data-type="consent" value="0">Email erneut senden</button>
        <?php else :?>
            <?php
                $uploadDir = get_consent_dir();
                $file_path = $review->filepath;
                if($file_path){
                    $file_path = $uploadDir . $file_path . '/';
                }
                if (!empty($file_path)) {
                    $files = glob($file_path . '*.pdf'); // Nur PDF-Dateien anzeigen
                    if ($files) {
                        echo '<ul>';
                        foreach ($files as $file) {
                            echo '<li><a href="' . esc_url( add_query_arg( array('file' => basename($file),'review_id' => $review->ID), home_url('/pdf-viewer-page') ) ) . '" target="_blank">' . basename($file) . '</a></li>';
                        }                              
                        echo '</ul>';
                    } else {
                        echo '<p>Keine Datei hochgeladen.</p>';
                    }                       
                } else {
                    echo '<p>Keine Datei hochgeladen.</p>';
                }
        ?>
        <?php endif; ?>
    </div>
</div>
