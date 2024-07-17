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
        add_shortcode('show_games', 'render_games_table');
        add_shortcode('game_details', 'render_game_details');
        add_shortcode('product_details', 'render_product_details');
        add_shortcode('job_details', 'render_job_details');
        add_shortcode('compare_details', 'render_compare_details');
        add_shortcode('show_matchings', 'render_matching_table');
        add_shortcode('show_evaluations', 'render_evaluation_table');
        add_shortcode('show_events', 'render_events_table');
    }

    function render_compare_details(){
        // Überprüfen, ob die oai_test_id in der URL vorhanden ist
        if (has_service_permission()) {
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
        if (has_service_permission()) {
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
        if (has_service_permission()) {
            $selected_state = array_key_exists('state', $_GET) ? intval($_GET['state']) : -1;
            $notes = isset($_GET['notes']) ? sanitize_text_field($_GET['notes']) : '';

            global $wpdb;
            $jobs_table = $wpdb->prefix . 'te_jobs'; // Tabellenname anpassen
            $matching_table = $wpdb->prefix . 'te_matching';
            $customers_table = $wpdb->prefix . 'te_customers';

            // Basis-Query mit Platzhalter
            $query = "
                SELECT j.*, 
                    c.company_name,
                    c.state AS company_state,
                    (
                        SELECT COUNT(*)
                        FROM {$matching_table} m
                        WHERE m.job_id = j.ID
                        AND m.value BETWEEN 0 AND 10
                    ) AS positive_matching_count
                FROM {$jobs_table} j
                JOIN {$customers_table} c ON j.customer_id = c.ID
                WHERE j.notes LIKE %s
                AND j.customer_id != 1
            ";

            // Überprüfen, ob der ausgewählte Status >= 0 ist, und die Bedingung hinzufügen
            if($selected_state >= 0){
                $query .= " AND j.state = %d";
            }

            // Fügen Sie die ORDER BY Klausel hinzu, um nach positive_matching_count zu sortieren
            $query .= " ORDER BY positive_matching_count DESC, j.edited ASC";

            // Übergeben Sie die Parameter an die prepare-Funktion
            if($selected_state >= 0){
                $jobs = $wpdb->get_results($wpdb->prepare($query, '%' . $wpdb->esc_like($notes) . '%', '%' . $wpdb->esc_like($notes) . '%', $selected_state));
            } else {
                $jobs = $wpdb->get_results($wpdb->prepare($query, '%' . $wpdb->esc_like($notes) . '%', '%' . $wpdb->esc_like($notes) . '%'));
            }

            // Filterformular einfügen
            ob_start(); // Puffer starten
            include TE_DIR . 'filters/job-filter.php'; // Pfad zur Datei mit dem Filterformular
            $filter_form = ob_get_clean();

            // Überprüfen, ob Talente vorhanden sind
            ob_start(); // Puffer starten
            include TE_DIR.'tables/jobs-table-template.php'; // Pfad zur Datei mit dem Test-Formular
            $table = ob_get_clean(); 
            return $filter_form.$table;
        } else {
            return 'Bitte loggen Sie sich ein, um Ihre Stellen zu sehen.';
        }
    }

    function render_customer_details() {
        if (has_service_permission()) {
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

    function render_game_details() {
        if (has_service_permission()) {
            if ( isset( $_GET['id'] ) ) {
                $id = intval( $_GET['id'] );
                // Abfrage, um Talentdetails abzurufen
                $game = get_game_by_id($id);
                $products = get_products_for_game_id($game->ID);

                // Überprüfen, ob das Talent gefunden wurde
                if ($game) {
                    ob_start(); // Puffer starten
                    include TE_DIR.'details/game-detail-template.php'; // Pfad zur Datei mit dem Test-Formular
                    return ob_get_clean(); 
                } else {
                    // Talent nicht gefunden
                    return '<p>ID nicht gefunden.</p>';
                }
            } else if(isset( $_GET['add']) && $_GET['add'] == true){
                $game = [];
                ob_start(); // Puffer starten
                include TE_DIR.'details/game-detail-template.php'; // Pfad zur Datei mit dem Test-Formular
                return ob_get_clean(); 
            } else {
                return '<p>ID nicht übergeben.</p>';
            }
        } else {
            return '<p>Keine Berechtigung.</p>';
        }
    }

    function render_product_details() {
        if (has_service_permission()) {
            if (isset($_GET['id'])) {
                $id = intval($_GET['id']);
                // Abfrage, um Talentdetails abzurufen
                $product = get_product_by_id($id);
    
                // Überprüfen, ob das Talent gefunden wurde
                if ($product) {
                    ob_start(); // Puffer starten
                    include TE_DIR . 'forms/product-form.php'; // Pfad zur Datei mit dem Test-Formular
                    return ob_get_clean();
                } else {
                    // Talent nicht gefunden
                    return '<p>ID nicht gefunden.</p>';
                }
            } elseif (isset($_GET['add']) && $_GET['add'] == true) {
                // Neues Produkt hinzufügen
                $product = new stdClass();
                $product->game_id = isset($_GET['game_id']) ? intval($_GET['game_id']) : 0;
                ob_start(); // Puffer starten
                include TE_DIR . 'forms/product-form.php'; // Pfad zur Datei mit dem Test-Formular
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
        if (has_service_permission()) {
            // Abfrage, um Talente abzurufen
            global $wpdb;
            $customers_table = $wpdb->prefix . 'te_customers';
            $customers = $wpdb->get_results("SELECT * FROM $customers_table ORDER BY added DESC");

            // Überprüfen, ob Talente vorhanden sind
            ob_start(); // Puffer starten
            include TE_DIR.'tables/customers-table-template.php'; // Pfad zur Datei mit dem Test-Formular
            return ob_get_clean(); 
        } else {
            return 'Keine Berechtigung';
        }
    }

    // Benutzerdefinierte Funktion, um die Kunden-Tabelle zu erstellen
    function render_games_table() {
        // Überprüfen, ob der Benutzer eingeloggt ist
        if (has_service_permission()) {
            // Abfrage, um Talente abzurufen
            global $wpdb;
            $games_table = $wpdb->prefix . 'te_games';
            $games = $wpdb->get_results("SELECT * FROM $games_table ORDER BY added DESC");

            // Überprüfen, ob Talente vorhanden sind
            ob_start(); // Puffer starten
            include TE_DIR.'tables/games-table-template.php'; // Pfad zur Datei mit dem Test-Formular
            return ob_get_clean(); 
        } else {
            return 'Keine Berechtigung';
        }
    }

    function render_talent_details() {
        // Überprüfen, ob die oai_test_id in der URL vorhanden ist
        if (has_service_permission()) {
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
                    // get_mat = get_jobs_for_talent($talent, $apprenticeships, $studies, $experiences);
                    $jobs = get_jobs_for_talent($talent, $apprenticeships, $studies, $experiences);
                    $demojobs = get_demojobs();
                    $events = get_talent_events($talent->ID);
                    $resumes = get_uploaded_resumes_for_talent($talent->ID);
                    $documents = get_uploaded_documents_for_talent($talent->ID);

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
        if (has_service_permission()) {
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
                    }else{
                        $matching_count = get_active_matching_count_for_talent_id($result->ID);
                        if($selected_state == 'registered' && $result->member_id && SwpmMemberUtils::get_member_field_by_id($result->member_id, 'user_name') && $matching_count == 0){
                            array_push($talents, $result);
                        }else if($selected_state == 'in_progress' && $result->member_id && SwpmMemberUtils::get_member_field_by_id($result->member_id, 'user_name')  && $matching_count > 0){
                            array_push($talents, $result);
                        }
                    }
                }
            }else{
                $talents = $results;
            }
            // Filterformular einfügen
            ob_start(); // Puffer starten
            include TE_DIR . 'filters/talents-filter.php'; // Pfad zur Datei mit dem Filterformular
            $filter_form = ob_get_clean();

            // Tabelleninhalt einfügen
            ob_start();
            include TE_DIR . 'tables/talents-table-template.php'; // Pfad zur Datei mit dem Tabellen-Template
            $table_content = ob_get_clean();

            // Kombinierten Inhalt zurückgeben
            return $filter_form . $table_content;
        } else {
            return 'Keine Berechtigung';
        }
    }

    function render_matching_table() {
        // Überprüfen, ob der Benutzer eingeloggt ist
        if (has_service_permission()) {
            // Abfrage, um Talente abzurufen

            $selected_value = isset($_GET['value']) ? sanitize_text_field($_GET['value']) : -1;
            $state = isset($_GET['state']) ? sanitize_text_field($_GET['state']) : '';

            global $wpdb;

            $matchings = array();

            $matchings_table = $wpdb->prefix . 'te_matching';
            $talents_table = $wpdb->prefix . 'te_talents';
            $jobs_table = $wpdb->prefix . 'te_jobs';
            $customers_table = $wpdb->prefix . 'te_customers';

            if ($selected_value >= 0) {
                $query = $wpdb->prepare("
                    SELECT m.*, t.prename, t.surname, j.job_title, j.notes, j.company, j.customer_id, c.company_name
                    FROM {$matchings_table} m
                    LEFT JOIN {$jobs_table} j ON m.job_id = j.id
                    LEFT JOIN {$talents_table} t ON m.talent_id = t.id
                    LEFT JOIN {$customers_table} c ON j.customer_id = c.ID
                    WHERE m.value = %d AND j.notes LIKE %s
                    ORDER BY m.added DESC
                ", $selected_value, '%' . $wpdb->esc_like($state) . '%');

                $matchings = $wpdb->get_results($query);
            } else {
                $query = $wpdb->prepare("
                    SELECT m.*, t.prename, t.surname, j.job_title, j.notes, j.company, j.customer_id, c.company_name
                    FROM {$matchings_table} m
                    LEFT JOIN {$jobs_table} j ON m.job_id = j.id
                    LEFT JOIN {$talents_table} t ON m.talent_id = t.id
                    LEFT JOIN {$customers_table} c ON j.customer_id = c.ID
                    WHERE j.notes LIKE %s
                    ORDER BY m.added DESC
                ", '%' . $wpdb->esc_like($state) . '%');

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
            return 'Keine Berechtigung';
        }
    }
    
    function render_events_table() {
        // Überprüfen, ob der Benutzer eingeloggt ist
        if (has_service_permission()) {
            // Abfrage, um Talente abzurufen
            global $wpdb;


            $query = "SELECT e.*, t.prename,t.surname FROM {$wpdb->prefix}te_events e
            LEFT JOIN {$wpdb->prefix}te_talents t ON e.talent_id = t.id
            ORDER BY e.edited DESC
            LIMIT 20";

            $events = $wpdb->get_results($query);
            // Tabelleninhalt einfügen
            ob_start();
            include TE_DIR . 'tables/events-table-template.php'; // Pfad zur Datei mit dem Tabellen-Template
            return ob_get_clean();
        } else {
            return 'Keine Berechtigung';
        }
    }

    function render_evaluation_table() {
        // Überprüfen, ob der Benutzer eingeloggt ist
        if (has_service_permission()) {
            // Abfrage, um Talente abzurufen
            global $wpdb;


            $query = "SELECT e.*, t.prename,t.surname FROM {$wpdb->prefix}te_evaluations e
            LEFT JOIN {$wpdb->prefix}te_talents t ON e.talent_id = t.id
            ORDER BY e.edited DESC";

            $evaluations = $wpdb->get_results($query);
            // Tabelleninhalt einfügen
            ob_start();
            include TE_DIR . 'tables/evaluation-table-template.php'; // Pfad zur Datei mit dem Tabellen-Template
            return ob_get_clean();
        } else {
            return 'Keine Berechtigung';
        }
    }