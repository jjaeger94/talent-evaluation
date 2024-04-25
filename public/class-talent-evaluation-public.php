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
		add_action('wp_ajax_get_backlog',  array($this, 'handle_get_backlog'));
		add_action('wp_ajax_nopriv_get_backlog',  array($this, 'handle_get_backlog'));
		add_action('wp_ajax_save_user_data',  array($this, 'save_user_data'));
		add_action('wp_ajax_nopriv_save_user_data',  array($this, 'save_user_data'));
		add_action('wp_ajax_save_consent',  array($this, 'save_consent'));
		add_action('wp_ajax_nopriv_save_consent',  array($this, 'save_consent'));
		add_action('wp_ajax_add_test', array($this, 'process_add_test_form'));
		add_action('wp_ajax_nopriv_add_test', array($this, 'process_add_test_form'));
		add_action('wp_ajax_edit_question', array($this, 'process_edit_question'));
		add_action('wp_ajax_nopriv_edit_question', array($this, 'process_edit_question'));
		add_action('wp_ajax_delete_question', array($this, 'process_delete_question'));
		add_action('wp_ajax_nopriv_delete_question', array($this, 'process_delete_question'));
		add_action('wp_ajax_process_test_answers', array($this, 'process_test_answers'));
		add_action('wp_ajax_nopriv_process_test_answers', array($this, 'process_test_answers'));
	}

	function process_test_answers() {
		// Überprüfen, ob die erforderlichen Daten vorhanden sind
		if (isset($_POST['tid'], $_POST['jid'], $_POST['answers'], $_POST['key'])) {
			$tid = intval($_POST['tid']);
			$jid = intval($_POST['jid']);
			$answers = $_POST['answers'];
			$key = sanitize_text_field($_POST['key']);
			
			// Überprüfen des Hash
			if (commitment_hash($jid.$tid) === $key) {
				// Überprüfen, ob eine application_id vorhanden ist
				$aid = null;
				if (isset($_POST['aid'])) {
					$aid = intval($_POST['aid']);
					// Lade die Anwendung
					$application = get_application_by_id_permissionless($aid);
					if(!$application){
						wp_send_json_error('Bewerbung nicht gefunden');
					}
				} else {
					$job = get_job_by_id_permissionless($jid);
					if(!$job){
						wp_send_json_error('Stelle nicht gefunden');
					}
					if ( !isset( $_POST['prename'], $_POST['surname'] )) {
						wp_send_json_error('Name fehlt');
					}
					$prename = sanitize_text_field($_POST['prename']);
					$surname = sanitize_text_field($_POST['surname']);
					global $wpdb;
			
					$table_name = $wpdb->prefix . 'te_applications';
			
					// Neuen Eintrag in die Tabelle "applications" einfügen
					$result = $wpdb->insert(
						$table_name,
						array(
							'job_id' => $jid, // Beispielwert, ändern Sie dies entsprechend Ihrer Anforderungen
							'user_id' => $job->user_id,
							'prename' => $prename,
							'surname' => $surname
							// Fügen Sie hier weitere Felder hinzu und passen Sie die Werte an
						),
						array(
							'%d',
							'%d',
							'%s',
							'%s'
							// Fügen Sie hier weitere Formatierungen hinzu, falls erforderlich
						)
					);
			
					if ( $result === false ) {
						wp_send_json_error('Fehler: Die Bewerbung konnte nicht hinzugefügt werden.');
					}
					$aid = $wpdb->insert_id;
				}
				if(!$aid){
					wp_send_json_error('Fehler: AID nicht gefunden.');
				}
				
				// Speichern der Antworten in der Datenbank
				foreach ($answers as $question_id => $answer) {
					$result = save_answer($aid, $question_id, $answer);
					if(!$result){
						wp_send_json_error('Antworten konnten nicht hinzugefügt werden');
					}
				}
				
				// Erfolgsmeldung zurückgeben
				wp_send_json_success('Die Testantworten wurden erfolgreich gespeichert.');
			} else {
				// Fehlermeldung zurückgeben, wenn der Hash nicht übereinstimmt
				wp_send_json_error('Ungültiger Zugriff.');
			}
		} else {
			// Fehlermeldung zurückgeben, wenn erforderliche Daten fehlen
			wp_send_json_error('Ungültige Anfrage.');
		}
		// Beenden des Skripts
		wp_die();
	}
	

	function process_delete_question() {
		if(has_ajax_permission()){
			if (isset($_POST['question_id'])) {
				global $wpdb;
		
				// Entferne potenziell gefährliche Zeichen aus der Eingabe
				$question_id = absint($_POST['question_id']);
		
				// Überprüfe, ob die Frage existiert
				$question = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}te_questions WHERE ID = %d", $question_id));
		
				if ($question) {
					// Lösche die Frage aus der Datenbank
					$wpdb->delete(
						$wpdb->prefix . 'te_questions',
						array('ID' => $question_id),
						array('%d')
					);
		
					echo 'Frage erfolgreich gelöscht!';
				} else {
					echo 'Frage nicht gefunden!';
				}
			} else {
				echo 'Frage-ID nicht übermittelt!';
			}
		}
		wp_die();
	}

	function process_edit_question(){
		if(has_ajax_permission()){
		if (isset($_POST['test_id'], $_POST['question_text'], $_POST['answer_text'])) {
			global $wpdb;
	
			// Entferne potenziell gefährliche Zeichen aus den Eingaben
			$test_id = absint($_POST['test_id']);
			$question_text = sanitize_text_field($_POST['question_text']);
			$answer_text = sanitize_text_field($_POST['answer_text']);
	
			if (isset($_POST['question_id'])) {
				$question_id = absint($_POST['question_id']);
	
				// Aktualisiere die Frage in der Datenbank
				$table_name = $wpdb->prefix . 'te_questions';
				$wpdb->update(
					$table_name,
					array(
						'question_text' => $question_text,
						'answer_text' => $answer_text
					),
					array('ID' => $question_id),
					array('%s', '%s'),
					array('%d')
				);
	
				// Gib eine Erfolgsmeldung zurück
				echo 'Frage erfolgreich aktualisiert!';
			} else {
				// Füge die Frage in die Datenbank ein
				$table_name = $wpdb->prefix . 'te_questions';
				$wpdb->insert(
					$table_name,
					array(
						'test_id' => $test_id,
						'question_text' => $question_text,
						'answer_text' => $answer_text
					),
					array('%d', '%s', '%s')
				);
	
				// Gib eine Erfolgsmeldung zurück
				echo 'Frage erfolgreich erstellt!';
			}
	
		} else {
			// Gib eine Fehlermeldung zurück, wenn nicht alle erforderlichen Felder übermittelt wurden
			echo 'Alle Felder sind erforderlich!';
		}
	}
		wp_die();
	}	

	function process_add_test_form(){
		if(has_ajax_permission()){
		// Überprüfe, ob die erforderlichen Felder gesetzt sind
		if (isset($_POST['test_title'], $_POST['affiliate_link'], $_POST['image_link'])) {
			global $wpdb;

			// Entferne potenziell gefährliche Zeichen aus den Eingaben
			$test_title = sanitize_text_field($_POST['test_title']);
			$affiliate_link = esc_url_raw($_POST['affiliate_link']);
			$image_link = esc_url_raw($_POST['image_link']);

			// Füge den Test in die Datenbank ein
			$table_name = $wpdb->prefix . 'te_tests';
			$wpdb->insert(
				$table_name,
				array(
					'title' => $test_title,
					'affiliate_link' => $affiliate_link,
					'image_link' => $image_link
				)
			);

			// Gib eine Erfolgsmeldung zurück
			echo 'Test erfolgreich erstellt!';
		} else {
			// Gib eine Fehlermeldung zurück, wenn nicht alle erforderlichen Felder übermittelt wurden
			echo 'Alle Felder sind erforderlich!';
		}
	}
		wp_die();
	}

	function save_consent(){
		if(isset($_POST['application_id']) && isset($_POST['key']) && isset($_FILES['file'])){
			$application_id = absint( $_POST['application_id'] );
			$uniqueDir = sanitize_text_field($_POST['key']);
			$application = get_application_by_id_permissionless($application_id);
			if($application){
				$review = get_review_by_id_permissionless($application->review_id);
				if ($review && ($review->filepath == $uniqueDir)){
					$uploadDir = get_consent_dir();
					$file_path = $uploadDir . $review->filepath .'/';
					$filename = 'consent_' . date('Y-m-d_H-i-s') . '.pdf';
					if(!is_dir($file_path) || !is_writable($file_path)){
						mkdir($file_path, 0755, true);
					}
					$result = move_uploaded_file($_FILES['file']['tmp_name'], $file_path . $filename);
					if($result){
						$consent = 0;
						if( isset($_POST['linkedIn_check']) ){
							$consent = ($consent | 1); // LinkedIn-Check ist aktiv, setze das entsprechende Bit
						}
						if( isset($_POST['old_work_reference_check']) ){
							$consent = ($consent | 2); // alter arbeitgeber ist aktiv, setze das entsprechende Bit
						}
						if( isset($_POST['new_work_reference_check']) ){
							$consent = ($consent | 4); // aktueller Arbeitgeber ist aktiv, setze das entsprechende Bit
						}

									// Versuchen, eine temporäre Datenbankverbindung herzustellen
						global $wpdb;

						$table_name = $wpdb->prefix . 'te_reviews';

						$data = array('consent' => $consent);

						// Bedingung für die Aktualisierung
						$where = array('ID' => $review->ID);
					
						// Aktualisieren der Daten in der Datenbank
						$wpdb->update($table_name, $data, $where);
						
						$log = 'Bewerber hat Einverständnis hochgeladen';
					
						create_backlog_entry($application_id, $log);
						wp_send_json_success('Datei erfolgreich hochgeladen');
					}else{
						wp_send_json_error('Fehler beim upload');
					}
				}else{
					wp_send_json_error('Keine Berechtigung');
				}
			}else{
				wp_send_json_error('Keine Berechtigung');
			}
			
		}else{
			wp_send_json_error('Fehlende oder ungültige Daten.');
		}
		wp_die();
	}

	// AJAX-Funktion zum Speichern der Benutzerdaten
	function save_user_data() {
		if(has_ajax_permission()){
			// Überprüfen, ob der Benutzer angemeldet ist und die erforderlichen Felder gesendet wurden
			if (is_user_logged_in() && isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['company']) && isset($_POST['email'])) {
				$current_user = wp_get_current_user();
				$user_id = $current_user->ID;
				
				// Daten validieren und aktualisieren
				$first_name = sanitize_text_field($_POST['first_name']);
				$last_name = sanitize_text_field($_POST['last_name']);
				$company = sanitize_text_field($_POST['company']);
				$email = sanitize_email($_POST['email']);

				// Überprüfen, ob die E-Mail-Adresse bereits einem anderen Benutzer zugewiesen ist
				if (email_exists($email) && email_exists($email) != $user_id) {
					wp_send_json_error('Die angegebene E-Mail-Adresse wird bereits verwendet.');
				}

				// Überprüfen, ob die Checkbox "Mail-Benachrichtigungen erhalten" aktiviert ist
				$subscribe_notifications = isset($_POST['subscribe_notifications']) ? '1' : '0';

				// Benutzerdaten aktualisieren
				$userdata = array(
					'ID' => $user_id,
					'user_email' => $email,
					'first_name' => $first_name,
					'last_name' => $last_name
				);
				$updated = wp_update_user($userdata);

				// Benutzermetadaten aktualisieren
				if (!is_wp_error($updated)) {
					update_user_meta($user_id, 'company', $company);
					update_user_meta($user_id, 'subscribe_notifications', $subscribe_notifications); // Hinzufügen der Checkbox-Daten
					wp_send_json_success('Benutzerdaten erfolgreich aktualisiert.');
				} else {
					wp_send_json_error('Fehler beim Aktualisieren der Benutzerdaten.');
				}
			} else {
				wp_send_json_error('Fehlende oder ungültige Daten.');
			}
		}
		wp_die();
	}	

	function process_application_form() {
		if(has_ajax_permission()){
		if ( isset( $_POST['prename'] ) && isset( $_POST['surname'] ) && isset( $_POST['email'] )) {
			$applicationDir = '';
			$uniqueDir = '';
			// Überprüfen, ob Dateien hochgeladen wurden
			if (isset($_FILES['resumes']) && !empty($_FILES['resumes']['name'][0])) {
				$uploadDir = get_applications_dir();
				$uniqueDir = 'application_' . uniqid();
				$applicationDir = $uploadDir . $uniqueDir; // Eindeutiger Ordnername für jede Bewerbung
				mkdir($applicationDir, 0755, true); // Ordner für Bewerbung erstellen

				// Durchlaufen Sie alle hochgeladenen Dateien
				foreach ($_FILES['resumes']['tmp_name'] as $key => $tmpName) {
					$uploadFile = $applicationDir . '/' . basename($_FILES['resumes']['name'][$key]); // Pfad zur hochgeladenen Datei im Bewerbungsordner
					move_uploaded_file($tmpName, $uploadFile);
				}
			}
			
			// Formulardaten bereinigen
			$prename = sanitize_text_field( $_POST['prename'] );
			$surname = sanitize_text_field( $_POST['surname'] );
			$email = sanitize_email( $_POST['email'] );
			$job_id = absint( $_POST['job_id'] );
			$salutation = absint( $_POST['salutation'] );
	
			$user_id = get_current_user_id();
	
			// Versuchen, eine temporäre Datenbankverbindung herzustellen
			global $wpdb;
	
			$table_name = $wpdb->prefix . 'te_applications';
	
			// Neuen Eintrag in die Tabelle "applications" einfügen
			$result = $wpdb->insert(
				$table_name,
				array(
					'job_id' => $job_id, // Beispielwert, ändern Sie dies entsprechend Ihrer Anforderungen
					'user_id' => $user_id,
					'prename' => $prename,
					'surname' => $surname,
					'email' => $email,
					'salutation' => $salutation,
					'filepath' => $uniqueDir,
					// Fügen Sie hier weitere Felder hinzu und passen Sie die Werte an
				),
				array(
					'%d',
					'%d',
					'%s',
					'%s',
					'%s',
					'%d',
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

		$table_name = $wpdb->prefix . 'te_applications';

		global $wpdb;

		$data = array('classification' => $value);
			
		// Bedingung für die Aktualisierung
		$where = array('ID' => $application_id, 'user_id' => $user_id);
	
		// Aktualisieren der Daten in der Datenbank
		$wpdb->update($table_name, $data, $where);
		
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

			global $wpdb;
			// Tabellenname für Bewerbungen
			$table_name = $wpdb->prefix . 'te_reviews';
		
			if($type == 'criteria'){
				// Daten zum Aktualisieren
				$data = array('criteria' => $value);
			
				// Bedingung für die Aktualisierung
				$where = array('ID' => $application->review_id);
			
				// Aktualisieren der Daten in der Datenbank
				$wpdb->update($table_name, $data, $where);
				
				$log = 'Kriterien '.$text;
			
				create_backlog_entry($application_id, $log, $comment);
			}else if($type == 'completeness'){
				// Daten zum Aktualisieren
				$data = array('completeness' => $value);

				// Bedingung für die Aktualisierung
				$where = array('ID' => $application->review_id);
			
				// Aktualisieren der Daten in der Datenbank
				$wpdb->update($table_name, $data, $where);
				
				$log = 'Vollständigkeit '.$text;
			
				create_backlog_entry($application_id, $log, $comment);
			}else if($type == 'screening'){
				// Daten zum Aktualisieren
				$data = array('screening' => $value);

				// Bedingung für die Aktualisierung
				$where = array('ID' => $application->review_id);
			
				// Aktualisieren der Daten in der Datenbank
				$wpdb->update($table_name, $data, $where);
				
				$log = 'Background Screening '.$text;
			
				create_backlog_entry($application_id, $log, $comment);
			}else if($type == 'commitment'){
				// Daten zum Aktualisieren
				$data = array('commitment' => $value);

				// Bedingung für die Aktualisierung
				$where = array('ID' => $application->review_id);
			
				// Aktualisieren der Daten in der Datenbank
				$wpdb->update($table_name, $data, $where);
				$log = '';
				if($value > 0){
					$log = 'Commitment Test Ergebnis: '.$text. '/10';
				}else{
					$log = 'Commitment '.$text;
				}
				
			
				create_backlog_entry($application_id, $log, $comment);
			}else if($type == 'consent'){
				$data = array('consent' => $value);

				// Bedingung für die Aktualisierung
				$where = array('ID' => $application->review_id);
			
				// Aktualisieren der Daten in der Datenbank
				$wpdb->update($table_name, $data, $where);

				$to = $application->email; // E-Mail-Adresse des Empfängers
				$subject = 'Einverständniserklärung';
		
				$review = get_review_by_id($application->review_id);
				$job = get_job_by_id($application->job_id);

				$wp_user = get_user_by( 'id', $job->user_id );

                $swpm_user = SwpmMemberUtils::get_user_by_email($wp_user->user_email);
                $company = SwpmMemberUtils::get_member_field_by_id($swpm_user->member_id, 'company_name');
				// Template einlesen
				ob_start();
				include 'partials/consent-mail.php';
				$message = ob_get_clean();
		
				$headers = array('Content-Type: text/html; charset=UTF-8');
		
				// E-Mail senden
				wp_mail($to, $subject, $message, $headers);
				
				$log = 'Einverständnis Email verschickt';
			
				create_backlog_entry($application_id, $log);
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

		$review_id = add_review_to_application($application_id);
		
		if($review_id){
			update_application_state($application_id, $state );

			wp_send_json_success('Status erfolgreich geändert.');
		}else{
			wp_send_json_error('Ein Fehler ist aufgetreten');
		}
		wp_die();
	}

	function handle_get_backlog(){
		// Überprüfen Sie die Benutzerberechtigungen, bevor Sie fortfahren
		if (!has_ajax_permission()) {
			wp_send_json_error('Sie haben keine Berechtigung diese Funktion aufzurufen');
		}
	
		// Überprüfen Sie, ob die Anwendungs-ID gesendet wurde
		if (!isset($_POST['application_id'])) {
			wp_send_json_error('Die Anwendungs-ID fehlt.');
		}
	
		$application_id = $_POST['application_id'];
	
		$application = get_application_by_id($application_id);
		if($application){
			ob_start(); // Starten des Output-Puffers
			include 'partials/backlog-template.php'; // Einbinden der Template-Datei
			$backlog_content = ob_get_clean(); // Abrufen des Inhalts des Output-Puffers und Löschen des Puffers
			echo $backlog_content; // Senden des Inhalts als JSON-Antwort
		}else{
			wp_send_json_error('Sie haben keine Berechtigung die Application zu sehen');
		}
		wp_die();
	}

	function handle_change_state() {
		// Überprüfen Sie die Benutzerberechtigungen, bevor Sie fortfahren
		// Überprüfen Sie, ob Dateien gesendet wurden
		if (!isset($_POST['state'])) {
			wp_send_json_error('Es wurden kein State übergeben.');
		}

		if (current_user_can('dienstleister')) {
			// Überprüfen Sie, ob die Anwendungs-ID gesendet wurde
			if (!isset($_POST['application_id'])) {
				wp_send_json_error('Die Anwendungs-ID fehlt.');
			}
		
			$application_id = $_POST['application_id'];

			$state = $_POST['state'];

			$comment = isset($_POST['comment']) ? $_POST['comment'] : '';
			
			update_application_state($application_id, $state, $comment);

			if($state == 'failed' || $state == 'passed'){
				send_status_mail($application_id);
			}
		}else if(current_user_can('firmenkunde')){
			// Überprüfen Sie, ob die Anwendungs-ID gesendet wurde
			if (!isset($_POST['job_id'])) {
				wp_send_json_error('Die Job-ID fehlt.');
			}
		
			$job_id = $_POST['job_id'];

			$state = $_POST['state'];
			
			update_job_state($job_id, $state);
		}else{
			wp_send_json_error('Sie haben keine Berechtigung');
		}

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

		$uploadDir = get_applications_dir(); // Standard-WordPress-Upload-Verzeichnis
        $file_path = $application->filepath;
        if($file_path){
            $file_path = $uploadDir . $file_path . '/';
        }

	
		// Überprüfen, ob bereits Dateien vorhanden sind
		if ($file_path && !empty($file_path)) {
			// Dateien vorhanden
			// Prüfen, ob es sich um ein Verzeichnis handelt und ob es beschreibbar ist
			if (is_dir($file_path) && is_writable($file_path)) {
				// Dateien werden im vorhandenen Verzeichnis gespeichert
				$upload_path = $file_path;
			} else {
				// Fehler: Das Verzeichnis existiert nicht oder ist nicht beschreibbar
				echo 'Fehler: Das Verzeichnis für Dateien ist nicht verfügbar oder nicht beschreibbar.';
				exit;
			}
		} else {
			// Keine Dateien vorhanden
			$uniqueDir = 'application_' . uniqid();
			$applicationDir = $uploadDir . $uniqueDir;
			mkdir($applicationDir, 0755, true);

			// Upload-Pfad setzen
			$upload_path = $applicationDir . '/';
			update_application_filepath($application_id, $uniqueDir);
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
			global $wpdb;
	
			$table_name = $wpdb->prefix . 'te_jobs';
	
			// Neuen Eintrag in die Tabelle "Stellen" einfügen
			$result = $wpdb->insert( 
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
				if ($wpdb->last_error && strpos($wpdb->last_error, 'Duplicate entry') !== false) {
					// Fehlermeldung für Duplikateintrag ausgeben
					echo '<p>Fehler: Eine Stelle mit diesem Namen existiert schon.</p>';
				} elseif ($wpdb->last_error) {
					// Fehlermeldung ausgeben
					echo '<p>Error: ' . $wpdb->last_error . '</p>';
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
		wp_enqueue_script('bootstrap-js', plugin_dir_url(__FILE__) . 'js/bootstrap.bundle.min.js', array('jquery'), '5.3.3', true);	
		wp_enqueue_script('fontawesome', plugin_dir_url(__FILE__) . 'js/all.min.js', array('jquery'), '6.5.2', true);	

	}

}