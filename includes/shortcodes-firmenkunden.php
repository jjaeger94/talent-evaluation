<?php    
     /**
     * Hier alle Shortcodes für Firmenkunden eintragen!
     */
    function register_shortcodes_firmenkunden() {
        add_shortcode( 'add_job_form', 'add_job_form_shortcode' );
        add_shortcode( 'show_jobs', 'show_jobs_table' );
        add_shortcode( 'create_application_form', 'render_create_application_form' );
        add_shortcode( 'show_applications', 'show_applications_table' );
        add_shortcode( 'application_details', 'render_application_details_shortcode' );
        add_shortcode( 'job_details', 'render_job_details_shortcode' );
        add_shortcode('edit_user_data_form', 'render_edit_user_data_form');
        add_shortcode('logout_button', 'render_logout_button');
        add_shortcode( 'application_button', 'render_application_button' );
    }

    function render_application_button() {
        if ( is_user_logged_in() ) {
            $user = wp_get_current_user();
            $button_text = 'Zur Kandidatenverwaltung';
            $button_url = get_user_home_url($user); // Anpassen Sie die URL entsprechend Ihrer Seitenstruktur    
            $output = '<a class="btn btn-primary" href="' . esc_url( $button_url ) . '">' . esc_html( $button_text ) . '</a>';
        } else {
            $login_url = wp_login_url( home_url() ); // Login-URL für den Fall, dass der Benutzer nicht eingeloggt ist
            $output = '<a class="btn btn-primary" href="' . esc_url( $login_url ) . '">Jetzt einloggen</a>';
        }
    
        return $output;
    }    

    function render_edit_user_data_form() {
        // Logout-Button rendern
        ob_start();
        render_logout_button();
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
            ob_start();
            include( plugin_dir_path( __FILE__ ) . 'templates/forms/job-form.php' );
            return ob_get_clean();
        }else{
            return 'Sie haben keine Berechtigung, dieses Formular anzuzeigen.';
        }
    }

    function render_create_application_form() {
        if ( current_user_can( 'firmenkunde' ) ) {
            $jobs = get_active_jobs();
            if (empty( $jobs )){
                return '<div class="alert alert-info" role="alert">Bitte legen Sie zuerst eine Stelle an um einen Kandidaten hinzuzufügen.</div>';
            }
            ob_start();
            include plugin_dir_path( __FILE__ ) . 'templates/forms/create-application-form.php';
            return ob_get_clean();
        }else{
            return 'Sie haben keine Berechtigung, dieses Formular anzuzeigen.';
        }
    }

    // Kurzer Shortcode zum Anzeigen der Kandidatentabelle
    function show_applications_table() {
        // Überprüfen, ob der Benutzer eingeloggt ist
        if ( current_user_can( 'firmenkunde' ) ) {
            // ID des aktuellen Benutzers abrufen
            $user_id = get_current_user_id();
            
            $jobs = get_active_jobs();

            $selected_job = isset( $_GET['job_id'] ) ? intval( $_GET['job_id'] ) : 0;

            $selected_tasks = isset( $_GET['filter_tasks'] ) ? sanitize_text_field( $_GET['filter_tasks'] ) : null;

            // Erfassen Sie die in den Optionen gespeicherten Daten
            global $wpdb;

            $filter = "WHERE user_id = {$user_id}";
            if ( $selected_job ) {
                $filter .= " AND job_id = {$selected_job}";
            }

            if ( $selected_tasks ) {
                $filter .= " AND state = '{$selected_tasks}'";
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
                $job_id = $application->job_id;

                foreach ( $jobs as $job ) {
                    if ( $job->ID == $job_id ) {
                        $job_title = $job->job_title;
                        break;
                    }
                }

                $application->job_title = $job_title;

                if ($application->review_id) {
                    $application->review = get_review_by_application($application);
                }

            }
            // Tabelle aus Vorlagendatei einfügen
            ob_start();
            include plugin_dir_path( __FILE__ ) . 'templates/applications-table-template.php';
            $output = ob_get_clean();
    
            return $output;
        } else {
            return 'Bitte loggen Sie sich ein, um Ihre Kandidaten zu sehen.';
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

function get_application_count_for_job($job_id) {
    global $wpdb;

    // Tabellenname für die Bewerbungen
    $table_name = $wpdb->prefix . 'te_applications';

    // SQL-Abfrage, um die Anzahl der Kandidaten für den Job abzurufen
    $query = $wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE job_id = %d", $job_id);

    // Anzahl der Kandidaten abrufen
    $application_count = $wpdb->get_var($query);

return $application_count;
}

function get_ongoing_application_count_for_job($job_id) {
    global $wpdb;

    // Tabellenname für die Bewerbungen
    $table_name = $wpdb->prefix . 'te_applications';

    // SQL-Abfrage, um die Anzahl der Kandidaten für den Job abzurufen
    $query = $wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE job_id = %d AND state != 'passed' AND state != 'failed'", $job_id);

    // Anzahl der Kandidaten abrufen
    $application_count = $wpdb->get_var($query);

return $application_count;
}

function get_failed_application_count_for_job($job_id) {
    global $wpdb;

    // Tabellenname für die Bewerbungen
    $table_name = $wpdb->prefix . 'te_applications';

    // SQL-Abfrage, um die Anzahl der Kandidaten für den Job abzurufen
    $query = $wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE job_id = %d AND state = 'failed'", $job_id);

    // Anzahl der Kandidaten abrufen
    $application_count = $wpdb->get_var($query);

return $application_count;
}

function get_passed_application_count_for_job($job_id) {
    global $wpdb;

    // Tabellenname für die Bewerbungen
    $table_name = $wpdb->prefix . 'te_applications';

    // SQL-Abfrage, um die Anzahl der Kandidaten für den Job abzurufen
    $query = $wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE job_id = %d AND state = 'passed'", $job_id);

    // Anzahl der Kandidaten abrufen
    $application_count = $wpdb->get_var($query);

return $application_count;
}

function show_jobs_table() {    
    // Überprüfen, ob der Benutzer eingeloggt ist
    if ( is_user_logged_in() ) {
        // ID des aktuellen Benutzers abrufen
        $user_id = get_current_user_id();

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
    
// Funktion zum Rendern des Bewerbungsdetail-Shortcodes
function render_application_details_shortcode() {
    // Überprüfen, ob der Benutzer eingeloggt ist und Berechtigung hat
    if ( current_user_can( 'firmenkunde' ) ) {
        // Überprüfen, ob die ID-Parameter übergeben wurde
        if ( isset( $_GET['id'] ) ) {
            // ID-Parameter aus der URL abrufen
            $application_id = intval( $_GET['id'] );

            $application = get_application_by_id($application_id);
            if ( $application ) {

                $job = get_job_by_id($application->job_id);

                if ($application->review_id) {
                    $application->review = get_review_by_application($application);
                }
                // Tabelle aus Vorlagendatei einfügen
                ob_start();
                include plugin_dir_path( __FILE__ ) . 'templates/application-detail-template.php';
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

function render_job_details_shortcode() {
    if ( current_user_can( 'firmenkunde' ) || current_user_can( 'dienstleister' ) ) {
        // Überprüfen, ob die ID-Parameter übergeben wurde
        if ( isset( $_GET['id'] ) ) {
            // ID-Parameter aus der URL abrufen
            $job_id = intval( $_GET['id'] );

            $job = get_job_by_id($job_id);
            if ( $job ) {
                $member_id = SwpmMemberUtils::get_logged_in_members_id();
                $company = SwpmMemberUtils::get_member_field_by_id($member_id, 'company_name');
                $test = get_test_by_id($job->test_id);
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

