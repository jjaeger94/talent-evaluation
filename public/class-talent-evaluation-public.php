<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Talent_Evaluation
 * @subpackage Talent_Evaluation/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Talent_Evaluation
 * @subpackage Talent_Evaluation/public
 * @author     Jan Jäger <janjaeger2020@gmail.com>
 */
class Talent_Evaluation_Public {

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
	 * @param      string    $talent_evaluation       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $talent_evaluation, $version ) {

		$this->talent_evaluation = $talent_evaluation;
		$this->version = $version;
		$this->register_public_requests();

	}

	private function register_public_requests(){
		add_action('wp_ajax_add_job', 'process_job_form');
		add_action('wp_ajax_nopriv_add_job', 'process_job_form');
	}

	function process_job_form() {
		if ( isset( $_POST['job_title'] ) ) {
			$job_title = sanitize_text_field( $_POST['job_title'] );
			$user_id = get_current_user_id(); // Nutzer-ID des anlegenden Nutzers
	
			// Erfassen Sie die in den Optionen gespeicherten Daten
			$db_host = get_option('te_db_host');
			$db_name = get_option('te_db_name');
			$db_user = get_option('te_db_user');
			$db_password = get_option('te_db_password');
		
			// Versuchen Sie, eine temporäre Datenbankverbindung herzustellen
			$temp_db = new wpdb($db_user, $db_password, $db_name, $db_host);
	
			$table_name = $temp_db->prefix . 'jobs';
	
			// Neuen Eintrag in die Tabelle "Stellen" einfügen
			$result = $temp_db->insert( 
				$table_name, 
				array( 
					'user_id' => $user_id,
					'job_title' => $job_title
				), 
				array( 
					'%d', 
					'%s' 
				) 
			);
	
			if ( $result === false ) {
				// Fehlermeldung zurückgeben
				echo '<p>Error: Job could not be added to the database.</p>';
			} else {
				// Erfolgsmeldung zurückgeben
				echo '<p>Job added successfully!</p>';
			}
		}
		wp_die();
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->talent_evaluation, plugin_dir_url( __FILE__ ) . 'css/talent-evaluation-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->talent_evaluation, plugin_dir_url( __FILE__ ) . 'js/talent-evaluation-public.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script('bootstrap-js', plugin_dir_url(__FILE__) . 'js/bootstrap.min.js', array('jquery'), '5.3.3', true);		

	}

}
