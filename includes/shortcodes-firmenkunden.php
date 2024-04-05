<?php    
     /**
     * Hier alle Shortcodes für Firmenkunden eintragen!
     */
    function register_shortcodes_firmenkunden() {
        add_shortcode('firmenkunden_page', 'firmenkunden_page_shortcode');
        add_shortcode( 'add_job_form', 'add_job_form_shortcode' );
        add_shortcode( 'show_jobs', 'show_jobs_table' );
        add_shortcode( 'create_candidate_form', 'render_create_candidate_form' );
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
        if ( current_user_can( 'firmenkunde' ) ) {
            ob_start();
            include( plugin_dir_path( __FILE__ ) . 'templates/job-form.php' );
            return ob_get_clean();
        }else{
            return 'Sie haben keine Berechtigung, dieses Formular anzuzeigen.';
        }
    }

    function render_create_candidate_form() {
        if ( current_user_can( 'firmenkunde' ) ) {
            $jobs = get_active_jobs();
            ob_start();
            include plugin_dir_path( __FILE__ ) . 'templates/create_candidate_form.php';
            return ob_get_clean();
        }else{
            return 'Sie haben keine Berechtigung, dieses Formular anzuzeigen.';
        }
    }

    // Funktion zum Abrufen aller aktiven Stellen aus der Datenbank
    function get_active_jobs() {
        if(current_user_can( 'firmenkunde' )){
            $user_id = get_current_user_id();

            $temp_db = open_database_connection();

            // SQL-Abfrage, um alle aktiven Stellen abzurufen
            $query = $temp_db->prepare( "
                SELECT ID, job_title
                FROM {$temp_db->prefix}jobs
                WHERE user_id = %d
                AND state = 'active'
                ORDER BY added DESC
            ", $user_id );

            // Stellen abrufen
            $jobs = $temp_db->get_results( $query );

            return $jobs;
        }else{
            return NULL;
        }
            
}

    function show_jobs_table() {    
        // Überprüfen, ob der Benutzer eingeloggt ist
        if ( is_user_logged_in() ) {
            // ID des aktuellen Benutzers abrufen
            $user_id = get_current_user_id();
    
            // Erfassen Sie die in den Optionen gespeicherten Daten
            $temp_db = open_database_connection();
    
            // SQL-Abfrage, um Stellen des aktuellen Benutzers abzurufen
            $query = $temp_db->prepare( "
                SELECT ID, job_title, added, state
                FROM {$temp_db->prefix}jobs
                WHERE user_id = %d
                ORDER BY added DESC
            ", $user_id );
    
            // Stellen abrufen
            $jobs = $temp_db->get_results( $query );
    
            // Überprüfen, ob Jobs vorhanden sind
            if ( $jobs ) {
                // Tabelle aus Vorlagendatei einfügen
                ob_start();
                include plugin_dir_path( __FILE__ ) . 'templates/jobs_table_template.php';
                $output = ob_get_clean();
            } else {
                // Keine Jobs gefunden, Nachricht ausgeben
                $output = '<div class="alert alert-info" role="alert">Es wurden keine Stellen gefunden.</div>';
            }
    
            return $output;
        } else {
            return 'Bitte loggen Sie sich ein, um Ihre Stellen zu sehen.';
        }
    }
    
    

