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
		add_action('wp_ajax_add_job', array($this, 'process_job_form'));
		add_action('wp_ajax_nopriv_add_job', array($this, 'process_job_form'));
		add_action('wp_ajax_add_candidate', array($this, 'process_candidate_form'));
		add_action('wp_ajax_nopriv_add_candidate', array($this, 'process_candidate_form'));
	}
	
	function process_candidate_form() {
		if ( isset( $_POST['prename'] ) && isset( $_POST['surname'] ) && isset( $_POST['email'] ) && (current_user_can( 'firmenkunde' ) || current_user_can( 'dienstleister' )) ) {
			$applicationDir = '';
			// Überprüfen, ob Dateien hochgeladen wurden
			if (isset($_FILES['resumes']) && !empty($_FILES['resumes']['name'][0])) {
				$uploadDir = wp_upload_dir()['basedir'] . '/applications/';

				// Überprüfen, ob das Verzeichnis existiert, andernfalls erstellen
				if (!file_exists($uploadDir)) {
					mkdir($uploadDir, 0755, true); // Verzeichnis erstellen mit Lesen/Schreiben-Rechten für Besitzer und Leserechten für andere
				}
				$applicationDir = $uploadDir . 'application_' . uniqid() . '/'; // Eindeutiger Ordnername für jede Bewerbung
				mkdir($applicationDir, 0755, true); // Ordner für Bewerbung erstellen

				// Durchlaufen Sie alle hochgeladenen Dateien
				foreach ($_FILES['resumes']['tmp_name'] as $key => $tmpName) {
					$uploadFile = $applicationDir . basename($_FILES['resumes']['name'][$key]); // Pfad zur hochgeladenen Datei im Bewerbungsordner
					move_uploaded_file($tmpName, $uploadFile);
				}
			}
			
			// Formulardaten bereinigen
			$prename = sanitize_text_field( $_POST['prename'] );
			$surname = sanitize_text_field( $_POST['surname'] );
			$email = sanitize_email( $_POST['email'] );
			$job_id = absint( $_POST['job_id'] );
	
			$user_id = get_current_user_id();
	
			// Versuchen, eine temporäre Datenbankverbindung herzustellen
			$temp_db = open_database_connection();
	
			$table_name = $temp_db->prefix . 'applications';
	
			// Neuen Eintrag in die Tabelle "applications" einfügen
			$result = $temp_db->insert(
				$table_name,
				array(
					'job_id' => $job_id, // Beispielwert, ändern Sie dies entsprechend Ihrer Anforderungen
					'user_id' => $user_id,
					'prename' => $prename,
					'surname' => $surname,
					'email' => $email,
					'filepath' => $applicationDir,
					// Fügen Sie hier weitere Felder hinzu und passen Sie die Werte an
				),
				array(
					'%d',
					'%d',
					'%s',
					'%s',
					'%s',
					'%s',
					// Fügen Sie hier weitere Formatierungen hinzu, falls erforderlich
				)
			);
	
			if ( $result === false ) {
				// Fehler beim Einfügen des Datensatzes
				echo '<p>Fehler: Die Bewerbung konnte nicht hinzugefügt werden.</p>';
			} else {
				// Erfolgsmeldung zurückgeben
				echo '<p>Bewerbung erfolgreich hinzugefügt!</p>';
			}
		}
		wp_die();
	}
	

	function process_job_form() {
		if ( isset( $_POST['job_title'] ) && current_user_can( 'firmenkunde' )) {
			$job_title = sanitize_text_field( $_POST['job_title'] );
			$location = sanitize_text_field( $_POST['location'] );
			$criteria1 = sanitize_text_field( $_POST['criteria1'] );
			$criteria2 = sanitize_text_field( $_POST['criteria2'] );
			$criteria3 = sanitize_text_field( $_POST['criteria3'] );
			$completeness1 = isset( $_POST['completeness1'] ) ? 1 : 0;
			$completeness2 = isset( $_POST['completeness2'] ) ? 1 : 0;
			$reference1 = isset( $_POST['reference1'] ) ? 1 : 0;
			$reference2 = isset( $_POST['reference2'] ) ? 1 : 0;
			$reference3 = isset( $_POST['reference3'] ) ? 1 : 0;
			
			$user_id = get_current_user_id(); // Nutzer-ID des anlegenden Nutzers
			
			// Versuchen Sie, eine temporäre Datenbankverbindung herzustellen
			$temp_db = open_database_connection();
	
			$table_name = $temp_db->prefix . 'jobs';
	
			// Neuen Eintrag in die Tabelle "Stellen" einfügen
			$result = $temp_db->insert( 
				$table_name, 
				array( 
					'user_id' => $user_id,
					'job_title' => $job_title,
					'criteria1' => $criteria1,
					'criteria2' => $criteria2,
					'criteria3' => $criteria3,
					'completeness' => $completeness1 + ($completeness2 << 1),
					'reference' => $reference1 + ($reference2 << 1) + ($reference3 << 2),
					'location' => $location,
				), 
				array( 
					'%d', 
					'%s',
					'%s',
					'%s',
					'%s',
					'%d',
					'%d',
					'%s'
				) 
			);
	
			if ( $result === false ) {
				// Fehler beim Einfügen des Datensatzes
				if ($temp_db->last_error && strpos($temp_db->last_error, 'Duplicate entry') !== false) {
					// Fehlermeldung für Duplikateintrag ausgeben
					echo '<p>Fehler: Eine Stelle mit diesem Namen existiert schon.</p>';
				} elseif ($temp_db->last_error) {
					// Fehlermeldung ausgeben
					echo '<p>Error: ' . $temp_db->last_error . '</p>';
				} else {
					// Allgemeine Fehlermeldung ausgeben
					echo '<p>Fehler: Die Stelle konnte nicht hinzugefügt werden.</p>';
				}
			} else {
				// Erfolgsmeldung zurückgeben
				echo '<p>Stelle erfolgreich hinzugefügt!</p>';
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
		// Definiere ajaxurl
		wp_localize_script( $this->talent_evaluation, 'your_script_vars', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' )
		));
		wp_enqueue_script('bootstrap-js', plugin_dir_url(__FILE__) . 'js/bootstrap.min.js', array('jquery'), '5.3.3', true);		

	}

}
