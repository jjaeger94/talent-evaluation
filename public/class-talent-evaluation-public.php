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
		add_action('wp_ajax_add_application', array($this, 'process_application_form'));
		add_action('wp_ajax_nopriv_add_application', array($this, 'process_application_form'));
		add_action('wp_ajax_add_files',  array($this, 'handle_file_upload'));
		add_action('wp_ajax_nopriv_add_files',  array($this, 'handle_file_upload'));
		add_action('wp_ajax_change_state',  array($this, 'handle_change_state'));
		add_action('wp_ajax_nopriv_change_state',  array($this, 'handle_change_state'));
		add_action('wp_ajax_start_review',  array($this, 'handle_start_review'));
		add_action('wp_ajax_nopriv_start_review',  array($this, 'handle_start_review'));
		add_action('wp_ajax_set_review',  array($this, 'handle_set_review'));
		add_action('wp_ajax_nopriv_set_review',  array($this, 'handle_set_review'));
		add_action('wp_ajax_set_classification',  array($this, 'handle_set_classification'));
		add_action('wp_ajax_nopriv_set_classification',  array($this, 'handle_set_classification'));
	}
	
	function process_application_form() {
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

	function handle_set_classification(){
		// Überprüfen Sie die Benutzerberechtigungen, bevor Sie fortfahren
		if (!current_user_can('firmenkunde')) {
			wp_send_json_error('Sie haben keine Berechtigung, Dateien hochzuladen.');
		}
	
		// Überprüfen Sie, ob Dateien gesendet wurden
		if (!isset($_POST['value'])) {
			wp_send_json_error('Es wurden kein Value übergeben.');
		}

		// Überprüfen Sie, ob Dateien gesendet wurden
		if (!isset($_POST['text'])) {
			wp_send_json_error('Es wurden kein Text übergeben.');
		}

		// Überprüfen Sie, ob die Anwendungs-ID gesendet wurde
		if (!isset($_POST['application_id'])) {
			wp_send_json_error('Die Anwendungs-ID fehlt.');
		}

		$user_id = get_current_user_id();

		$application_id = $_POST['application_id'];
		
		$value = $_POST['value'];

		$text = $_POST['text'];

		$comment = isset($_POST['comment']) ? $_POST['comment'] : '';

		$table_name = $temp_db->prefix . 'applications';

		$temp_db = open_database_connection();

		$data = array('classification' => $value);
			
		// Bedingung für die Aktualisierung
		$where = array('ID' => $application_id, 'user_id' => $user_id);
	
		// Aktualisieren der Daten in der Datenbank
		$temp_db->update($table_name, $data, $where);
		
		$log = 'Einordnung gesetzt auf: '.$text;
	
		create_backlog_entry($application_id, $log, $comment);
	}

	function handle_set_review(){
			// Überprüfen Sie die Benutzerberechtigungen, bevor Sie fortfahren
			if (!current_user_can('dienstleister')) {
				wp_send_json_error('Sie haben keine Berechtigung, Dateien hochzuladen.');
			}
		
			// Überprüfen Sie, ob Dateien gesendet wurden
			if (!isset($_POST['value'])) {
				wp_send_json_error('Es wurden kein Value übergeben.');
			}

			// Überprüfen Sie, ob Dateien gesendet wurden
			if (!isset($_POST['text'])) {
				wp_send_json_error('Es wurden kein Text übergeben.');
			}

			// Überprüfen Sie, ob Dateien gesendet wurden
			if (!isset($_POST['type'])) {
				wp_send_json_error('Es wurden kein Type übergeben.');
			}
		
			// Überprüfen Sie, ob die Anwendungs-ID gesendet wurde
			if (!isset($_POST['application_id'])) {
				wp_send_json_error('Die Anwendungs-ID fehlt.');
			}
		
			$application_id = $_POST['application_id'];
	
			$text = $_POST['text'];

			$type = $_POST['type'];

			$value = $_POST['value'];

			$comment = isset($_POST['comment']) ? $_POST['comment'] : '';

			$application = get_application_by_id($application_id);

			if(!$application){
				wp_send_json_error('Keine Berechtigung');
			}

			$temp_db = open_database_connection();
			// Tabellenname für Bewerbungen
			$table_name = $temp_db->prefix . 'reviews';
		
			if($type == 'criteria'){
				// Daten zum Aktualisieren
				$data = array('criteria' => $value);
			
				// Bedingung für die Aktualisierung
				$where = array('ID' => $application->review_id);
			
				// Aktualisieren der Daten in der Datenbank
				$temp_db->update($table_name, $data, $where);
				
				$log = 'Kriterien '.$text;
			
				create_backlog_entry($application_id, $log, $comment);
			}else if($type == 'completeness'){
				// Daten zum Aktualisieren
				$data = array('completeness' => $value);

				// Bedingung für die Aktualisierung
				$where = array('ID' => $application->review_id);
			
				// Aktualisieren der Daten in der Datenbank
				$temp_db->update($table_name, $data, $where);
				
				$log = 'Vollständigkeit '.$text;
			
				create_backlog_entry($application_id, $log, $comment);
			}else if($type == 'screening'){
				// Daten zum Aktualisieren
				$data = array('screening' => $value);

				// Bedingung für die Aktualisierung
				$where = array('ID' => $application->review_id);
			
				// Aktualisieren der Daten in der Datenbank
				$temp_db->update($table_name, $data, $where);
				
				$log = 'Background Screening '.$text;
			
				create_backlog_entry($application_id, $log, $comment);
			}else if($type == 'commitment'){
				// Daten zum Aktualisieren
				$data = array('commitment' => $value);

				// Bedingung für die Aktualisierung
				$where = array('ID' => $application->review_id);
			
				// Aktualisieren der Daten in der Datenbank
				$temp_db->update($table_name, $data, $where);
				$log = '';
				if($value > 0){
					$log = 'Commitment Test Ergebnis '.$text;
				}else{
					$log = 'Commitment '.$text;
				}
				
			
				create_backlog_entry($application_id, $log, $comment);
			}

			wp_send_json_success('Status erfolgreich geändert.');
			wp_die();
	}

	function handle_start_review(){
		// Überprüfen Sie die Benutzerberechtigungen, bevor Sie fortfahren
		if (!current_user_can('dienstleister')) {
			wp_send_json_error('Sie haben keine Berechtigung, Dateien hochzuladen.');
		}
	
		// Überprüfen Sie, ob Dateien gesendet wurden
		if (!isset($_POST['state'])) {
			wp_send_json_error('Es wurden kein State übergeben.');
		}
	
		// Überprüfen Sie, ob die Anwendungs-ID gesendet wurde
		if (!isset($_POST['application_id'])) {
			wp_send_json_error('Die Anwendungs-ID fehlt.');
		}
	
		$application_id = $_POST['application_id'];

		$state = $_POST['state'];

		add_review_to_application($application_id);
		
		update_application_state($application_id, $state );

		wp_send_json_success('Status erfolgreich geändert.');
		wp_die();
	}

	function handle_change_state() {
		// Überprüfen Sie die Benutzerberechtigungen, bevor Sie fortfahren
		if (!current_user_can('dienstleister')) {
			wp_send_json_error('Sie haben keine Berechtigung, Dateien hochzuladen.');
		}
	
		// Überprüfen Sie, ob Dateien gesendet wurden
		if (!isset($_POST['state'])) {
			wp_send_json_error('Es wurden kein State übergeben.');
		}
	
		// Überprüfen Sie, ob die Anwendungs-ID gesendet wurde
		if (!isset($_POST['application_id'])) {
			wp_send_json_error('Die Anwendungs-ID fehlt.');
		}
	
		$application_id = $_POST['application_id'];

		$state = $_POST['state'];

		$comment = isset($_POST['comment']) ? $_POST['comment'] : '';
		
		update_application_state($application_id, $state, $comment);

		wp_send_json_success('Status erfolgreich geändert.');
		wp_die();
	}
	
	function handle_file_upload() {
		// Überprüfen Sie die Benutzerberechtigungen, bevor Sie fortfahren
		if (!current_user_can('dienstleister')) {
			wp_send_json_error('Sie haben keine Berechtigung, Dateien hochzuladen.');
		}
	
		// Überprüfen Sie, ob Dateien gesendet wurden
		if (!isset($_FILES['files'])) {
			wp_send_json_error('Es wurden keine Dateien hochgeladen.');
		}
	
		// Überprüfen Sie, ob die Anwendungs-ID gesendet wurde
		if (!isset($_POST['application_id'])) {
			wp_send_json_error('Die Anwendungs-ID fehlt.');
		}
	
		$application_id = $_POST['application_id'];

		$application = get_application_by_id($application_id);

		if(!$application){
			wp_send_json_error('Keine Berechtigung');
		}

		$file_directory = $application->filepath;
	
		// Überprüfen, ob bereits Dateien vorhanden sind
		if (!empty($file_directory)) {
			// Dateien vorhanden
			// Prüfen, ob es sich um ein Verzeichnis handelt und ob es beschreibbar ist
			if (is_dir($file_directory) && is_writable($file_directory)) {
				// Dateien werden im vorhandenen Verzeichnis gespeichert
				$upload_path = $file_directory;
			} else {
				// Fehler: Das Verzeichnis existiert nicht oder ist nicht beschreibbar
				echo 'Fehler: Das Verzeichnis für Dateien ist nicht verfügbar oder nicht beschreibbar.';
				exit;
			}
		} else {
			// Keine Dateien vorhanden
			// Neuen Ordner erstellen
			$uploadDir = wp_upload_dir()['basedir'] . '/applications/'; // Standard-WordPress-Upload-Verzeichnis
			// Überprüfen, ob das Verzeichnis existiert, andernfalls erstellen
			if (!file_exists($uploadDir)) {
				mkdir($uploadDir, 0755, true); // Verzeichnis erstellen mit Lesen/Schreiben-Rechten für Besitzer und Leserechten für andere
			}
			$applicationDir = $uploadDir . 'application_' . uniqid() . '/'; // Eindeutiger Ordnername für jede Bewerbung
			mkdir($applicationDir, 0755, true);

			// Upload-Pfad setzen
			$upload_path = $applicationDir;
			update_application_filepath($application_id, $applicationDir);
		}
	
		// Dateien verschieben und speichern
		foreach ($_FILES['files']['tmp_name'] as $key => $tmp_name) {
			$file_name = basename($_FILES['files']['name'][$key]);
			$target_file = $upload_path . $file_name;
	
			if (move_uploaded_file($_FILES['files']['tmp_name'][$key], $target_file)) {
				$log = 'Datei '.$file_name.' hinzugefügt';
				create_backlog_entry($application_id, $log);
			} else {
				wp_send_json_error('Fehler beim Hochladen der Datei ' . $file_name);
			}
		}
	
		wp_send_json_success('Dateien erfolgreich hochgeladen.');
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
			$screening1 = isset( $_POST['screening1'] ) ? 1 : 0;
			$screening2 = isset( $_POST['screening2'] ) ? 1 : 0;
			$screening3 = isset( $_POST['screening3'] ) ? 1 : 0;
			
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
					'screening' => $screening1 + ($screening2 << 1) + ($screening3 << 2),
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