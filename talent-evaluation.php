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
 * Requires Plugins:  Simple WordPress Membership
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

function show_all_features(){
    return false;
}

function has_ajax_permission(){
    return current_user_can( 'dienstleister' ) || current_user_can( 'firmenkunde' );
}

function get_availability_string($availability){
    switch ($availability) {
        case 0:
            return 'Sofort';
        case 1:
            return '1 Monat';
        case 2:
            return '2 Monate';
        case 3:
            return '3 Monate';
        case 4:
            return '4 Monate';
        case 5:
            return '5 Monate';
        case 6:
            return '6 Monate';
        case 7:
            return 'Momentan nicht verfügbar';
        // Weitere Fälle hinzufügen, falls erforderlich
        default:
            return '';
    }
}

function has_edit_talent_permission($talent_id){
     if(current_user_can( 'dienstleister' )){
        return true;
     }
     $auth = SwpmAuth::get_instance();
     if (!$auth->is_logged_in()) {
        return false;
     }
    $member_id = SwpmMemberUtils::get_logged_in_members_id();
    // Überprüfen, ob Bewerbungsdetails vorhanden sind
    $talent = get_talent_by_member_id($member_id);
    if(!$talent){
        return false;
    }
    return $talent_id == $talent->ID;
}



function commitment_hash($uid){
    return substr(hash('sha256', 'diesIstEinHash' . $uid), -8);
}

function save_answer($aid, $question_id, $answer) {
    global $wpdb;

    $table_name = $wpdb->prefix . 'te_answers';

    // Neuen Eintrag in die Tabelle "answers" einfügen
    $result = $wpdb->insert(
        $table_name,
        array(
            'application_id' => $aid,
            'question_id' => $question_id,
            'answer_text' => $answer
            // Weitere Felder hinzufügen, falls erforderlich
        ),
        array(
            '%d',
            '%d',
            '%s'
            // Weitere Formatierungen hinzufügen, falls erforderlich
        )
    );

    return $result;
}

function get_talent_by_member_id($member_id){
    global $wpdb;
    $query = $wpdb->prepare( "
        SELECT *
        FROM {$wpdb->prefix}te_talents
        WHERE member_id = {$member_id}
    ");
    // Bewerbungsdetails abrufen
    $talents = $wpdb->get_results( $query );

    // Überprüfen, ob Bewerbungsdetails vorhanden sind
    return ! empty( $talents ) ? $talents[0] : null;
}

function get_talent_by_id($talent_id){
    if ( current_user_can( 'dienstleister' ) ) {
        global $wpdb;
        $query = $wpdb->prepare( "
            SELECT *
            FROM {$wpdb->prefix}te_talents
            WHERE ID = {$talent_id}
        ");
        // Bewerbungsdetails abrufen
        $talents = $wpdb->get_results( $query );

        // Überprüfen, ob Bewerbungsdetails vorhanden sind
        return ! empty( $talents ) ? $talents[0] : null;
    } else {
        return null;
    }
}

function get_question_by_id($question_id){
    if ( current_user_can( 'dienstleister' ) ) {
        global $wpdb;
        $query = $wpdb->prepare( "
            SELECT *
            FROM {$wpdb->prefix}te_questions
            WHERE ID = {$question_id}
        ");
        // Bewerbungsdetails abrufen
        $questions = $wpdb->get_results( $query );

        // Überprüfen, ob Bewerbungsdetails vorhanden sind
        return ! empty( $questions ) ? $questions[0] : null;
    } else {
        return null;
    }
}

function get_test_by_id( $test_id ) {
    if ( has_ajax_permission() ) {
        return get_test_by_id_permissionless($test_id);
    } else {
        return null;
    }
}

function get_questions_by_test_id($test_id){
        global $wpdb;
        $query = $wpdb->prepare( "
            SELECT *
            FROM {$wpdb->prefix}te_questions
            WHERE test_id = {$test_id}
        ");
        return $wpdb->get_results( $query );
}

function get_apprenticeships_by_talent_id($talent_id){
    global $wpdb;

    // SQL-Abfrage, um die Jobdetails abzurufen
    $query = $wpdb->prepare( "
        SELECT *
        FROM {$wpdb->prefix}te_apprenticeship
        WHERE talent_id = {$talent_id}
    ");

    // Jobdetails abrufen
    return $wpdb->get_results( $query );
}

function get_eq_by_talent_id($talent_id){
    global $wpdb;

    // SQL-Abfrage, um die Jobdetails abzurufen
    $query = $wpdb->prepare( "
        SELECT *
        FROM {$wpdb->prefix}te_eq
        WHERE talent_id = {$talent_id}
    ");

    // Jobdetails abrufen
    $eq = $wpdb->get_results( $query );

    // Überprüfen, ob Jobdetails vorhanden sind
    return ! empty( $eq ) ? $eq[0] : null;
}

function get_experiences_by_talent_id($talent_id){
    global $wpdb;

    // SQL-Abfrage, um die Jobdetails abzurufen
    $query = $wpdb->prepare( "
        SELECT *
        FROM {$wpdb->prefix}te_experiences
        WHERE talent_id = {$talent_id}
    ");

    // Jobdetails abrufen
    return $wpdb->get_results( $query );
}

function get_studies_by_talent_id($talent_id){
    global $wpdb;

    // SQL-Abfrage, um die Jobdetails abzurufen
    $query = $wpdb->prepare( "
        SELECT *
        FROM {$wpdb->prefix}te_studies
        WHERE talent_id = {$talent_id}
    ");

    // Jobdetails abrufen
    return $wpdb->get_results( $query );
}

function get_school_by_talent_id($talent_id){
    global $wpdb;

    // SQL-Abfrage, um die Jobdetails abzurufen
    $query = $wpdb->prepare( "
        SELECT *
        FROM {$wpdb->prefix}te_school
        WHERE talent_id = {$talent_id}
    ");

    // Jobdetails abrufen
    $school = $wpdb->get_results( $query );

    // Überprüfen, ob Jobdetails vorhanden sind
    return ! empty( $school ) ? $school[0] : null;
}

function get_application_by_id( $application_id ) {
    if ( current_user_can( 'firmenkunde' ) ) {
        $user_id = get_current_user_id();
        // Datenbankverbindung öffnen
        global $wpdb;

        // SQL-Abfrage, um die Bewerbungsdetails abzurufen
        $query = $wpdb->prepare( "
            SELECT *
            FROM {$wpdb->prefix}te_applications
            WHERE ID = {$application_id}
            AND user_id = {$user_id}
        ");

        // Bewerbungsdetails abrufen
        $application = $wpdb->get_results( $query );

        // Überprüfen, ob Bewerbungsdetails vorhanden sind
        return ! empty( $application ) ? $application[0] : null;
    } else if ( current_user_can( 'dienstleister' ) ) {
        return get_application_by_id_permissionless($application_id);
    } else {
        return null;
    }
}

function get_job_by_id( $job_id ) {
    if ( current_user_can( 'firmenkunde' ) ) {
        $user_id = get_current_user_id();
        // Datenbankverbindung öffnen
        global $wpdb;

        // SQL-Abfrage, um die Jobdetails abzurufen
        $query = $wpdb->prepare( "
            SELECT *
            FROM {$wpdb->prefix}te_jobs
            WHERE ID = {$job_id}
            AND user_id = {$user_id}
        ");

        // Jobdetails abrufen
        $job = $wpdb->get_results( $query );

        // Überprüfen, ob Jobdetails vorhanden sind
        return ! empty( $job ) ? $job[0] : null;
    } else if ( current_user_can( 'dienstleister' ) ) {
        return get_job_by_id_permissionless($job_id);
    }else{
        return null;
    }
}

function update_application_filepath($application_id, $file_directory){
	global $wpdb;
	// Tabellenname für Bewerbungen
	$table_name = $wpdb->prefix . 'te_applications';

	// Daten zum Aktualisieren
	$data = array('filepath' => $file_directory);

	// Bedingung für die Aktualisierung
	$where = array('ID' => $application_id);

	// Aktualisieren der Daten in der Datenbank
	$wpdb->update($table_name, $data, $where);

	// Überprüfen, ob ein Fehler aufgetreten ist
	if ($wpdb->last_error !== '') {
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
    global $wpdb;

    // SQL-Abfrage, um die Bewerbungsdetails abzurufen
    $query = $wpdb->prepare( "
        SELECT *
        FROM {$wpdb->prefix}te_reviews
        WHERE ID = %d
    ", $application->review_id );

    // Bewerbungsdetails abrufen
    $review = $wpdb->get_results( $query );

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
    global $wpdb;

    // SQL-Abfrage, um die Bewerbungsdetails abzurufen
    $query = $wpdb->prepare( "
        SELECT *
        FROM {$wpdb->prefix}te_backlogs
        WHERE application_id = %d
        ORDER BY added DESC
    ", $application->ID );

    // Bewerbungsdetails abrufen
    return $wpdb->get_results( $query );

}

function create_backlog_entry($application_id, $log, $comment = ''){
    $user_id = get_current_user_id();
    global $wpdb;

    $table_name = $wpdb->prefix . 'te_backlogs';

    $result = $wpdb->insert(
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
    global $wpdb;
    // Tabellenname für Bewerbungen
    $table_name = $wpdb->prefix . 'te_jobs';

    // Daten zum Aktualisieren
    $data = array('state' => $state);

    // Bedingung für die Aktualisierung
    $where = array('ID' => $job_id);

    // Aktualisieren der Daten in der Datenbank
    $wpdb->update($table_name, $data, $where);
}

function update_application_state($application_id, $state, $comment = ''){
    global $wpdb;
    // Tabellenname für Bewerbungen
    $table_name = $wpdb->prefix . 'te_applications';

    // Daten zum Aktualisieren
    $data = array('state' => $state);

    // Bedingung für die Aktualisierung
    $where = array('ID' => $application_id);

    // Aktualisieren der Daten in der Datenbank
    $wpdb->update($table_name, $data, $where);
    
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
        global $wpdb;
        // Tabellenname für Bewerbungen
        $table_name = $wpdb->prefix . 'te_reviews';

        $uniqueDir = 'consent_' . uniqid();

        $result = $wpdb->insert(
            $table_name,
            array(
                'application_id' => $application_id,
                'filepath' => $uniqueDir
            ),
            array(
                '%d',
                '%s'
            )
        );
        if($result){
            //get id 
            $lastid = $wpdb->insert_id;
            // Tabellenname für Bewerbungen
            $table_name = $wpdb->prefix . 'te_applications';

            // Daten zum Aktualisieren
            $data = array('review_id' => $lastid);

            // Bedingung für die Aktualisierung
            $where = array('ID' => $application_id);

            // Aktualisieren der Daten in der Datenbank
            $wpdb->update($table_name, $data, $where);

            $log = 'Prüfung begonnen';

            create_backlog_entry($application_id, $log);
            return $lastid;
        }else{
            return null;
        }
    }
}

function send_status_mail($application_id){
    $user = wp_get_current_user();
    $subscribe_notifications = get_user_meta($user->ID, 'subscribe_notifications', true);
    if($subscribe_notifications){
        $application = get_application_by_id($application_id);

        if ($application) {
            $is_mail = True;
            
            $job = get_job_by_id($application->job_id);
            if ($application->review_id) {
                $application->review = get_review_by_application($application);
            }
            
            $state = '';
            if($application->state == 'failed'){
                $state = 'Prüfung nicht bestanden: ';
            }else if($application->state == 'passed'){
                $state = 'Prüfung bestanden: ';
            }
            $to = $user->user_email; // E-Mail-Adresse des Empfängers
            $subject = $state. $application->prename . ' ' . $application->surname;
    
            // CSS-Datei einlesen und inline einbetten
            $css_content = file_get_contents(plugin_dir_path(__FILE__) . 'public/css/custom.css');
            $style = '<style>' . $css_content . '</style>';
    
            // Template einlesen
            ob_start();
            include plugin_dir_path(__FILE__) . 'includes/templates/application-detail-template.php';
            $template = ob_get_clean();
    
            // CSS und Template in die E-Mail einbetten
            $message = $style . $template;
    
            $headers = array('Content-Type: text/html; charset=UTF-8');
    
            // E-Mail senden
            wp_mail($to, $subject, $message, $headers);
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

function get_test_by_id_permissionless($test_id){
    global $wpdb;

    // SQL-Abfrage, um die Jobdetails abzurufen
    $query = $wpdb->prepare( "
         SELECT *
         FROM {$wpdb->prefix}te_tests
         WHERE ID = %d
    ", $test_id );

    // Jobdetails abrufen
    $tests = $wpdb->get_results( $query );

    // Überprüfen, ob Jobdetails vorhanden sind
    return ! empty( $tests ) ? $tests[0] : null;
}

function get_job_by_id_permissionless($job_id){
    global $wpdb;

    // SQL-Abfrage, um die Jobdetails abzurufen
    $query = $wpdb->prepare( "
         SELECT *
         FROM {$wpdb->prefix}te_jobs
         WHERE ID = %d
    ", $job_id );

    // Jobdetails abrufen
    $jobs = $wpdb->get_results( $query );

    // Überprüfen, ob Jobdetails vorhanden sind
    return ! empty( $jobs ) ? $jobs[0] : null;
}

function get_review_by_id_permissionless($review_id){
    global $wpdb;

    // SQL-Abfrage, um die Jobdetails abzurufen
    $query = $wpdb->prepare( "
         SELECT *
         FROM {$wpdb->prefix}te_reviews
         WHERE ID = %d
    ", $review_id );

    // Jobdetails abrufen
    $reviews = $wpdb->get_results( $query );

    // Überprüfen, ob Jobdetails vorhanden sind
    return ! empty( $reviews ) ? $reviews[0] : null;
}

function get_review_by_id($review_id){
    if(current_user_can('dienstleister')){
         return get_review_by_id_permissionless($review_id);
    }else{
         return null;
    }

}

function get_application_by_id_permissionless($application_id){
    // Datenbankverbindung öffnen
    global $wpdb;

    // SQL-Abfrage, um die Bewerbungsdetails abzurufen
    $query = $wpdb->prepare( "
        SELECT *
        FROM {$wpdb->prefix}te_applications
        WHERE ID = %d
    ", $application_id );

    // Bewerbungsdetails abrufen
    $applications = $wpdb->get_results( $query );

    // Überprüfen, ob Bewerbungsdetails vorhanden sind
    return ! empty( $applications ) ? $applications[0] : null;
}

function get_applications_dir(){
    $uploadDir = wp_upload_dir()['basedir'] . '/applications/';
    // Überprüfen, ob das Verzeichnis existiert, andernfalls erstellen
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true); // Verzeichnis erstellen mit Lesen/Schreiben-Rechten für Besitzer und Leserechten für andere
    }
    return $uploadDir;
}

function get_consent_dir(){
    $uploadDir = wp_upload_dir()['basedir'] . '/consent/';
    // Überprüfen, ob das Verzeichnis existiert, andernfalls erstellen
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true); // Verzeichnis erstellen mit Lesen/Schreiben-Rechten für Besitzer und Leserechten für andere
    }
    return $uploadDir;
}

function get_user_home_url( $user ) {
    if ( isset( $user->roles ) && is_array( $user->roles ) ) {
        if ( in_array( 'firmenkunde', $user->roles ) ) {
            return home_url( '/kandidaten' );
        } elseif ( in_array( 'dienstleister', $user->roles ) ) {
            return home_url( '/talents' );
        } elseif ( in_array( 'subscriber', $user->roles ) ) {
            return home_url( '/membership-login' );
        }
    }
    return home_url();
}

function render_logout_button() {
    if ( SwpmMemberUtils::is_member_logged_in()) {
        ?>
        <div class="swpm-logged-logout-link">
            <a href="?swpm-logout=true"><?php echo SwpmUtils::_("Logout"); ?></a>
        </div>
        <?php
    }else if(is_user_logged_in()){
        ?>
        <div class="swpm-logged-logout-link">
        <a href="<?php echo home_url('/wp-login.php?action=logout'); ?>">Logout</a>
        </div>
        <?php
    }
}

function render_link_template($test_page_url) {
    ob_start();
    ?>
    <div class="input-group mb-3">
        <input type="text" id="test-link" class="form-control" value="<?php echo esc_url($test_page_url); ?>" readonly>
        <button class="btn btn-outline-secondary" type="button" id="copy-button">
            <i class="bi bi-clipboard"></i> Kopieren
        </button>
    </div>
    <?php
    return ob_get_clean();
}

function getPostalCodesInRadius($postalCode, $radius=10, $countryCode='DE') {
    // URL für die API-Zip-Anfrage erstellen
    $apiUrl = "https://zip-api.eu/api/v1/radius/{$countryCode}-{$postalCode}/{$radius}/km";

    // HTTP-Anfrage senden
    $response = file_get_contents($apiUrl);

    // Überprüfen, ob die Anfrage erfolgreich war
    if ($response === false) {
        return false; // Fehler beim Abrufen der Daten
    }

    // Daten decodieren
    $data = json_decode($response, true);

    // Überprüfen, ob Daten vorhanden sind
    if (!empty($data) && is_array($data)) {
        // Überprüfen, ob es sich um ein mehrdimensionales Array handelt
        if (isset($data[0])) {
            // Mehrere Einträge: Nur die Postleitzahlen zurückgeben, ohne Entfernungs- und Einheitsinformationen
            return array_column($data, 'postal_code');
        } else {
            // Einzelner Eintrag: Direkt die Postleitzahl zurückgeben
            return array($data['postal_code']);
        }
    } else {
        return false; // Keine Daten oder falsches Format
    }
}

function get_assistant_id() {
    if(!$_SESSION['game']){
        return false;
    }
    $game = $_SESSION['game'];
    if($game == 'burger'){
        return 'asst_3MWRUUDVcZR8zRe5DjmYAqJD';
    }else if($game == 'glasses'){
        return 'asst_n5KxIqgqswb4ZV7HSvaIgZsg';
    }
    return false;
}

run_talent_evaluation();
