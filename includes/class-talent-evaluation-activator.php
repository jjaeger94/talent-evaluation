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
                'title' => 'Dienstleister',
                'content' => '[show_tasks]',
                'slug' => 'dienstleister',
                'template' => '', // optional: Vorlage für die Seite
            ),
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
                'title' => 'Kandidaten',
                'content' => '[show_applications]',
                'slug' => 'kandidaten',
                'template' => '', // optional: Vorlage für die Seite
            ),
            array(
                'title' => 'Kandidat hinzufügen',
                'content' => '[create_application_form]',
                'slug' => 'kandidaten-hinzufuegen',
                'template' => '', // optional: Vorlage für die Seite
            ),
            array(
                'title' => 'Aufgaben Details',
                'content' => '[task_details]',
                'slug' => 'task-details',
                'template' => '', // optional: Vorlage für die Seite
            ),
            array(
                'title' => 'Bewerbungs Details',
                'content' => '[application_details]',
                'slug' => 'bewerbung-details',
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
                'title' => 'Consent',
                'content' => '[consent_form]',
                'slug' => 'consent',
                'template' => '', // optional: Vorlage für die Seite
            ),
            array(
                'title' => 'Test hinzufügen',
                'content' => '[create_test]',
                'slug' => 'test-hinzufuegen',
                'template' => '', // optional: Vorlage für die Seite
            ),
            array(
                'title' => 'Test Details',
                'content' => '[edit_test]',
                'slug' => 'test-details',
                'template' => '', // optional: Vorlage für die Seite
            ),
            array(
                'title' => 'Frage Details',
                'content' => '[edit_question]',
                'slug' => 'frage-details',
                'template' => '', // optional: Vorlage für die Seite
            ),
            array(
                'title' => 'Tests',
                'content' => '[show_tests]',
                'slug' => 'tests',
                'template' => '', // optional: Vorlage für die Seite
            ),
            array(
                'title' => 'Bewerber Test',
                'content' => '[commitment_test]',
                'slug' => 'bewerber-test',
                'template' => '', // optional: Vorlage für die Seite
            ),
            array(
                'title' => 'Test methode',
                'content' => '[test_methode]',
                'slug' => 'test-methode',
                'template' => '', // optional: Vorlage für die Seite
            ),
            array(
                'title' => 'Test starten',
                'content' => '[test_start]',
                'slug' => 'test-starten',
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
            // $jobs = $wpdb->prefix . 'te_jobs';
            // if ($wpdb->get_var("SHOW TABLES LIKE '{$jobs}'") != $jobs) {
            //     $sql = "CREATE TABLE $jobs
			// 		(ID INT NOT NULL AUTO_INCREMENT ,
			// 		user_id INT NOT NULL ,
			// 		test_id INT NOT NULL ,
			// 		location VARCHAR(255) NOT NULL ,
			// 		job_title VARCHAR(255) NOT NULL ,
			// 		added TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
			// 		edited TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
			// 		criteria1 VARCHAR(255) NOT NULL ,
			// 		criteria2 VARCHAR(255) NOT NULL ,
			// 		criteria3 VARCHAR(255) NOT NULL ,
			// 		completeness TINYINT NOT NULL ,
			// 		screening TINYINT NOT NULL ,
			// 		state VARCHAR(255) NOT NULL DEFAULT active,
			// 		PRIMARY KEY (ID))
			// 		$charset_collate;";
            //     dbDelta($sql);
            // }
            // $applications = $wpdb->prefix . 'te_applications';
            // if ($wpdb->get_var("SHOW TABLES LIKE '{$applications}'") != $applications) {
            //     $sql = "CREATE TABLE $applications
			// 		(ID INT NOT NULL AUTO_INCREMENT ,
			// 		job_id INT NOT NULL ,
			// 		user_id INT NOT NULL,
			// 		prename VARCHAR(255) NOT NULL ,
			// 		surname VARCHAR(255) NOT NULL ,
			// 		email VARCHAR(255) NOT NULL ,
			// 		salutation INT NOT NULL ,
			// 		classification INT NOT NULL ,
			// 		added TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
			// 		edited TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
			// 		review_id INT NOT NULL ,
			// 		filepath VARCHAR(255) NOT NULL ,
			// 		state VARCHAR(255) NOT NULL DEFAULT new,
			// 		PRIMARY KEY (ID))
			// 		$charset_collate;";
            //     dbDelta($sql);
            // }
            // $reviews = $wpdb->prefix . 'te_reviews';
            // if ($wpdb->get_var("SHOW TABLES LIKE '{$reviews}'") != $reviews) {
            //     $sql = "CREATE TABLE $reviews
			// 		(ID INT NOT NULL AUTO_INCREMENT ,
			// 		application_id INT NOT NULL ,
			// 		added TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
			// 		edited TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
			// 		criteria INT NOT NULL DEFAULT -1 ,
			// 		completeness INT NOT NULL DEFAULT -1 ,
			// 		screening INT NOT NULL DEFAULT -1 ,
			// 		commitment INT NOT NULL DEFAULT -1 ,
			// 		consent INT NOT NULL DEFAULT -1 ,
			// 		ilepath VARCHAR(255) NOT NULL ,
			// 		PRIMARY KEY (ID))
			// 		$charset_collate;";
            //     dbDelta($sql);
            // }
            // $backlogs = $wpdb->prefix . 'te_backlogs';
            // if ($wpdb->get_var("SHOW TABLES LIKE '{$backlogs}'") != $backlogs) {
            //     $sql = "CREATE TABLE $backlogs
			// 		(ID INT NOT NULL AUTO_INCREMENT ,
			// 		application_id INT NOT NULL ,
			// 		user_id INT NOT NULL,
			// 		added TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
			// 		edited TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
			// 		log TEXT NOT NULL ,
			// 		comment TEXT NOT NULL ,
			// 		PRIMARY KEY (ID),
			// 		INDEX application_id (application_id))
			// 		$charset_collate;";
            //     dbDelta($sql);
            // }
            // $test = $wpdb->prefix . 'te_tests';
            // if ($wpdb->get_var("SHOW TABLES LIKE '{$test}'") != $test) {
            //     $sql = "CREATE TABLE  $test
			// 		(ID INT NOT  NULL AUTO_INCREMENT ,
			// 		added TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
			// 		edited TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
			// 		title VARCHAR(255) NOT NULL ,
			// 		description TEXT NOT NULL ,
			// 		book_title VARCHAR(255) NOT NULL ,
			// 		affiliate_link VARCHAR(255) NOT NULL ,
			// 		image_link VARCHAR(255) NOT NULL ,
			// 		PRIMARY KEY (ID))
			// 		$charset_collate;";
            //     dbDelta($sql);
            // }
            // $questions = $wpdb->prefix . 'te_questions';
            // if ($wpdb->get_var("SHOW TABLES LIKE '{$questions}'") != $questions) {
            //     $sql = "CREATE TABLE  $questions
			// 		(ID INT NOT  NULL AUTO_INCREMENT ,
			// 		added TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
			// 		edited TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
			// 		test_id INT NOT NULL ,
			// 		question_text TEXT NOT NULL,
			// 		answer_text TEXT NOT NULL ,
			// 		PRIMARY KEY (ID),
			// 		INDEX test_id (test_id))
			// 		$charset_collate;";
            //     dbDelta($sql);
            // }
            // $answers = $wpdb->prefix . 'te_answers';
            // if ($wpdb->get_var("SHOW TABLES LIKE '{$answers}'") != $answers) {
            //     $sql = "CREATE TABLE  $answers(
			// 		ID INT NOT  NULL AUTO_INCREMENT ,
			// 		added TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
			// 		edited TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
			// 		application_id INT NOT NULL ,
			// 		question_id INT NOT NULL ,
			// 		answer_text TEXT NOT NULL,
			// 		PRIMARY KEY (ID) ,
			// 		FOREIGN KEY (question_id) REFERENCES $questions(ID) ,
			// 		FOREIGN KEY (application_id) REFERENCES $applications(ID))
			// 		$charset_collate;";
            //     dbDelta($sql);
            // }
            $talents = $wpdb->prefix . 'te_talents';
            if ($wpdb->get_var("SHOW TABLES LIKE '{$talents}'") != $talents) {
                $sql = "CREATE TABLE  $talents(
					ID INT NOT  NULL AUTO_INCREMENT ,
                    member_id INT,
                    availability INT,
					added TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
					edited TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
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
            $school = $wpdb->prefix . 'te_school';
            if ($wpdb->get_var("SHOW TABLES LIKE '{$school}'") != $school) {
                $sql = "CREATE TABLE $school (
					ID INT AUTO_INCREMENT PRIMARY KEY,
					talent_id INT,
					degree INT,
					added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
					edited TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
					FOREIGN KEY (talent_id) REFERENCES $talents(ID)
				) $charset_collate;";
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
