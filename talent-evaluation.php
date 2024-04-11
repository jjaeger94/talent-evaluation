<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           Talent_Evaluation
 *
 * @wordpress-plugin
 * Plugin Name:       Talent Evaluation
 * Plugin URI:        https://github.com/jjaeger94/talent-evaluation
 * Description:       Plugin für Commit IQ zum Hinterlegen und evaluieren von bewerbern
 * Version:           1.0.0
 * Author:            Jan Jäger
 * Author URI:        https://www.linkedin.com/in/jaegerjan/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       talent-evaluation
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'TALENT_EVALUATION_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-talent-evaluation-activator.php
 */
function activate_talent_evaluation() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-talent-evaluation-activator.php';
	Talent_Evaluation_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-talent-evaluation-deactivator.php
 */
function deactivate_talent_evaluation() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-talent-evaluation-deactivator.php';
	Talent_Evaluation_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_talent_evaluation' );
register_deactivation_hook( __FILE__, 'deactivate_talent_evaluation' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-talent-evaluation.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_talent_evaluation() {

	$plugin = new Talent_Evaluation();
	$plugin->run();

}

// Funktion zum Öffnen der Datenbankverbindung
function open_database_connection() {
    global $connection;

    // Überprüfen, ob bereits eine Verbindung besteht
    if ( isset( $connection ) && is_object( $connection ) ) {
        return $connection;
    }

    // Erfassen Sie die in den Optionen gespeicherten Daten
    $db_host = get_option('te_db_host');
    $db_name = get_option('te_db_name');
    $db_user = get_option('te_db_user');
    $db_password = get_option('te_db_password');

    // Versuchen Sie, eine temporäre Datenbankverbindung herzustellen
    $temp_db = new wpdb($db_user, $db_password, $db_name, $db_host);

    // Überprüfen, ob die Verbindung erfolgreich war
    if ( ! is_wp_error( $temp_db ) ) {
        $connection = $temp_db;
        return $connection;
    } else {
        // Behandeln Sie den Fehler, wenn die Verbindung fehlschlägt
        // Zum Beispiel, eine Fehlermeldung anzeigen oder protokollieren
        return $temp_db;
    }
}

function get_application_by_id( $application_id ) {
    if ( current_user_can( 'firmenkunde' ) ) {
        $user_id = get_current_user_id();
        // Datenbankverbindung öffnen
        $temp_db = open_database_connection();

        // SQL-Abfrage, um die Bewerbungsdetails abzurufen
        $query = $temp_db->prepare( "
            SELECT *
            FROM {$temp_db->prefix}applications
            WHERE ID = {$application_id}
            AND user_id = {$user_id}
        ");

        // Bewerbungsdetails abrufen
        $application = $temp_db->get_results( $query );

        // Überprüfen, ob Bewerbungsdetails vorhanden sind
        return ! empty( $application ) ? $application[0] : null;
    } else if ( current_user_can( 'dienstleister' ) ) {
        // Datenbankverbindung öffnen
        $temp_db = open_database_connection();

        // SQL-Abfrage, um die Bewerbungsdetails abzurufen
        $query = $temp_db->prepare( "
            SELECT *
            FROM {$temp_db->prefix}applications
            WHERE ID = %d
        ", $application_id );

        // Bewerbungsdetails abrufen
        $application = $temp_db->get_results( $query );

        // Überprüfen, ob Bewerbungsdetails vorhanden sind
        return ! empty( $application ) ? $application[0] : null;
    } else {
        return null;
    }
}

function get_job_by_id( $job_id ) {
    if ( current_user_can( 'firmenkunde' ) ) {
        $user_id = get_current_user_id();
        // Datenbankverbindung öffnen
        $temp_db = open_database_connection();

        // SQL-Abfrage, um die Jobdetails abzurufen
        $query = $temp_db->prepare( "
            SELECT *
            FROM {$temp_db->prefix}jobs
            WHERE ID = {$job_id}
            AND user_id = {$user_id}
        ");

        // Jobdetails abrufen
        $job = $temp_db->get_results( $query );

        // Überprüfen, ob Jobdetails vorhanden sind
        return ! empty( $job ) ? $job[0] : null;
    } else if ( current_user_can( 'dienstleister' ) ) {
        // Datenbankverbindung öffnen
        $temp_db = open_database_connection();

        // SQL-Abfrage, um die Jobdetails abzurufen
        $query = $temp_db->prepare( "
            SELECT *
            FROM {$temp_db->prefix}jobs
            WHERE ID = %d
        ", $job_id );

        // Jobdetails abrufen
        $job = $temp_db->get_results( $query );

        // Überprüfen, ob Jobdetails vorhanden sind
        return ! empty( $job ) ? $job[0] : null;
    }else{
        return null;
    }
}

function update_application_filepath($application_id, $file_directory){
	$temp_db = open_database_connection();
	// Tabellenname für Bewerbungen
	$table_name = $temp_db->prefix . 'applications';

	// Daten zum Aktualisieren
	$data = array('filepath' => $file_directory);

	// Bedingung für die Aktualisierung
	$where = array('ID' => $application_id);

	// Aktualisieren der Daten in der Datenbank
	$temp_db->update($table_name, $data, $where);

	// Überprüfen, ob ein Fehler aufgetreten ist
	if ($temp_db->last_error !== '') {
		wp_send_json_error('Fehler beim Aktualisieren des Dateipfads in der Datenbank.');
	}
}

function get_review_by_application($application){
    if ( current_user_can( 'firmenkunde' ) ) {
        $user_id = get_current_user_id();
        if ($application->user_id != $user_id){
            return null;
        }
    } else if ( !current_user_can( 'dienstleister' ) ) {
        return null;
    }
    //Datenbankverbindung öffnen
    $temp_db = open_database_connection();

    // SQL-Abfrage, um die Bewerbungsdetails abzurufen
    $query = $temp_db->prepare( "
        SELECT *
        FROM {$temp_db->prefix}reviews
        WHERE ID = %d
    ", $application->review_id );

    // Bewerbungsdetails abrufen
    $review = $temp_db->get_results( $query );

    return ! empty( $review ) ? $review[0] : null;
}

function get_backlogs_by_application( $application ) {
    if ( current_user_can( 'firmenkunde' ) ) {
        $user_id = get_current_user_id();
        if ($application->user_id != $user_id){
            return null;
        }
    } else if ( !current_user_can( 'dienstleister' ) ) {
        return null;
    }
    //Datenbankverbindung öffnen
    $temp_db = open_database_connection();

    // SQL-Abfrage, um die Bewerbungsdetails abzurufen
    $query = $temp_db->prepare( "
        SELECT *
        FROM {$temp_db->prefix}backlogs
        WHERE application_id = %d
        ORDER BY added DESC
    ", $application->ID );

    // Bewerbungsdetails abrufen
    return $temp_db->get_results( $query );

}

function create_backlog_entry($application_id, $log, $comment = ''){
    $user_id = get_current_user_id();
    $temp_db = open_database_connection();

    $table_name = $temp_db->prefix . 'backlogs';

    $result = $temp_db->insert(
        $table_name,
        array(
            'application_id' => $application_id, 
            'user_id' => $user_id,
            'log' => $log,
            'comment' => $comment,
        ),
        array(
            '%d',
            '%d',
            '%s',
            '%s',
        )
    );
}

function update_job_state($job_id, $state){
    $temp_db = open_database_connection();
    // Tabellenname für Bewerbungen
    $table_name = $temp_db->prefix . 'jobs';

    // Daten zum Aktualisieren
    $data = array('state' => $state);

    // Bedingung für die Aktualisierung
    $where = array('ID' => $job_id);

    // Aktualisieren der Daten in der Datenbank
    $temp_db->update($table_name, $data, $where);
}

function update_application_state($application_id, $state, $comment = ''){
    $temp_db = open_database_connection();
    // Tabellenname für Bewerbungen
    $table_name = $temp_db->prefix . 'applications';

    // Daten zum Aktualisieren
    $data = array('state' => $state);

    // Bedingung für die Aktualisierung
    $where = array('ID' => $application_id);

    // Aktualisieren der Daten in der Datenbank
    $temp_db->update($table_name, $data, $where);
    
    $log = 'Status zu "'.$state.'" geändert';

    create_backlog_entry($application_id, $log, $comment);
}

function add_review_to_application($application_id){
    $application = get_application_by_id($application_id);

    if(!$application){
        return null;
    }else if($application->review_id){
        return $application->review_id;
    }else{
        $temp_db = open_database_connection();
        // Tabellenname für Bewerbungen
        $table_name = $temp_db->prefix . 'reviews';

        $result = $temp_db->insert(
            $table_name,
            array(
                'application_id' => $application_id
            ),
            array(
                '%d'
            )
        );
        if($result){
            //get id 
            $lastid = $temp_db->insert_id;
            // Tabellenname für Bewerbungen
            $table_name = $temp_db->prefix . 'applications';

            // Daten zum Aktualisieren
            $data = array('review_id' => $lastid);

            // Bedingung für die Aktualisierung
            $where = array('ID' => $application_id);

            // Aktualisieren der Daten in der Datenbank
            $temp_db->update($table_name, $data, $where);

            $log = 'Prüfung begonnen';

            create_backlog_entry($application_id, $log);
            return $lastid;
        }else{
            return null;
        }
    }
}

function info_button($text) {
    
    // HTML für den Info-Button mit Popover zurückgeben
    return '<div class="info-button" data-toggle="popover" title="'.get_text_by_key($text).'"">
                <i class="fa-regular fa-circle-question"></i>
            </div>';
}

function get_text_by_key($key) {
    // Pfad zur JSON-Datei
    $json_file_path = plugin_dir_path( __FILE__ ) . './translations.json'; // Beispiel: Dateiname der JSON-Datei

    // Überprüfen, ob die Datei existiert und lesbar ist
    if (file_exists($json_file_path) && is_readable($json_file_path)) {
        // Laden des Inhalts der JSON-Datei
        $json_content = file_get_contents($json_file_path);

        // JSON-Dekodierung des Inhalts
        $translations = json_decode($json_content, true);

        // Überprüfen, ob der Schlüssel im Array vorhanden ist
        if (isset($translations[$key])) {
            // Rückgabe des Textes für den Schlüssel
            return $translations[$key];
        } else {
            // Wenn der Schlüssel nicht vorhanden ist, Rückgabe des Schlüssels selbst
            return $key;
        }
    } else {
        // Wenn die Datei nicht existiert oder nicht lesbar ist, Rückgabe des Schlüssels
        return $key;
    }
}

run_talent_evaluation();
