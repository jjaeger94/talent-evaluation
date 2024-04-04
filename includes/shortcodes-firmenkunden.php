<?php    
     /**
     * Hier alle Shortcodes für Firmenkunden eintragen!
     */
    function register_shortcodes_firmenkunden() {
        add_shortcode('firmenkunden_page', 'firmenkunden_page_shortcode');
        add_shortcode( 'add_job_form', 'add_job_form_shortcode' );
    }
    

    /**
     * Shortcode-Callback für die Firmenkunden-Seite
     *
     * @param array $atts Array von Attributen, die im Shortcode verwendet werden können.
     * @param string $content Der Inhalt innerhalb des Shortcodes, wenn der Shortcode als Paar verwendet wird.
     * @return string Der HTML-Inhalt der Dienstleister-Seite.
     */
    function firmenkunden_page_shortcode($atts, $content = null) {
        // Hier den Inhalt der Dienstleister-Seite einfügen
        return "Hier können Firmenkunden Bewerber hinzufügen und Dokumente hochladen.";
    }

    /**
     * 
     */
    function add_job_form_shortcode() {
        ob_start(); ?>
        
        <!-- Bootstrap-Formular für das Hinzufügen einer Stelle -->
        <form id="add-job-form" class="bootstrap-form" method="post">
            <div class="form-group">
                <label for="job-title">Stellenbezeichnung:</label>
                <input type="text" class="form-control" id="job-title" name="job_title" required>
            </div>
            <button type="submit" class="btn btn-primary">Stelle hinzufügen</button>
        </form>
        
        <?php
        return ob_get_clean();
    }