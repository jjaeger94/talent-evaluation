<?php
    /**
     * Hier alle Shortcodes für Dienstleister eintragen!
     */
    function register_shortcodes_dienstleister() {
        add_shortcode('show_talents', 'render_talents_table');
        add_shortcode('talent_details', 'render_talent_details');
        add_shortcode('show_customers', 'render_customers_table');
        add_shortcode('customer_details', 'render_customer_details');
        add_shortcode('show_jobs', 'render_jobs_table');
        add_shortcode('job_details', 'render_job_details');
        add_shortcode('compare_details', 'render_compare_details');
        add_shortcode('show_matchings', 'render_matching_overview');
    }

    function render_compare_details(){
        // Überprüfen, ob die oai_test_id in der URL vorhanden ist
        if (current_user_can('dienstleister')) {
            if ( isset( $_GET['job_id'], $_GET['talent_id']) ) {
                $customers = get_all_customers();
                $talent_id = intval( $_GET['talent_id'] );
                $job_id = intval( $_GET['job_id'] );
                // Abfrage, um Talentdetails abzurufen
                $talent = get_talent_by_id($talent_id);
                $job = get_job_by_id($job_id);

                // Überprüfen, ob das Talent gefunden wurde
                if ($talent && $job) {
                    $apprenticeships = get_apprenticeships_by_talent_id($talent->ID);
                    $studies = get_studies_by_talent_id($talent->ID);
                    $experiences = get_experiences_by_talent_id($talent->ID);
                    $eq = get_eq_by_talent_id($talent->ID);
                    $requirements = get_requirements_for_job_id($job->ID);
                    $grouped_requirements = [];
                    $matching = get_matching_for_ids($talent_id, $job_id);
                    foreach ($requirements as $requirement) {
                        $grouped_requirements[$requirement->type][] = $requirement;
                    }
                    ob_start(); // Puffer starten
                    include TE_DIR.'details/compare-detail-template.php'; // Pfad zur Datei mit dem Test-Formular
                    return ob_get_clean(); 
                } else {
                    // Talent nicht gefunden
                    return '<p>ID nicht gefunden.</p>';
                }
            } else {
                return '<p>ID nicht übergeben.</p>';
            }
        } else {
            return '<p>Keine Berechtigung.</p>';
        }
    }

    function render_job_details() {
        if (current_user_can('dienstleister')) {
            global $wpdb;
            $customers = get_all_customers();
            if ( isset( $_GET['id'] ) ) {
                $id = intval( $_GET['id'] );
                // Abfrage, um Talentdetails abzurufen
                $job = get_job_by_id($id);
                $requirements = get_requirements_for_job_id($id);
                $talents = get_talents_for_job($job, $requirements);

                // Überprüfen, ob das Talent gefunden wurde
                if ($job) {
                    ob_start(); // Puffer starten
                    include TE_DIR.'details/job-detail-template.php'; // Pfad zur Datei mit dem Test-Formular
                    return ob_get_clean(); 
                } else {
                    // Talent nicht gefunden
                    return '<p>ID nicht gefunden.</p>';
                }
            } else if(isset( $_GET['add']) && $_GET['add'] == true){
                $job = [];
                ob_start(); // Puffer starten
                include TE_DIR.'details/job-detail-template.php'; // Pfad zur Datei mit dem Test-Formular
                return ob_get_clean(); 
            } else {
                return '<p>ID nicht übergeben.</p>';
            }
        } else {
            return '<p>Keine Berechtigung.</p>';
        }
    }

    // Benutzerdefinierte Funktion, um die Kunden-Tabelle zu erstellen
    function render_jobs_table() {
        // Überprüfen, ob der Benutzer eingeloggt ist
        if (current_user_can('dienstleister')) {
            // Abfrage, um Talente abzurufen
            global $wpdb;
            $jobs_table = $wpdb->prefix . 'te_jobs';
            $jobs = $wpdb->get_results("SELECT * FROM $jobs_table ORDER BY added DESC");

            // Überprüfen, ob Talente vorhanden sind
            ob_start(); // Puffer starten
            include TE_DIR.'tables/jobs-table-template.php'; // Pfad zur Datei mit dem Test-Formular
            return ob_get_clean(); 
        } else {
            return 'Bitte loggen Sie sich ein, um Ihre Stellen zu sehen.';
        }
    }

    function render_customer_details() {
        if (current_user_can('dienstleister')) {
            if ( isset( $_GET['id'] ) ) {
                $id = intval( $_GET['id'] );
                // Abfrage, um Talentdetails abzurufen
                $customer = get_customer_by_id($id);

                // Überprüfen, ob das Talent gefunden wurde
                if ($customer) {
                    ob_start(); // Puffer starten
                    include TE_DIR.'details/customer-detail-template.php'; // Pfad zur Datei mit dem Test-Formular
                    return ob_get_clean(); 
                } else {
                    // Talent nicht gefunden
                    return '<p>ID nicht gefunden.</p>';
                }
            } else if(isset( $_GET['add']) && $_GET['add'] == true){
                $customer = [];
                ob_start(); // Puffer starten
                include TE_DIR.'details/customer-detail-template.php'; // Pfad zur Datei mit dem Test-Formular
                return ob_get_clean(); 
            } else {
                return '<p>ID nicht übergeben.</p>';
            }
        } else {
            return '<p>Keine Berechtigung.</p>';
        }
    }

    // Benutzerdefinierte Funktion, um die Kunden-Tabelle zu erstellen
    function render_customers_table() {
        // Überprüfen, ob der Benutzer eingeloggt ist
        if (current_user_can('dienstleister')) {
            // Abfrage, um Talente abzurufen
            global $wpdb;
            $customers_table = $wpdb->prefix . 'te_customers';
            $customers = $wpdb->get_results("SELECT * FROM $customers_table ORDER BY added DESC");

            // Überprüfen, ob Talente vorhanden sind
            ob_start(); // Puffer starten
            include TE_DIR.'tables/customers-table-template.php'; // Pfad zur Datei mit dem Test-Formular
            return ob_get_clean(); 
        } else {
            return 'Bitte loggen Sie sich ein, um Ihre Talente zu sehen.';
        }
    }

    function render_talent_details() {
        // Überprüfen, ob die oai_test_id in der URL vorhanden ist
        if (current_user_can('dienstleister')) {
            if ( isset( $_GET['id'] ) ) {
                $id = intval( $_GET['id'] );
                // Abfrage, um Talentdetails abzurufen
                $talent = get_talent_by_id($id);

                // Überprüfen, ob das Talent gefunden wurde
                if ($talent) {
                    // Abfrage, um den Chatverlauf abzurufen
                    $messages = list_messages_by_thread($talent->oai_test_id);
                    // $school = get_school_by_talent_id($talent->ID);
                    $apprenticeships = get_apprenticeships_by_talent_id($talent->ID);
                    $studies = get_studies_by_talent_id($talent->ID);
                    $experiences = get_experiences_by_talent_id($talent->ID);
                    $eq = get_eq_by_talent_id($talent->ID);
                    $jobs = get_jobs_for_talent($talent, $apprenticeships, $studies, $experiences);
                    $events = get_talent_events($talent->ID);

                    ob_start(); // Puffer starten
                    include TE_DIR.'details/talent-detail-template.php'; // Pfad zur Datei mit dem Test-Formular
                    return ob_get_clean(); 
                } else {
                    // Talent nicht gefunden
                    return '<p>ID nicht gefunden.</p>';
                }
            } else {
                return '<p>ID nicht übergeben.</p>';
            }
        } else {
            return '<p>Keine Berechtigung.</p>';
        }
    }

    // Benutzerdefinierte Funktion, um die Talent-Tabelle zu erstellen
    function render_talents_table() {
        // Überprüfen, ob der Benutzer eingeloggt ist
        if (current_user_can('dienstleister')) {
            // Abfrage, um Talente abzurufen
            $ref = isset($_GET['ref']) ? sanitize_text_field($_GET['ref']) : null;
            $selected_state = isset($_GET['state']) ? sanitize_text_field($_GET['state']) : '';

            global $wpdb;
            $talents_table = $wpdb->prefix . 'te_talents';
            
            if ($ref) {
                // Sicherstellen, dass die ref korrekt in der SQL-Abfrage verwendet wird
                $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM $talents_table WHERE ref LIKE %s ORDER BY added DESC", $ref.'%'));
            } else {
                $results = $wpdb->get_results("SELECT * FROM $talents_table ORDER BY added DESC");
            }
            
            if($selected_state != ''){
                $talents = [];
                foreach ($results as $result){
                    if($selected_state == 'new' && !$result->member_id){
                        array_push($talents, $result);
                    }else if($selected_state == 'waiting' && $result->member_id && !SwpmMemberUtils::get_member_field_by_id($result->member_id, 'user_name')){
                        array_push($talents, $result);
                    }else if($selected_state == 'registered' && $result->member_id && SwpmMemberUtils::get_member_field_by_id($result->member_id, 'user_name')){
                        array_push($talents, $result);
                    }
                }
            }else{
                $talents = $results;
            }
            // Filterformular einfügen
            ob_start(); // Puffer starten
            include TE_DIR . 'filters/ref-state-filter.php'; // Pfad zur Datei mit dem Filterformular
            $filter_form = ob_get_clean();

            // Tabelleninhalt einfügen
            ob_start();
            include TE_DIR . 'tables/talents-table-template.php'; // Pfad zur Datei mit dem Tabellen-Template
            $table_content = ob_get_clean();

            // Kombinierten Inhalt zurückgeben
            return $filter_form . $table_content;
        } else {
            return 'Bitte loggen Sie sich ein, um Ihre Talente zu sehen.';
        }
    }

    function render_matching_overview() {
        // Überprüfen, ob der Benutzer eingeloggt ist
        if (current_user_can('dienstleister')) {
            // Abfrage, um Talente abzurufen

            $selected_state = isset($_GET['state']) ? sanitize_text_field($_GET['state']) : -1;

            global $wpdb;
            
            $matchings = array();

            if ($selected_state >= 0) {
                $query = $wpdb->prepare("
                    SELECT m.*, t.prename,t.surname, j.job_title
                    FROM {$wpdb->prefix}te_matching m
                    LEFT JOIN {$wpdb->prefix}te_talents t ON m.talent_id = t.id
                    LEFT JOIN {$wpdb->prefix}te_jobs j ON m.job_id = j.id
                    WHERE m.value = %d
                    ORDER BY m.added DESC
                ", $selected_state);

                $matchings = $wpdb->get_results($query);
            } else {
                $query = "
                    SELECT m.*, t.prename,t.surname, j.job_title
                    FROM {$wpdb->prefix}te_matching m
                    LEFT JOIN {$wpdb->prefix}te_talents t ON m.talent_id = t.id
                    LEFT JOIN {$wpdb->prefix}te_jobs j ON m.job_id = j.id
                    ORDER BY m.added DESC
                ";

                $matchings = $wpdb->get_results($query);
            }



            // Filterformular einfügen
            ob_start(); // Puffer starten
            include TE_DIR . 'filters/matching-filter.php'; // Pfad zur Datei mit dem Filterformular
            $filter_form = ob_get_clean();

            // Tabelleninhalt einfügen
            ob_start();
            include TE_DIR . 'tables/matching-table-template.php'; // Pfad zur Datei mit dem Tabellen-Template
            $table_content = ob_get_clean();

            // Kombinierten Inhalt zurückgeben
            return $filter_form . $table_content;
        } else {
            return 'Bitte loggen Sie sich ein, um Ihre Talente zu sehen.';
        }
    }