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