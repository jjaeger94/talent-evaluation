<?php    
     /**
     * Hier alle Shortcodes für Firmenkunden eintragen!
     */
    function register_shortcodes_firmenkunden() {
        add_shortcode( 'add_job_form', 'add_job_form_shortcode' );
        add_shortcode( 'show_jobs', 'show_jobs_table' );
        add_shortcode( 'job_details', 'render_job_details_shortcode' );
        add_shortcode('edit_user_data_form', 'render_edit_user_data_form');
    }

    function render_edit_user_data_form() {
        // Logout-Button rendern
        ob_start();
        $logout_button = ob_get_clean();
        if ( current_user_can( 'firmenkunde' ) ) {
            $user_id = get_current_user_id();
            $user_info = get_userdata($user_id);
            ob_start();
            include plugin_dir_path( __FILE__ ) . 'templates/forms/edit-user-form.php';
            $form_content = ob_get_clean();    
            // Formularinhalt mit Logout-Button zurückgeben
            return $form_content . '<br><div class="logout-button">' . $logout_button . '</div>';
        } else if(is_user_logged_in()){
            return $logout_button;
        }else{
            return 'Sie haben keine Berechtigung, dieses Formular anzuzeigen.';
        }
    }
    

    function add_job_form_shortcode() {
        if ( current_user_can( 'firmenkunde' ) ) {
            $test_id = 0;
            global $wpdb;
            $tests = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}te_tests");
            ob_start();
            include( plugin_dir_path( __FILE__ ) . 'templates/forms/job-form.php' );
            return ob_get_clean();
        }else{
            return 'Sie haben keine Berechtigung, dieses Formular anzuzeigen.';
        }
    }

    // Funktion zum Abrufen aller aktiven Stellen aus der Datenbank
    function get_active_jobs() {
        if(current_user_can( 'firmenkunde' )){
            $user_id = get_current_user_id();

            global $wpdb;

            // SQL-Abfrage, um alle aktiven Stellen abzurufen
            $query = $wpdb->prepare( "
                SELECT ID, job_title
                FROM {$wpdb->prefix}te_jobs
                WHERE user_id = %d
                AND state = 'active'
                ORDER BY added DESC
            ", $user_id );

            // Stellen abrufen
            $jobs = $wpdb->get_results( $query );

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
        $hash = commitment_hash($user_id); // Hier den Hash-Wert einfügen

        // URL für die Bewerber-Testseite mit den Parametern uid und key (hash)
        $test_page_url = add_query_arg(array('uid' => $user_id,'key' => $hash), home_url('/bewerber-test/'));

        // Erfassen Sie die in den Optionen gespeicherten Daten
        global $wpdb;

        // SQL-Abfrage, um Stellen des aktuellen Benutzers abzurufen
        $query = $wpdb->prepare( "
            SELECT ID, job_title, added, state, location
            FROM {$wpdb->prefix}te_jobs
            WHERE user_id = %d
            ORDER BY added DESC
        ", $user_id );

        // Stellen abrufen
        $jobs = $wpdb->get_results( $query );

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
    
function render_job_details_shortcode() {
    if ( current_user_can( 'firmenkunde' ) || current_user_can( 'dienstleister' ) ) {
        // Überprüfen, ob die ID-Parameter übergeben wurde
        if ( isset( $_GET['id'] ) ) {
            // ID-Parameter aus der URL abrufen
            $job_id = intval( $_GET['id'] );

            $job = get_job_by_id($job_id);
            if ( $job ) {
                global $wpdb;
                $member_id = SwpmMemberUtils::get_logged_in_members_id();
                $company = SwpmMemberUtils::get_member_field_by_id($member_id, 'company_name');
                $hash = commitment_hash($job->ID);
                $tests = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}te_tests");
                $test_id = $job->test_id;
                $test_page_url = add_query_arg(array('jid' => $job->ID,'key' => $hash), home_url('/bewerber-test/'));
                // Tabelle aus Vorlagendatei einfügen
                ob_start();
                include plugin_dir_path( __FILE__ ) . 'templates/job-detail-template.php';
                $output = ob_get_clean();
            } else {
                // Keine Bewerbungsdetails gefunden, Nachricht ausgeben
                $output = '<div class="alert alert-info" role="alert">Es wurde keine Stelle gefunden.</div>';
            }

            return $output;
        } else {
            // Keine ID-Parameter übergeben, Meldung ausgeben
            return '<div class="alert alert-warning" role="alert">Es wurde keine Stellen-ID angegeben.</div>';
        }
    } else {
        // Benutzer hat keine Berechtigung, Meldung ausgeben
        return 'Sie haben keine Berechtigung, diese Seite anzuzeigen.';
    }
}

