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
run_talent_evaluation();