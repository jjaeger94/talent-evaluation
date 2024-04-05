<?php    
     /**
     * Hier alle Shortcodes für Firmenkunden eintragen!
     */
    function register_shortcodes_firmenkunden() {
        add_shortcode('firmenkunden_page', 'firmenkunden_page_shortcode');
        add_shortcode( 'add_job_form', 'add_job_form_shortcode' );
        add_shortcode( 'show_jobs', 'show_jobs_table' );
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
            include( plugin_dir_path( __FILE__ ) . 'forms/job-form.php' );
            return ob_get_clean();
        }else{
            return 'Sie haben keine Berechtigung, dieses Formular anzuzeigen.';
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
                // Tabelle erstellen
                $output = '<table>';
                $output .= '<tr><th>Stellenbezeichnung</th><th>Erstelldatum</th><th>Status</th></tr>';
    
                // Schleife durch alle Stellen des Benutzers
                foreach ( $jobs as $job ) {
                    $job_title = $job->job_title;
                    $job_date = $job->added;
                    $job_status = $job->state == 'active' ? 'aktiv' : 'inaktiv';
    
                    // Zeile für jede Stelle hinzufügen
                    $output .= '<tr>';
                    $output .= '<td>' . $job_title . '</td>';
                    $output .= '<td>' . $job_date . '</td>';
                    $output .= '<td>' . $job_status . '</td>';
                    $output .= '</tr>';
                }
    
                $output .= '</table>';
            } else {
                // Keine Jobs gefunden, Nachricht ausgeben
                $output = 'Es wurden keine Stellen gefunden.';
            }
    
            return $output;
        } else {
            return 'Bitte loggen Sie sich ein, um Ihre Stellen zu sehen.';
        }
    }
    

