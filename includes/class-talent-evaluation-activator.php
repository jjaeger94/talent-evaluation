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
class Talent_Evaluation_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
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
				'content' => '[edit_user_data_form]',
				'slug' => 'account',
				'template' => '', // optional: Vorlage für die Seite
			),
			array(
				'title' => 'Consent',
				'content' => '[consent_form]',
				'slug' => 'consent',
				'template' => '', // optional: Vorlage für die Seite
			),
		);
	
		foreach ( $pages as $page ) {
			$page_check = get_page_by_title( $page['title'] );
	
			// Wenn die Seite noch nicht vorhanden ist, füge sie hinzu
			if ( ! $page_check ) {
				$page_data = array(
					'post_title'   => $page['title'],
					'post_content' => $page['content'],
					'post_status'  => 'publish',
					'post_author'  => 1, // ID des Autors der Seite
					'post_type'    => 'page',
					'post_name'    => $page['slug'],
					'page_template' => $page['template'] // optional: Vorlage für die Seite
				);
	
				wp_insert_post( $page_data );
			}
		}

		function wp_create_database_tables() {
			global $wpdb;
				
			$charset_collate = $wpdb->get_charset_collate();
		
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			$jobs = $wpdb->prefix . 'te_jobs';
			if( $wpdb->get_var("SHOW TABLES LIKE '{$jobs}'") != $jobs ){
				$sql = "CREATE TABLE $jobs (`ID` INT NOT NULL AUTO_INCREMENT , `user_id` INT NOT NULL ,`location` VARCHAR(255) NOT NULL , `job_title` VARCHAR(255) NOT NULL , `added` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `edited` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `criteria1` VARCHAR(255) NOT NULL , `criteria2` VARCHAR(255) NOT NULL , `criteria3` VARCHAR(255) NOT NULL , `completeness` TINYINT NOT NULL , `screening` TINYINT NOT NULL , `state` VARCHAR(255) NOT NULL DEFAULT 'active', PRIMARY KEY (`ID`)) $charset_collate;";
				dbDelta( $sql );
			}
			$applications = $wpdb->prefix . 'te_applications';
			if( $wpdb->get_var("SHOW TABLES LIKE '{$applications}'") != $applications ){
				$sql = "CREATE TABLE $applications (`ID` INT NOT NULL AUTO_INCREMENT , `job_id` INT NOT NULL , `user_id` INT NOT NULL, `prename` VARCHAR(255) NOT NULL , `surname` VARCHAR(255) NOT NULL , `email` VARCHAR(255) NOT NULL , `salutation` INT NOT NULL , `classification` INT NOT NULL , `added` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `edited` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `review_id` INT NOT NULL , `filepath` VARCHAR(255) NOT NULL , `state` VARCHAR(255) NOT NULL DEFAULT 'new', PRIMARY KEY (`ID`)) $charset_collate;";
				dbDelta( $sql );
			}
			$reviews = $wpdb->prefix . 'te_reviews';
			if( $wpdb->get_var("SHOW TABLES LIKE '{$reviews}'") != $reviews ){
				$sql = "CREATE TABLE $reviews (`ID` INT NOT NULL AUTO_INCREMENT , `application_id` INT NOT NULL , `added` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `edited` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `criteria` INT NOT NULL DEFAULT '-1' , `completeness` INT NOT NULL DEFAULT '-1' , `screening` INT NOT NULL DEFAULT '-1' , `commitment` INT NOT NULL DEFAULT '-1' , `consent` INT NOT NULL DEFAULT '-1' , `filepath` VARCHAR(255) NOT NULL ,  PRIMARY KEY (`ID`)) $charset_collate;";
				dbDelta( $sql );
			}
			$backlogs = $wpdb->prefix . 'te_backlogs';
			if( $wpdb->get_var("SHOW TABLES LIKE '{$backlogs}'") != $backlogs ){
				$sql = "CREATE TABLE $backlogs (`ID` INT NOT NULL AUTO_INCREMENT , `application_id` INT NOT NULL , `user_id` INT NOT NULL, `added` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `edited` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `log` TEXT NOT NULL , `comment` TEXT NOT NULL , PRIMARY KEY (`ID`), INDEX application_id (`application_id`)) $charset_collate;";
				dbDelta( $sql );
			}
		}

		wp_create_database_tables();

	}

}
