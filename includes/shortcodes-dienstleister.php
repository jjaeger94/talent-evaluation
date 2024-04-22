<?php
    /**
     * Hier alle Shortcodes für Dienstleister eintragen!
     */
    function register_shortcodes_dienstleister() {
        add_shortcode( 'show_tasks', 'show_tasks_table' );
        add_shortcode( 'task_details', 'render_task_details_shortcode' );
    }

    // Kurzer Shortcode zum Anzeigen der Kandidatentabelle
    function show_tasks_table() {
        // Überprüfen, ob der Benutzer eingeloggt ist
        if ( current_user_can( 'dienstleister' ) ) {

            $selected_tasks = isset( $_GET['filter_tasks'] ) ? sanitize_text_field( $_GET['filter_tasks'] ) : null;
            // Erfassen Sie die in den Optionen gespeicherten Daten
            $temp_db = open_database_connection();

            $filter = '';

            if ( $selected_tasks ) {
                $filter = "WHERE state = '{$selected_tasks}'";
            }

            // SQL-Abfrage, um Kandidaten des aktuellen Benutzers abzurufen
            $query = $temp_db->prepare( "
                SELECT *
                FROM {$temp_db->prefix}te_applications
                {$filter}
                ORDER BY added DESC
            " );
    
            // Stellen abrufen
            $applications = $temp_db->get_results( $query );

            foreach ( $applications as $application ) {
                if ($application->review_id) {
                    $application->review = get_review_by_application($application);
                }
            }
    
            // Tabelle aus Vorlagendatei einfügen
            ob_start();
            include plugin_dir_path( __FILE__ ) . 'templates/tasks-table-template.php';
            $output = ob_get_clean();

    
            return $output;
        } else {
            return 'Bitte loggen Sie sich ein, um Ihre Aufgaben zu sehen.';
        }
    }

    // Funktion zum Rendern des Bewerbungsdetail-Shortcodes
    function render_task_details_shortcode() {
        // Überprüfen, ob der Benutzer eingeloggt ist und Berechtigung hat
        if ( current_user_can( 'dienstleister' ) ) {
            // Überprüfen, ob die ID-Parameter übergeben wurde
            if ( isset( $_GET['id'] ) ) {
                // ID-Parameter aus der URL abrufen
                $application_id = intval( $_GET['id'] );

                $application = get_application_by_id($application_id);

                $job = get_job_by_id($application->job_id);
                
                if($application->review_id){
                    $review = get_review_by_application($application);
                }

                $wp_user = get_user_by( 'id', $job->user_id );

                $swpm_user = SwpmMemberUtils::get_user_by_email($wp_user->user_email);
                $company = SwpmMemberUtils::get_member_field_by_id($swpm_user->member_id, 'company_name');
                
                // Tabelle aus Vorlagendatei einfügen
                ob_start();
                include plugin_dir_path( __FILE__ ) . 'templates/task-detail-template.php';
                return ob_get_clean();

            } else {
                // Keine ID-Parameter übergeben, Meldung ausgeben
                return '<div class="alert alert-warning" role="alert">Es wurde keine Bewerbungs-ID angegeben.</div>';
            }
        } else {
            // Benutzer hat keine Berechtigung, Meldung ausgeben
            return 'Sie haben keine Berechtigung, diese Seite anzuzeigen.';
        }
    }   