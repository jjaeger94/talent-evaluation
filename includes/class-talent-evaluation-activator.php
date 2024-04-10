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
				'title' => 'Firmenkunden',
				'content' => 'Inhalt der Firmenkunden-Seite',
				'slug' => 'firmenkunden',
				'template' => '', // optional: Vorlage für die Seite
			),
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
				'title' => 'Bewerber',
				'content' => '[show_applications]',
				'slug' => 'bewerber',
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
			)
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

		flush_rewrite_rules();
	}

}
