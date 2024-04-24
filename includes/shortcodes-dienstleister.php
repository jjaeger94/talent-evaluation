<?php
    /**
     * Hier alle Shortcodes für Dienstleister eintragen!
     */
    function register_shortcodes_dienstleister() {
        add_shortcode( 'show_tasks', 'show_tasks_table' );
        add_shortcode( 'task_details', 'render_task_details_shortcode' );
        add_shortcode('create_test', 'create_test_shortcode');
        add_shortcode('edit_test', 'edit_test_shortcode');
        add_shortcode('edit_question', 'edit_question_shortcode');
    }

    function create_test_shortcode() {
        if ( current_user_can( 'dienstleister' ) ) {
            ob_start(); // Puffer starten
            include_once('templates/forms/create-test-form.php'); // Pfad zur Datei mit dem Test-Formular
            return ob_get_clean(); // Puffer leeren und zurückgeben
        } else {
            return 'Keine Berechtigung';
        }
    }

    function edit_question_shortcode() {
        if ( current_user_can( 'dienstleister' ) ) {
            if ( isset( $_GET['tid'] ) ) {
                // ID-Parameter aus der URL abrufen
                $test_id = intval( $_GET['tid'] );
                if ( isset( $_GET['qid'] ) ) {
                    $question_id = intval( $_GET['qid'] );
                    $question = get_question_by_id($question_id);
                }
                ob_start(); // Puffer starten
                include_once('templates/forms/edit-question-form.php'); // Pfad zur Datei mit dem Test-Formular
                return ob_get_clean(); // Puffer leeren und zurückgeben
            } else {
                // Keine ID-Parameter übergeben, Meldung ausgeben
                return '<div class="alert alert-warning" role="alert">Es wurde keine Test-ID angegeben.</div>';
            }
        } else {
            return 'Keine Berechtigung';
        }
    }
    
    function edit_test_shortcode() {
        if ( current_user_can( 'dienstleister' ) ) {
            if ( isset( $_GET['id'] ) ) {
                // ID-Parameter aus der URL abrufen
                $test_id = intval( $_GET['id'] );
                $test = get_test_by_id($test_id);
                if($test){
                    $questions = get_questions_by_test_id($test_id);
                    ob_start(); // Puffer starten
                    include_once('templates/test-detail-template.php'); // Pfad zur Datei mit dem Test-Formular
                    return ob_get_clean(); // Puffer leeren und zurückgeben
                }else{
                    return '<div class="alert alert-warning" role="alert">Test nicht gefunden.</div>';
                }
        } else {
            // Keine ID-Parameter übergeben, Meldung ausgeben
            return '<div class="alert alert-warning" role="alert">Es wurde keine Test-ID angegeben.</div>';
        }
        } else {
            return 'Keine Berechtigung';
        }
    }

    // Kurzer Shortcode zum Anzeigen der Kandidatentabelle
    function show_tasks_table() {
        // Überprüfen, ob der Benutzer eingeloggt ist
        if ( current_user_can( 'dienstleister' ) ) {

            $selected_tasks = isset( $_GET['filter_tasks'] ) ? sanitize_text_field( $_GET['filter_tasks'] ) : null;
            // Erfassen Sie die in den Optionen gespeicherten Daten
            global $wpdb;

            $filter = '';

            if ( $selected_tasks ) {
                $filter = "WHERE state = '{$selected_tasks}'";
            }

            // SQL-Abfrage, um Kandidaten des aktuellen Benutzers abzurufen
            $query = $wpdb->prepare( "
                SELECT *
                FROM {$wpdb->prefix}te_applications
                {$filter}
                ORDER BY added DESC
            " );
    
            // Stellen abrufen
            $applications = $wpdb->get_results( $query );

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