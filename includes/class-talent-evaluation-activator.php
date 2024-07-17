<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Talent_Evaluation
 * @subpackage Talent_Evaluation/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Talent_Evaluation
 * @subpackage Talent_Evaluation/includes
 * @author     Jan Jäger <janjaeger2020@gmail.com>
 */
class Talent_Evaluation_Activator
{

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate()
    {
        // Seiten für Firmenkunden und Dienstleister erstellen
        $pages = array(
            array(
                'title' => 'Stellen',
                'content' => '[show_jobs]',
                'slug' => 'jobs',
                'template' => '', // optional: Vorlage für die Seite
            ),
            array(
                'title' => 'Job Details',
                'content' => '[job_details]',
                'slug' => 'job-details',
                'template' => '', // optional: Vorlage für die Seite
            ),
            array(
                'title' => 'Account',
                'content' => '[swpm_profile_form]',
                'slug' => 'account',
                'template' => '', // optional: Vorlage für die Seite
            ),
            array(
                'title' => 'Sales Game',
                'content' => '[chatbot_page]',
                'slug' => 'sales-game',
                'template' => '', // optional: Vorlage für die Seite
            ),
            array(
                'title' => 'Talents',
                'content' => '[show_talents]',
                'slug' => 'talents',
                'template' => '', // optional: Vorlage für die Seite
            ),
            array(
                'title' => 'Talent Details',
                'content' => '[talent_details]',
                'slug' => 'talent-details',
                'template' => '', // optional: Vorlage für die Seite
            ),
            array(
                'title' => 'Vergleich',
                'content' => '[compare_details]',
                'slug' => 'compare-details',
                'template' => '', // optional: Vorlage für die Seite
            ),
            array(
                'title' => 'Matching',
                'content' => '[matching_talent]',
                'slug' => 'matching',
                'template' => '', // optional: Vorlage für die Seite
            ),
            array(
                'title' => 'Preferences',
                'content' => '[preferences_talent]',
                'slug' => 'preferences',
                'template' => '', // optional: Vorlage für die Seite
            ),
            array(
                'title' => 'Contact',
                'content' => '[contact_talent]',
                'slug' => 'contact',
                'template' => '', // optional: Vorlage für die Seite
            ),
            array(
                'title' => 'Matching Overview',
                'content' => '[show_matchings]',
                'slug' => 'matching-overview',
                'template' => '', // optional: Vorlage für die Seite
            ),
            array(
                'title' => 'Bewertung',
                'content' => '[show_evaluations]',
                'slug' => 'evaluations',
                'template' => '', // optional: Vorlage für die Seite
            ),
            array(
                'title' => 'Events',
                'content' => '[show_events]',
                'slug' => 'events',
                'template' => '', // optional: Vorlage für die Seite
            ),
            array(
                'title' => 'Profile',
                'content' => '[profile_talent]',
                'slug' => 'profile',
                'template' => '', // optional: Vorlage für die Seite
            ),
            array(
                'title' => 'Kunden',
                'content' => '[show_customers]',
                'slug' => 'customers',
                'template' => '', // optional: Vorlage für die Seite
            ),
            array(
                'title' => 'Kunden Details',
                'content' => '[customer_details]',
                'slug' => 'customer-details',
                'template' => '', // optional: Vorlage für die Seite
            ),
            array(
                'title' => 'Game Details',
                'content' => '[game_details]',
                'slug' => 'game-details',
                'template' => '', // optional: Vorlage für die Seite
            ),
            array(
                'title' => 'Games',
                'content' => '[show_games]',
                'slug' => 'games',
                'template' => '', // optional: Vorlage für die Seite
            ),
            array(
                'title' => 'Product Details',
                'content' => '[product_details]',
                'slug' => 'product-details',
                'template' => '', // optional: Vorlage für die Seite
            ),
        );

        foreach ($pages as $page) {
            $page_check = get_page_by_title($page['title']);

            // Wenn die Seite noch nicht vorhanden ist, füge sie hinzu
            if (!$page_check) {
                $page_data = array(
                    'post_title' => $page['title'],
                    'post_content' => $page['content'],
                    'post_status' => 'publish',
                    'post_author' => 1, // ID des Autors der Seite
                    'post_type' => 'page',
                    'post_name' => $page['slug'],
                    'page_template' => $page['template'], // optional: Vorlage für die Seite
                );

                wp_insert_post($page_data);
            }
        }

        function wp_create_database_tables()
        {
            global $wpdb;

            $charset_collate = $wpdb->get_charset_collate();

            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
            $customers = $wpdb->prefix . 'te_customers';
            if ($wpdb->get_var("SHOW TABLES LIKE '{$customers}'") != $customers) {
                $sql = "CREATE TABLE $customers(
					ID INT AUTO_INCREMENT PRIMARY KEY,
                    member_id INT,
                    state INT NOT NULL DEFAULT 0,
					added TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
					edited TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    company_name VARCHAR(255) NOT NULL,
					prename VARCHAR(255),
					surname VARCHAR(255),
					email VARCHAR(255),
                    mobile VARCHAR(20),
                    position VARCHAR(255))
					$charset_collate;";
                dbDelta($sql);
            }
            $jobs = $wpdb->prefix . 'te_jobs';
            if ($wpdb->get_var("SHOW TABLES LIKE '{$jobs}'") != $jobs) {
                $sql = "CREATE TABLE $jobs(
					ID INT AUTO_INCREMENT PRIMARY KEY,
                    state INT NOT NULL DEFAULT 1,
                    customer_id INT NOT NULL,
                    job_title VARCHAR(255) NOT NULL ,
                    link VARCHAR(255),
                    job_info TEXT,
                    added TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
					edited TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    post_code VARCHAR(5) NOT NULL ,
                    school INT,
                    availability INT,
                    license TINYINT(1),
                    home_office TINYINT(1),
                    part_time TINYINT(1),
					FOREIGN KEY (customer_id) REFERENCES $customers(ID)
				) $charset_collate;";
                dbDelta($sql);
            }
            $talents = $wpdb->prefix . 'te_talents';
            if ($wpdb->get_var("SHOW TABLES LIKE '{$talents}'") != $talents) {
                $sql = "CREATE TABLE  $talents(
					ID INT NOT  NULL AUTO_INCREMENT ,
                    notifications INT UNSIGNED NOT NULL DEFAULT 255
                    member_id INT,
                    school INT,
                    mobility INT,
                    english INT,
                    license TINYINT(1),
                    home_office TINYINT(1),
                    part_time TINYINT(1),
                    notes TEXT,
                    availability INT,
					added TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
					edited TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
					prename VARCHAR(255) NOT NULL ,
					surname VARCHAR(255) NOT NULL ,
					email VARCHAR(255) NOT NULL ,
					mobile VARCHAR(20) NOT NULL ,
					post_code VARCHAR(5) NOT NULL ,
					oai_test_id VARCHAR(255) NOT NULL ,
					ref VARCHAR(255) NOT NULL ,
					PRIMARY KEY (ID) )
					$charset_collate;";
                dbDelta($sql);
            }
            $apprenticeship = $wpdb->prefix . 'te_apprenticeship';
            if ($wpdb->get_var("SHOW TABLES LIKE '{$apprenticeship}'") != $apprenticeship) {
                $sql = "CREATE TABLE $apprenticeship (
					ID INT AUTO_INCREMENT PRIMARY KEY,
					talent_id INT,
					field INT,
					designation VARCHAR(255),
                    start_date DATE,
                    end_date DATE,
					added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
					edited TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
					FOREIGN KEY (talent_id) REFERENCES $talents(ID)
				) $charset_collate;";
                dbDelta($sql);
            }
            $studies = $wpdb->prefix . 'te_studies';
            if ($wpdb->get_var("SHOW TABLES LIKE '{$studies}'") != $studies) {
                $sql = "CREATE TABLE $studies (
					ID INT AUTO_INCREMENT PRIMARY KEY,
					talent_id INT,
					field INT,
					degree INT,
                    start_date DATE,
                    end_date DATE,
					designation VARCHAR(255),
					added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
					edited TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
					FOREIGN KEY (talent_id) REFERENCES $talents(ID)
				) $charset_collate;";
                dbDelta($sql);
            }
            $eq = $wpdb->prefix . 'te_eq';
            if ($wpdb->get_var("SHOW TABLES LIKE '{$eq}'") != $eq) {
                $sql = "CREATE TABLE $eq (
					ID INT AUTO_INCREMENT PRIMARY KEY,
					talent_id INT,
					value TEXT,
					added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
					edited TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
					FOREIGN KEY (talent_id) REFERENCES $talents(ID)
				) $charset_collate;";
                dbDelta($sql);
            }
            $experiences = $wpdb->prefix . 'te_experiences';
            if ($wpdb->get_var("SHOW TABLES LIKE '{$experiences}'") != $experiences) {
                $sql = "CREATE TABLE $experiences (
                    ID INT AUTO_INCREMENT PRIMARY KEY,
                    talent_id INT,
                    position VARCHAR(255),
                    company VARCHAR(255),
                    field INT,
                    start_date DATE,
                    end_date DATE,
                    added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    edited TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    FOREIGN KEY (talent_id) REFERENCES $talents(ID)
                ) $charset_collate;";
                dbDelta($sql);
            }
            $requirements = $wpdb->prefix . 'te_requirements';
            if ($wpdb->get_var("SHOW TABLES LIKE '{$requirements}'") != $requirements) {
                $sql = "CREATE TABLE $requirements (
                    ID INT AUTO_INCREMENT PRIMARY KEY,
                    job_id INT,
                    type INT,
                    field INT,
                    degree INT,
                    activity TEXT,
                    added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    edited TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    FOREIGN KEY (job_id) REFERENCES $jobs(ID)
                ) $charset_collate;";
                dbDelta($sql);
            }
            $matching = $wpdb->prefix . 'te_matching';
            if ($wpdb->get_var("SHOW TABLES LIKE '{$matching}'") != $matching) {
                $sql = "CREATE TABLE $matching (
                    ID INT AUTO_INCREMENT PRIMARY KEY,
                    job_id INT,
                    talent_id INT,
                    value INT NOT NULL DEFAULT 0 ,
                    added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    edited TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    FOREIGN KEY (job_id) REFERENCES $jobs(ID),
                    FOREIGN KEY (talent_id) REFERENCES $talents(ID)
                ) $charset_collate;";
                dbDelta($sql);
            }
            $preferences = $wpdb->prefix . 'te_preferences';
            if ($wpdb->get_var("SHOW TABLES LIKE '{$preferences}'") != $preferences) {
                $sql = "CREATE TABLE $preferences (
                    ID INT AUTO_INCREMENT PRIMARY KEY,
                    job_id INT,
                    talent_id INT,
                    value INT NOT NULL DEFAULT 0 ,
                    added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    edited TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    FOREIGN KEY (job_id) REFERENCES $jobs(ID),
                    FOREIGN KEY (talent_id) REFERENCES $talents(ID)
                ) $charset_collate;";
                dbDelta($sql);
            }
            $events = $wpdb->prefix . 'te_events';
            if ($wpdb->get_var("SHOW TABLES LIKE '{$events}'") != $events) {
                $sql = "CREATE TABLE $events (
                    ID INT AUTO_INCREMENT PRIMARY KEY,
                    talent_id INT NULL,
                    job_id INT NULL,
                    matching_id INT NULL,
                    user_id INT NOT NULL,
                    event_type INT NOT NULL,
                    event_description TEXT NOT NULL,
                    added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    edited TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    FOREIGN KEY (talent_id) REFERENCES $talents(ID),
                    FOREIGN KEY (job_id) REFERENCES $jobs(ID),
                    FOREIGN KEY (matching_id) REFERENCES $matching(ID)
                ) $charset_collate;";
                dbDelta($sql);
            }
            $evaluations = $wpdb->prefix . 'te_evaluations';
            if ($wpdb->get_var("SHOW TABLES LIKE '{$evaluations}'") != $evaluations) {
                $sql = "CREATE TABLE $evaluations (
                    ID INT AUTO_INCREMENT PRIMARY KEY,
                    talent_id INT NULL,
                    rating INT NOT NULL,
                    comment TEXT NULL,
                    added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    edited TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    FOREIGN KEY (talent_id) REFERENCES $talents(ID),
                ) $charset_collate;";
                dbDelta($sql);
            }
            $documents = $wpdb->prefix . 'te_documents';
            if ($wpdb->get_var("SHOW TABLES LIKE '{$documents}'") != $documents) {
                $sql = "CREATE TABLE $documents (
                    ID INT AUTO_INCREMENT PRIMARY KEY,
                    talent_id INT NULL,
                    type INT NOT NULL,
                    path VARCHAR(255) NOT NULL,
                    added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    edited TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    FOREIGN KEY (talent_id) REFERENCES $talents(ID)
                ) $charset_collate;";
                dbDelta($sql);
            }
            $games = $wpdb->prefix . 'te_games';
            if ($wpdb->get_var("SHOW TABLES LIKE '{$games}'") != $games) {
                $sql = "CREATE TABLE $games (
                    ID INT AUTO_INCREMENT PRIMARY KEY,
                    gamekey VARCHAR(20) NOT NULL,
                    title VARCHAR(20) NOT NULL,
                    assistant_id VARCHAR(255),
                    type INT NOT NULL,
                    image_url VARCHAR(255),
                    start_msg TEXT,
                    info_title VARCHAR(255),
                    info_msg TEXT,
                    info_text TEXT,
                    first_msg TEXT,
                    added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    edited TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                ) $charset_collate;";
                dbDelta($sql);
            }
            $products = $wpdb->prefix . 'te_products';
            if ($wpdb->get_var("SHOW TABLES LIKE '{$products}'") != $products) {
                $sql = "CREATE TABLE $products (
                    ID INT AUTO_INCREMENT PRIMARY KEY,
                    game_id INT NULL,
                    product_name VARCHAR(255),
                    product_description TEXT,
                    image_url VARCHAR(255),
                    type INT NOT NULL,
                    added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    edited TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    FOREIGN KEY (game_id) REFERENCES $games(ID)
                ) $charset_collate;";
                dbDelta($sql);
            }
        }

        wp_create_database_tables();

    }

}
