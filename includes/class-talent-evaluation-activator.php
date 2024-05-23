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
                'slug' => 'stellen',
                'template' => '', // optional: Vorlage für die Seite
            ),
            array(
                'title' => 'Stellen hinzufügen',
                'content' => '[add_job_form]',
                'slug' => 'stelle-hinzufuegen',
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
					added TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
					edited TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    company_name VARCHAR(255) NOT NULL,
					prename VARCHAR(255),
					surname VARCHAR(255),
					email VARCHAR(255))
					$charset_collate;";
                dbDelta($sql);
            }
            $jobs = $wpdb->prefix . 'te_jobs';
            if ($wpdb->get_var("SHOW TABLES LIKE '{$jobs}'") != $jobs) {
                $sql = "CREATE TABLE $jobs(
					ID INT AUTO_INCREMENT PRIMARY KEY,
                    customer_id INT NOT NULL,
                    job_title VARCHAR(255) NOT NULL ,
                    added TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
					edited TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    post_code VARCHAR(5) NOT NULL ,
                    school INT,
                    mobility INT,
                    availability INT,
                    license TINYINT(1),
                    home_office TINYINT(1),
					FOREIGN KEY (customer_id) REFERENCES $customers(ID)
				) $charset_collate;";
                dbDelta($sql);
            }
            $talents = $wpdb->prefix . 'te_talents';
            if ($wpdb->get_var("SHOW TABLES LIKE '{$talents}'") != $talents) {
                $sql = "CREATE TABLE  $talents(
					ID INT NOT  NULL AUTO_INCREMENT ,
                    member_id INT,
                    school INT,
                    mobility INT,
                    license TINYINT(1),
                    home_office TINYINT(1),
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

        }

        wp_create_database_tables();

    }

}
