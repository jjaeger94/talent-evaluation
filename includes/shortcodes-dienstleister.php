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
    }

    function render_job_details() {
        if (current_user_can('dienstleister')) {
            global $wpdb;
            $customers = $wpdb->get_results("SELECT ID, company_name FROM {$wpdb->prefix}te_customers");
            if ( isset( $_GET['id'] ) ) {
                $id = intval( $_GET['id'] );
                // Abfrage, um Talentdetails abzurufen
                $job = get_job_by_id($id);

                // Überprüfen, ob das Talent gefunden wurde
                if ($job) {
                    ob_start(); // Puffer starten
                    include_once('templates/job-detail-template.php'); // Pfad zur Datei mit dem Test-Formular
                    return ob_get_clean(); 
                } else {
                    // Talent nicht gefunden
                    return '<p>ID nicht gefunden.</p>';
                }
            } else if(isset( $_GET['add']) && $_GET['add'] == true){
                $job = [];
                ob_start(); // Puffer starten
                include_once('templates/job-detail-template.php'); // Pfad zur Datei mit dem Test-Formular
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
            include_once('templates/jobs-table-template.php'); // Pfad zur Datei mit dem Test-Formular
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
                    include_once('templates/customer-detail-template.php'); // Pfad zur Datei mit dem Test-Formular
                    return ob_get_clean(); 
                } else {
                    // Talent nicht gefunden
                    return '<p>ID nicht gefunden.</p>';
                }
            } else if(isset( $_GET['add']) && $_GET['add'] == true){
                $customer = [];
                ob_start(); // Puffer starten
                include_once('templates/customer-detail-template.php'); // Pfad zur Datei mit dem Test-Formular
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
            include_once('templates/customers-table-template.php'); // Pfad zur Datei mit dem Test-Formular
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
                    ob_start(); // Puffer starten
                    include_once('templates/talent-detail-template.php'); // Pfad zur Datei mit dem Test-Formular
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
            global $wpdb;
            $talents_table = $wpdb->prefix . 'te_talents';
            $talents = $wpdb->get_results("SELECT * FROM $talents_table ORDER BY added DESC");

            $postal_code = isset( $_GET['postal_code'] ) ? sanitize_text_field( $_GET['postal_code'] ) : null;

            $radius = isset( $_GET['radius'] ) ? intval( $_GET['radius'] ) : 0;

            if ($postal_code && $radius) {           
                // Postleitzahlen im gewünschten Radius erhalten
                $countryCode = 'DE'; // Deutschland
                $postal_codes = getPostalCodesInRadius($postal_code, $radius, $countryCode);
                // Überprüfen, ob Postleitzahlen gefunden wurden
                if ($postal_codes !== false) {
                    // Talente nach den gefundenen Postleitzahlen filtern
                    $talents = array_filter($talents, function($talent) use ($postal_codes) {
                        return in_array($talent->post_code, $postal_codes);
                    });
                }
            }

            // Überprüfen, ob Talente vorhanden sind
            ob_start(); // Puffer starten
            include_once('templates/talents-table-template.php'); // Pfad zur Datei mit dem Test-Formular
            return ob_get_clean(); 
        } else {
            return 'Bitte loggen Sie sich ein, um Ihre Talente zu sehen.';
        }
    }