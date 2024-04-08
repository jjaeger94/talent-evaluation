<?php
    /**
     * Hier alle Shortcodes für Dienstleister eintragen!
     */
    function register_shortcodes_dienstleister() {
        add_shortcode( 'show_tasks', 'show_tasks_table' );
        add_shortcode( 'application_details', 'render_application_details_shortcode' );
    }

    // Kurzer Shortcode zum Anzeigen der Kandidatentabelle
    function show_tasks_table() {
        // Überprüfen, ob der Benutzer eingeloggt ist
        if ( current_user_can( 'dienstleister' ) ) {
            // Erfassen Sie die in den Optionen gespeicherten Daten
            $temp_db = open_database_connection();

            // SQL-Abfrage, um Kandidaten des aktuellen Benutzers abzurufen
            $query = $temp_db->prepare( "
                SELECT ID, email, state, added, edited
                FROM {$temp_db->prefix}applications
                ORDER BY added DESC
            " );
    
            // Stellen abrufen
            $tasks = $temp_db->get_results( $query );
    
            // Überprüfen, ob Jobs vorhanden sind
            if ( $tasks ) {
                // Tabelle aus Vorlagendatei einfügen
                ob_start();
                include plugin_dir_path( __FILE__ ) . 'templates/tasks-table-template.php';
                $output = ob_get_clean();
            } else {
                // Keine Jobs gefunden, Nachricht ausgeben
                $output = '<div class="alert alert-info" role="alert">Es wurden keine Aufgaben gefunden.</div>';
            }
    
            return $output;
        } else {
            return 'Bitte loggen Sie sich ein, um Ihre Aufgaben zu sehen.';
        }
    }

    // Funktion zum Rendern des Bewerbungsdetail-Shortcodes
    function render_application_details_shortcode() {
        // Überprüfen, ob der Benutzer eingeloggt ist und Berechtigung hat
        if ( current_user_can( 'dienstleister' ) ) {
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
        if ( current_user_can( 'dienstleister' ) ) {
            // Datenbankverbindung öffnen
            $temp_db = open_database_connection();
    
            // SQL-Abfrage, um die Bewerbungsdetails abzurufen
            $query = $temp_db->prepare( "
                SELECT *
                FROM {$temp_db->prefix}applications
                WHERE ID = %d
            ", $application_id );
    
            // Bewerbungsdetails abrufen
            $application = $temp_db->get_results( $query );
    
            // Überprüfen, ob Bewerbungsdetails vorhanden sind
            return ! empty( $application ) ? $application[0] : null;
        } else {
            return null;
        }
    }
    
    function get_job_by_id( $job_id ) {
        if ( current_user_can( 'dienstleister' ) ) {
            // Datenbankverbindung öffnen
            $temp_db = open_database_connection();
    
            // SQL-Abfrage, um die Jobdetails abzurufen
            $query = $temp_db->prepare( "
                SELECT *
                FROM {$temp_db->prefix}jobs
                WHERE ID = %d
            ", $job_id );
    
            // Jobdetails abrufen
            $job = $temp_db->get_results( $query );
    
            // Überprüfen, ob Jobdetails vorhanden sind
            return ! empty( $job ) ? $job[0] : null;
        } else {
            return null;
        }
    }
    