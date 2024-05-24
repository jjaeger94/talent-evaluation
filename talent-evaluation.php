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
define( 'TE_DIR', dirname(__FILE__).'/includes/' );

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
            return 'in einem Monat';
        case 2:
            return 'in 2 Monaten';
        case 3:
            return 'in 3 Monaten';
        case 4:
            return 'in 4 Monaten';
        case 5:
            return 'in 5 Monaten';
        case 6:
            return 'in 6 Monaten';
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

function info_button($text) {
    
    // HTML für den Info-Button mit Popover zurückgeben
    return '<div class="info-button" data-toggle="popover" data-content="'.get_text_by_key($text).'">
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

function get_user_home_url( $user ) {
    if ( isset( $user->roles ) && is_array( $user->roles ) ) {
        if ( in_array( 'firmenkunde', $user->roles ) ) {
            return home_url( '/kandidaten' );
        } elseif ( in_array( 'dienstleister', $user->roles ) ) {
            return home_url( '/talents' );
        } elseif ( in_array( 'subscriber', $user->roles ) ) {
            return home_url( '/membership-login' );
        } elseif ( in_array( 'administrator', $user->roles ) ) {
            return home_url( '/wp-admin' );
        }
    }
    return home_url();
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

function get_study_degree($degree) {
    switch ($degree) {
        case 1:
            return 'Kein Abschluss';
        case 2:
            return 'Bachelor';
        case 3:
            return 'Master';
        case 4:
            return 'Doktor';
        case 5:
            return 'Sonstige';
        // Weitere Fälle hinzufügen, falls erforderlich
        default:
            return '';
    }
}

run_talent_evaluation();
