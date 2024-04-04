<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Talent_Evaluation
 * @subpackage Talent_Evaluation/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Talent_Evaluation
 * @subpackage Talent_Evaluation/admin
 * @author     Your Name <email@example.com>
 */
class Talent_Evaluation_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $talent_evaluation    The ID of this plugin.
	 */
	private $talent_evaluation;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $talent_evaluation       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $talent_evaluation, $version ) {

		$this->talent_evaluation = $talent_evaluation;
		$this->version = $version;
		$this->register_admin_settings();

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Talent_Evaluation_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Talent_Evaluation_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->talent_evaluation, plugin_dir_url( __FILE__ ) . 'css/talent-evaluation-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Talent_Evaluation_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Talent_Evaluation_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->talent_evaluation, plugin_dir_url( __FILE__ ) . 'js/talent-evaluation-admin.js', array( 'jquery' ), $this->version, false );

	}

	private function register_admin_settings() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
		add_action('wp_ajax_check_db_connection', array($this, 'check_db_connection'));		
    }

    public function add_admin_menu() {
        add_options_page('Talent Evaluation Settings', 'Talent Evaluation', 'manage_options', 'talent-evaluation-settings', array($this, 'admin_page'));
    }

    public function admin_page() {
        ?>
        <div class="wrap">
            <h2>Talent Evaluation Settings</h2>
            <form method="post" action="options.php">
                <?php settings_fields('talent_evaluation_settings'); ?>
                <?php do_settings_sections('talent_evaluation_settings'); ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Database Host</th>
                        <td><input type="text" name="te_db_host" value="<?php echo esc_attr(get_option('te_db_host')); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Database Name</th>
                        <td><input type="text" name="te_db_name" value="<?php echo esc_attr(get_option('te_db_name')); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Database User</th>
                        <td><input type="text" name="te_db_user" value="<?php echo esc_attr(get_option('te_db_user')); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Database Password</th>
                        <td><input type="password" name="te_db_password" value="<?php echo esc_attr(get_option('te_db_password')); ?>" /></td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
			<button id="check-db-connection" class="button">Check Database Connection</button>
            <span id="db-connection-result"></span>
        </div>
        <?php
    }

    public function register_settings() {
        register_setting('talent_evaluation_settings', 'te_db_host');
        register_setting('talent_evaluation_settings', 'te_db_name');
        register_setting('talent_evaluation_settings', 'te_db_user');
        register_setting('talent_evaluation_settings', 'te_db_password');
    }

	public function check_db_connection() {
		// Erfassen Sie die in den Optionen gespeicherten Daten
		$db_host = get_option('te_db_host');
		$db_name = get_option('te_db_name');
		$db_user = get_option('te_db_user');
		$db_password = get_option('te_db_password');
	
		// Versuchen Sie, eine temporäre Datenbankverbindung herzustellen
		$temp_db = new wpdb($db_user, $db_password, $db_name, $db_host);
	
		// Überprüfen Sie, ob die Verbindung erfolgreich hergestellt wurde
		if ($temp_db->ready) {
			echo 'Connection successful';
		} else {
			echo 'Connection failed';
		}
	
		// Beenden Sie die Ausführung des Skripts
		wp_die();
	}

}
