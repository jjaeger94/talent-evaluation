<?php    
     /**
     * Hier alle Shortcodes für Firmenkunden eintragen!
     */
    function register_shortcodes_firmenkunden() {
        add_shortcode( 'add_job_form', 'add_job_form_shortcode' );
        add_shortcode( 'show_jobs', 'show_jobs_table' );
        add_shortcode( 'create_candidate_form', 'render_create_candidate_form' );
        add_shortcode( 'show_candidates', 'show_candidates_table' );
        add_shortcode( 'candidate_details', 'render_candidate_details_shortcode' );
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
            include plugin_dir_path( __FILE__ ) . 'templates/create-candidate-form.php';
            return ob_get_clean();
        }else{
            return 'Sie haben keine Berechtigung, dieses Formular anzuzeigen.';
        }
    }

    // Kurzer Shortcode zum Anzeigen der Kandidatentabelle
    function show_candidates_table() {
        // Überprüfen, ob der Benutzer eingeloggt ist
        if ( current_user_can( 'firmenkunde' ) ) {
            // ID des aktuellen Benutzers abrufen
            $user_id = get_current_user_id();
            
            $jobs = get_active_jobs();

            $selected_job = isset( $_GET['job_id'] ) ? intval( $_GET['job_id'] ) : 0;

            // Erfassen Sie die in den Optionen gespeicherten Daten
            $temp_db = open_database_connection();

            $filter = "WHERE user_id = {$user_id}";
            if ( $selected_job ) {
                $filter .= " AND job_id = {$selected_job}";
            }
            // SQL-Abfrage, um Kandidaten des aktuellen Benutzers abzurufen
            $query = $temp_db->prepare( "
                SELECT ID, job_id, prename, surname, reference_id
                FROM {$temp_db->prefix}applications
                {$filter}
                ORDER BY added DESC
            " );
    
            // Stellen abrufen
            $candidates = $temp_db->get_results( $query );
    
            // Überprüfen, ob Jobs vorhanden sind
            if ( $candidates ) {

                foreach ( $candidates as $candidate ) {
                    $job_id = $candidate->job_id;

                    foreach ( $jobs as $job ) {
                        if ( $job->ID == $job_id ) {
                            $job_title = $job->job_title;
                            break;
                        }
                    }

                    $candidate->job_title = $job_title;
                }
                // Tabelle aus Vorlagendatei einfügen
                ob_start();
                include plugin_dir_path( __FILE__ ) . 'templates/candidates-table-template.php';
                $output = ob_get_clean();
            } else {
                // Keine Jobs gefunden, Nachricht ausgeben
                $output = '<div class="alert alert-info" role="alert">Es wurden keine Stellen gefunden.</div>';
            }
    
            return $output;
        } else {
            return 'Bitte loggen Sie sich ein, um Ihre Kandidaten zu sehen.';
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
                SELECT ID, job_title, added, state, location
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
                include plugin_dir_path( __FILE__ ) . 'templates/jobs-table-template.php';
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
    
        // Funktion zum Rendern des Bewerbungsdetail-Shortcodes
        function render_candidate_details_shortcode() {
            // Überprüfen, ob der Benutzer eingeloggt ist und Berechtigung hat
            if ( current_user_can( 'firmenkunde' ) ) {
                // Überprüfen, ob die ID-Parameter übergeben wurde
                if ( isset( $_GET['id'] ) ) {
                    // ID-Parameter aus der URL abrufen
                    $application_id = intval( $_GET['id'] );
    
                    $application = get_application_by_id($application_id);
                    if ( $application ) {
                        // Tabelle aus Vorlagendatei einfügen
                        ob_start();
                        include plugin_dir_path( __FILE__ ) . 'templates/tasks-detail-template.php';
                        $output = ob_get_clean();
                    } else {
                        // Keine Bewerbungsdetails gefunden, Nachricht ausgeben
                        $output = '<div class="alert alert-info" role="alert">Es wurden keine Bewerbungsdetails gefunden.</div>';
                    }
    
                    return $output;
                } else {
                    // Keine ID-Parameter übergeben, Meldung ausgeben
                    return '<div class="alert alert-warning" role="alert">Es wurde keine Bewerbungs-ID angegeben.</div>';
                }
            } else {
                // Benutzer hat keine Berechtigung, Meldung ausgeben
                return 'Sie haben keine Berechtigung, diese Seite anzuzeigen.';
            }
        }
    
        function get_application_by_id( $application_id ) {
            if ( current_user_can( 'firmenkunde' ) ) {
                $user_id = get_current_user_id();
                // Datenbankverbindung öffnen
                $temp_db = open_database_connection();
        
                // SQL-Abfrage, um die Bewerbungsdetails abzurufen
                $query = $temp_db->prepare( "
                    SELECT *
                    FROM {$temp_db->prefix}applications
                    WHERE ID = {$application_id}
                    AND user_id = {$user_id}
                ");
        
                // Bewerbungsdetails abrufen
                $application = $temp_db->get_results( $query );
        
                // Überprüfen, ob Bewerbungsdetails vorhanden sind
                return ! empty( $application ) ? $application[0] : null;
            } else {
                return null;
            }
        }
        
        function get_job_by_id( $job_id ) {
            if ( current_user_can( 'firmenkunde' ) ) {
                $user_id = get_current_user_id();
                // Datenbankverbindung öffnen
                $temp_db = open_database_connection();
        
                // SQL-Abfrage, um die Jobdetails abzurufen
                $query = $temp_db->prepare( "
                    SELECT *
                    FROM {$temp_db->prefix}jobs
                    WHERE ID = {$job_id}
                    AND user_id = {$user_id}
                ");
        
                // Jobdetails abrufen
                $job = $temp_db->get_results( $query );
        
                // Überprüfen, ob Jobdetails vorhanden sind
                return ! empty( $job ) ? $job[0] : null;
            } else {
                return null;
            }
        }

