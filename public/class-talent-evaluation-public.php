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
		$this->add_public_request('send_message');
		$this->add_public_request('send_audio');
		$this->add_public_request('delete_chat');
		$this->add_public_request('save_talent');
		$this->add_public_request('remove_talent');
		$this->add_public_request('save_talent_details');
		$this->add_public_request('edit_apprenticeship');
		$this->add_public_request('edit_study');
		$this->add_public_request('edit_experience');
		$this->add_public_request('edit_eq');
		$this->add_public_request('delete_study');
		$this->add_public_request('delete_apprenticeship');
		$this->add_public_request('delete_experience');
		$this->add_public_request('create_user');
		$this->add_public_request('send_activate_account_mail');
		$this->add_public_request('edit_customer');
		$this->add_public_request('edit_job');
		$this->add_public_request('save_requirement');
		$this->add_public_request('delete_requirement');
		$this->add_public_request('save_talent_notes');		
		$this->add_public_request('activate_matching');
		$this->add_public_request('save_matching');
		$this->add_public_request('save_preference');
		$this->add_public_request('send_job_mail');
		$this->add_public_request('generate_resume_pdf');
		$this->add_public_request('activate_all_matchings');
		$this->add_public_request('submit_evaluation');
		$this->add_public_request('request_consultation');
		$this->add_public_request('remove_job');
		$this->add_public_request('remove_customer');
		$this->add_public_request('deactivate_job');
		$this->add_public_request('reactivate_job');
		$this->add_public_request('upload_document');
		$this->add_public_request('download_document');
		$this->add_public_request('save_notifications');
		$this->add_public_request('edit_game');
	}

	private function add_public_request($request_name){
		add_action('wp_ajax_'.$request_name, array($this, $request_name));
		add_action('wp_ajax_nopriv_'.$request_name,  array($this, $request_name));
	}

	function send_audio() {
		session_start();
		if (isset($_POST['audioCodec']) && !empty($_FILES['audio']) && !empty($_SESSION['active_chat'])) {
			$audio_file_path = $_FILES['audio']['tmp_name'];
			$audioCodec = sanitize_text_field($_POST['audioCodec']);
	
			// Überprüfen, ob die Datei hochgeladen wurde
			if (is_uploaded_file($_FILES['audio']['tmp_name'])) {
				error_log($_FILES['audio']['tmp_name']);
				try {
					// Transkription der Audiodatei
					$message = transcribe_audio($audio_file_path, $audioCodec);
				} catch (Exception $e) {
					wp_send_json_error($e->getMessage());
				}
	
				wp_send_json_success($message);
			} else {
				wp_send_json_error('Datei wurde nicht korrekt hochgeladen.');
			}
		} else {
			wp_send_json_error('Ungültige Anfrage.');
		}
		wp_die();
	}

	function save_notifications(){
		$email = isset($_POST['email']) ? sanitize_text_field($_POST['email']) : null;
		if(!$email){
			wp_send_json_error('ungültige Anfrage');
		}
		$talent = get_talent_by_email($email);
		if(!$talent){
			wp_send_json_error('ungültige Anfrage');
		}
		$notifications = 255;

		// Benachrichtigungseinstellungen aus dem Formular aktualisieren
		if (isset($_POST['registration'])) {
			$notifications = add_notification($notifications, NOTIFICATION_REGISTRATION);
		} else {
			$notifications = remove_notification($notifications, NOTIFICATION_REGISTRATION);
		}

		if (isset($_POST['new_jobs'])) {
			$notifications = add_notification($notifications, NOTIFICATION_NEW_JOBS);
		} else {
			$notifications = remove_notification($notifications, NOTIFICATION_NEW_JOBS);
		}

		$result = update_talent_notifications_by_email($email, $notifications);

		if ($result !== false) {
			log_event(6, 'Benachrichtigung anpassen erfolgreich', $talent->ID);
			wp_send_json_success('Einstellungen gespeichert!');
		} else {
			log_event(6, 'Benachrichtigung anpassen fehlgeschlagen', $talent->ID);
			wp_send_json_error('Fehler beim Speichern der Einstellungen.');
		}
	}

	// Ajax-Handler für den Dateidownload
	function download_document() {
		if (!is_user_logged_in()) {
			wp_send_json_error('Sie müssen eingeloggt sein, um diese Datei herunterzuladen.');
		}

		$document_id = intval($_POST['document_id']);
		$document = get_file_by_id($document_id);

		if (!$document) {
			wp_send_json_error('Lebenslauf nicht gefunden.');
		}

		$talent_id = $document->talent_id;

		if (!has_edit_talent_permission($talent_id)) {
			wp_send_json_error('Keine Berechtigung');
		}

		$upload_dir = wp_upload_dir();
		$file_path = $upload_dir['basedir'] . '/protected/' . $talent_id . '/' . $document->file;

		if (!file_exists($file_path)) {
			wp_send_json_error('Datei nicht gefunden.');
		}

		$file_url = add_query_arg(
			[
				'download_file' => $document_id,
			],
			home_url()
		);

		wp_send_json_success(['file_url' => $file_url]);
}

	function upload_document() {
		if(!isset($_POST['talent_id'], $_POST['type'])){
			wp_send_json_error('Ungültige Anfrage 1');
		}
		$talent_id = intval($_POST['talent_id']);
		if (!has_edit_talent_permission($talent_id)) {
			wp_send_json_error('Keine Berechtigung');
		}

		$type = intval($_POST['type']);
		if($type == 1 && isset($_FILES['resume'])){
			$file = $_FILES['resume'];
		}else if($type == 2 && isset($_FILES['document'])){
			$file  = $_FILES['document'];
		}else{
			wp_send_json_error('Ungültige Anfrage 2');
		}

		$allowed_file_types = ['pdf', 'doc', 'docx'];
		$file_ext = pathinfo($file['name'], PATHINFO_EXTENSION);
	
		if (in_array($file_ext, $allowed_file_types)) {
			$upload_dir = wp_upload_dir();
			$protected_dir = $upload_dir['basedir'] . '/protected';
			$talent_dir = $protected_dir. '/'. $talent_id;
	
			// Überprüfen, ob das Verzeichnis existiert, wenn nicht, erstelle es
			if (!file_exists($protected_dir)) {
				wp_mkdir_p($protected_dir);
			}
			if (!file_exists($talent_dir)) {
				wp_mkdir_p($talent_dir);
			}
	
			// Eindeutigen Dateinamen erstellen
			$unique_filename = wp_unique_filename($talent_dir, $file['name']);
			$target_file = $talent_dir . '/' . $unique_filename;
	
			// Datei verschieben
			if (move_uploaded_file($file['tmp_name'], $target_file)) {	
				// Pfad in der Datenbank speichern
				save_file_path($talent_id, $unique_filename, $type);
				log_event(5, $unique_filename.' hochgeladen', $talent_id);
				wp_send_json_success('Lebenslauf erfolgreich hochgeladen!');
			} else {
				wp_send_json_error('Fehler beim Verschieben der Datei.');
			}
		} else {
			wp_send_json_error('Ungültiger Dateityp. Bitte laden Sie eine PDF, DOC oder DOCX Datei hoch.');
		}
	}

	function request_consultation(){
		if(!isset($_POST['talent_id'])){
			wp_send_json_error('Keine Talent ID');
		}
		$talent_id = intval($_POST['talent_id']);
		if (!has_edit_talent_permission($talent_id)) {
			wp_send_json_error('Keine Berechtigung');
		}
		
		// Hole die Talent-Daten basierend auf der Talent-ID
		$talent = get_talent_by_id($talent_id);
		if(!$talent){
			wp_send_json_error('Talent nicht gefunden');
		}
		send_consultation_mail($talent);
		wp_send_json_success('Benachrictgigun gesendet');
		wp_die();
	}
	
	function submit_evaluation() {
		if(!isset($_POST['talent_id'], $_POST['rating'])){
			wp_send_json_error('Felder fehlen');
		}
		$talent_id = intval($_POST['talent_id']);
		if (!has_edit_talent_permission($talent_id)) {
			wp_send_json_error('Keine Berechtigung');
		}
		$rating = absint($_POST['rating']);
		$comment = isset($_POST['comment']) ? sanitize_text_field($_POST['comment']) : '';
		global $wpdb;

		// Prepare data arrays for insert and update
		$data = array(
			'talent_id' => $talent_id,
			'rating' => $rating,
			'comment' => $comment,
		);

		$format = array(
			'%d',
			'%d',
			'%s'
		);

		// Füge das Matching hinzu
		$inserted = $wpdb->insert(
			$wpdb->prefix . 'te_evaluations',
			$data,
			$format
		);

		if (false === $inserted) {
			wp_send_json_error('Fehler beim Hinzufügen');
		}
		wp_send_json_success('added comment');
		wp_die();
	}

	function activate_all_matchings() {
		wp_send_json_error('Funktion deaktiviert');
		if (!has_service_permission()) {
			wp_send_json_error('Keine Berechtigung');
		}
	
		if (!isset($_POST['talent_id'])) {
			wp_send_json_error('Keine Talent-ID angegeben');
		}
	
		$talent_id = intval($_POST['talent_id']);
		$talent = get_talent_by_id($talent_id);
		if(!$talent){
			wp_send_json_error('Talent nicht gefunden');
		}
		$jobs = get_jobs_for_talent($talent); // Annahme: Diese Funktion gibt eine Liste von Jobs zurück
	
		if (empty($jobs)) {
			wp_send_json_error('Keine passenden Jobs gefunden.');
		}
	
		foreach ($jobs as $job) {
			$job_id = intval($job->ID); // Annahme: Hier wird die Job-ID aus den zurückgegebenen Jobs extrahiert
	
			// Überprüfe, ob das Matching bereits existiert
			$entry = get_matching_for_ids($talent_id, $job_id);
	
			if (!$entry) {
				global $wpdb;
	
				// Prepare data arrays for insert and update
				$data = array(
					'job_id' => $job_id,
					'talent_id' => $talent_id
				);
	
				$format = array(
					'%d',
					'%d'
				);
	
				// Füge das Matching hinzu
				$inserted = $wpdb->insert(
					$wpdb->prefix . 'te_matching',
					$data,
					$format
				);
	
				if (false === $inserted) {
					wp_send_json_error('Fehler beim Hinzufügen des Eintrags für Job ID ' . $job_id);
				}
			}
		}
	
		wp_send_json_success('Alle Matchings erfolgreich aktiviert.');
		wp_die();
	}

	function generate_resume_pdf() {
		if (!has_service_permission()) {
			wp_send_json_error('Keine Berechtigung');
		}
		if (!isset($_POST['talent_id'])) {
			wp_send_json_error('Keine Talent-ID angegeben');
		}
	
		$talent_id = intval($_POST['talent_id']);
	
		// Deine Funktion zur PDF-Erstellung
		global $wpdb;
	
		// Talents-Tabelle abfragen
		$talents_table = $wpdb->prefix . 'te_talents';
		$talent = $wpdb->get_row($wpdb->prepare("SELECT * FROM $talents_table WHERE ID = %d", $talent_id));
	
		if (!$talent) {
			wp_send_json_error('Talent nicht gefunden');
		}
		
		try {

		// PDF-Objekt erstellen
		require(TE_DIR.'fpdf/fpdf.php'); // Stelle sicher, dass der Pfad zur fpdf.php-Datei korrekt ist
		$pdf = new FPDF();
		$pdf->AddPage();
		$pdf->AddFont('Quicksand','M','Quicksand-Medium.php');
		$pdf->AddFont('Quicksand','R','Quicksand-Regular.php');
		$pdf->SetFont('Quicksand', 'M', 16);
		// Logo hinzufügen (z.B. 10 mm vom rechten Rand und 10 mm vom oberen Rand)
		$pdf->Image(TE_DIR.'images/logo.png', 150, 10, 40);
	
		// Titel
		$pdf->Cell(40, 10, 'Lebenslauf');
		$pdf->Ln(20);
	
		// Kandidateninformationen
		$pdf->SetFont('Quicksand', 'R', 12);
		$pdf->Cell(40, 10, 'Talent ' .$talent->member_id);
		$pdf->Ln(10);
		$pdf->Cell(40, 10, convert_encoding('Verfügbarkeit: ' . get_availability_string($talent->availability)));
		$pdf->Ln(10);
		$pdf->Cell(40, 10, 'Schulabschluss: ' . get_school_degree($talent->school));	
	
		// Weitere Informationen wie Erfahrung, Ausbildung etc. hinzufügen

		// Ausbildung
		$apprenticeship_table = $wpdb->prefix . 'te_apprenticeship';
		$apprenticeships = $wpdb->get_results($wpdb->prepare("SELECT * FROM $apprenticeship_table WHERE talent_id = %d", $talent_id));
		if ($apprenticeships) {
			$pdf->Ln(20);
			$pdf->SetFont('Quicksand', 'M', 12);
			$pdf->Cell(40, 10, 'Ausbildung:');
			$pdf->SetFont('Quicksand', 'R', 12);
			foreach ($apprenticeships as $apprenticeship) {
				$pdf->Ln(10);
				$pdf->Line(10, $pdf->GetY(), 100, $pdf->GetY());
				$pdf->Cell(40, 10, convert_encoding($apprenticeship->designation));
				$pdf->Ln(10);
				$pdf->Cell(40, 10, get_date_string($apprenticeship));
			}
		}

		// Studium
		$study_table = $wpdb->prefix . 'te_studies';
		$studies = $wpdb->get_results($wpdb->prepare("SELECT * FROM $study_table WHERE talent_id = %d", $talent_id));
		if ($studies) {
			$pdf->Ln(20);
			$pdf->SetFont('Quicksand', 'M', 12);
			$pdf->Cell(40, 10, 'Studium:');
			$pdf->SetFont('Quicksand', 'R', 12);
			foreach ($studies as $study) {
				$pdf->Ln(10);
				$pdf->Line(10, $pdf->GetY(), 100, $pdf->GetY());
				$pdf->Cell(40, 10, convert_encoding($study->designation));
				$pdf->Ln(10);
				$pdf->Cell(40, 10, get_date_string($study));
			}
		}

		//Ausbildung

		//Berufserfahrung
		$experience_table = $wpdb->prefix . 'te_experiences';
		$experiences = $wpdb->get_results($wpdb->prepare("SELECT * FROM $experience_table WHERE talent_id = %d", $talent_id));
		if ($experiences) {
			$pdf->Ln(20);
			$pdf->SetFont('Quicksand', 'M', 12);
			$pdf->Cell(40, 10, 'Berufserfahrung:');
			$pdf->SetFont('Quicksand', 'R', 12);
			foreach ($experiences as $experience) {
				$pdf->Ln(10);
				$pdf->Line(10, $pdf->GetY(), 100, $pdf->GetY());
				$pdf->Cell(40, 10, convert_encoding($experience->position));
				$pdf->Ln(10);
				$pdf->Cell(40, 10, convert_encoding($experience->company));
				$pdf->Ln(10);
				$pdf->Cell(40, 10, get_date_string($experience));
			}
		}
	

	
		// PDF-Datei in einen temporären Speicher speichern
		$upload_dir = wp_upload_dir();
		$upload_path = $upload_dir['basedir'] . '/talent_resumes/';
		
		if (!file_exists($upload_path)) {
			mkdir($upload_path, 0755, true);
		}
		
		$filename = 'Lebenslauf_Talent_' . $talent->member_id . '.pdf';
		$file_path = $upload_path . $filename;
		$pdf->Output('F', $file_path);

		} catch (Exception $e) {
			// Fehler protokollieren und anzeigen
			error_log('Error: ' . $e->getMessage());
			wp_send_json_error('Error: ' . $e->getMessage());
			wp_die();
		}

		// URL der Datei zurückgeben
		$file_url = $upload_dir['baseurl'] . '/talent_resumes/' . $filename;
		wp_send_json_success(['file_url' => $file_url]);
		wp_die();
	}

	function save_preference(){
		
		if (!isset($_POST['preference'])) {
			wp_send_json_error('Kein matching value');
		}
		global $wpdb;
		$table_name = $wpdb->prefix . 'te_preferences';
		$value = absint($_POST['preference']);
		if (isset($_POST['preference_id'])) {
			// Entferne potenziell gefährliche Zeichen aus der Eingabe
			$preference_id = absint($_POST['preference_id']);
			

			// Überprüfe, ob die Frage existiert
			$data = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$table_name} WHERE ID = %d", $preference_id));

			if ($data) {
				if (!has_edit_talent_permission($data->talent_id)) {
					wp_send_json_error('Keine Berechtigung');
				}			

				// Daten zum Aktualisieren
				$data = array(
					'value' => $value
				);

				// Bedingung für die Aktualisierung
				$where = array('ID' => $preference_id);

				// Aktualisieren der Daten in der Datenbank
				$wpdb->update($table_name, $data, $where);

				// Überprüfen, ob ein Fehler aufgetreten ist
				if ($wpdb->last_error !== '') {
					wp_send_json_error($wpdb->last_error);
				}else{
					wp_send_json_success('Test erfolgreich geändert.');
				}
				
			}else{
				wp_send_json_error('Eintrag nicht gefunden');
			}
		}else if(isset($_POST['talent_id'], $_POST['job_id'])){
			$job_id = absint($_POST['job_id']);
			$talent_id = absint($_POST['talent_id']);
			// Prepare data arrays for insert and update
			$data = array(
				'job_id' => $job_id,
				'talent_id' => $talent_id,
				'value' => $value
			);

			$format = array(
				'%d',
				'%d',
				'%d'
			);
			// Fügen Sie einen neuen Job hinzu
			$inserted = $wpdb->insert(
				$table_name,
				$data,
				$format
			);
	
			if (false === $inserted) {
				wp_send_json_error('Fehler beim Hinzufügen des Eintrag.');
			} else {
				wp_send_json_success('Eintrag erfolgreich hinzugefügt.');
			}
		}else{
			wp_send_json_error('Keine ID');
		}
	}


	function save_matching(){
		if (!isset($_POST['matching_id'])) {
			wp_send_json_error('Keine ID');
		}
		if (!isset($_POST['matching'])) {
			wp_send_json_error('Kein matching value');
		}
		global $wpdb;

		// Entferne potenziell gefährliche Zeichen aus der Eingabe
		$matching_id = absint($_POST['matching_id']);
		$table_name = $wpdb->prefix . 'te_matching';

		// Überprüfe, ob die Frage existiert
		$data = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$table_name} WHERE ID = %d", $matching_id));

		if ($data) {
			if (!has_edit_talent_permission($data->talent_id)) {
				wp_send_json_error('Keine Berechtigung');
			}
			$value = absint($_POST['matching']);

			// Daten zum Aktualisieren
			$data = array(
				'value' => $value
			);

			// Bedingung für die Aktualisierung
			$where = array('ID' => $matching_id);

			// Aktualisieren der Daten in der Datenbank
			$wpdb->update($table_name, $data, $where);

			// Überprüfen, ob ein Fehler aufgetreten ist
			if ($wpdb->last_error !== '') {
				wp_send_json_error($wpdb->last_error);
			}else{
				wp_send_json_success('Test erfolgreich geändert.');
			}
			
		} else {
			wp_send_json_error('Eintrag nicht gefunden');
		}
	}

	function activate_matching(){
		if (!has_service_permission()) {
			wp_send_json_error('Keine Berechtigung');
		}
		if (!isset($_POST['talent_id'])) {
			wp_send_json_error('Keine talent_id');
		}
		if (!isset($_POST['job_id'])) {
			wp_send_json_error('Keine job_id');
		}
		$talent_id = intval($_POST['talent_id']);
		$job_id = intval($_POST['job_id']);
		
		// Überprüfe, ob die Frage existiert
		$entry = get_matching_for_ids($talent_id, $job_id);
	
		if (!$entry) {
			global $wpdb;

			// Prepare data arrays for insert and update
			$data = array(
				'job_id' => $job_id,
				'talent_id' => $talent_id
			);

			$format = array(
				'%d',
				'%d'
			);
			// Fügen Sie einen neuen Job hinzu
			$inserted = $wpdb->insert(
				$wpdb->prefix . 'te_matching',
				$data,
				$format
			);
	
			if (false === $inserted) {
				wp_send_json_error('Fehler beim Hinzufügen des Eintrag.');
			} else {
				wp_send_json_success('Eintrag erfolgreich hinzugefügt.');
			}

		} else {
			wp_send_json_error('Eintrag schon vorhanden');
		}
	}

	function save_talent_notes(){
		if (!has_service_permission()) {
			wp_send_json_error('Keine Berechtigung');
		}
		if (!isset($_POST['talent_id'])) {
			wp_send_json_error('Keine ID');
		}
		if (!isset($_POST['notes'])) {
			wp_send_json_error('Keine notes');
		}
		$talent_id = intval($_POST['talent_id']);
		$talent = get_talent_by_id($talent_id);
		$notes = wp_kses_post($_POST['notes']);
		if(!$talent){
			wp_send_json_error('Talent nicht gefunden');
		}
		global $wpdb;
		// Tabellenname für Bewerbungen
		$table_name = $wpdb->prefix . 'te_talents';

		// Daten zum Aktualisieren
		$data = array(
			'notes' => $notes
		);

		// Bedingung für die Aktualisierung
		$where = array('ID' => $talent_id);

		// Aktualisieren der Daten in der Datenbank
		$wpdb->update($table_name, $data, $where);

		// Überprüfen, ob ein Fehler aufgetreten ist
		if ($wpdb->last_error !== '') {
			wp_send_json_error($wpdb->last_error);
		}else{
			wp_send_json_success('Test erfolgreich geändert.');
		}
	}

	function delete_requirement() {
		// Code für delete_requirement
		// Beispielcode:
		if (!has_service_permission()) {
			wp_send_json_error('Keine Berechtigung');
		}

		if (isset($_POST['requirement_id'])) {
			global $wpdb;
	
			// Entferne potenziell gefährliche Zeichen aus der Eingabe
			$requirement_id = absint($_POST['requirement_id']);
	
			// Überprüfe, ob die Frage existiert
			$data = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}te_requirements WHERE ID = %d", $requirement_id));
	
			if ($data) {
				// Lösche die Frage aus der Datenbank
				$wpdb->delete(
					$wpdb->prefix . 'te_requirements',
					array('ID' => $requirement_id),
					array('%d')
				);
	
				wp_send_json_success('Anforderung erfolgreich gelöscht');
			} else {
				wp_send_json_error('Anforderung nicht gefunden');
			}
		} else {
			wp_send_json_error('Fehler beim Löschen der Anforderung');
		}
	}

	function save_requirement(){
		global $wpdb;
	
		// Überprüfen, ob der Benutzer die erforderlichen Berechtigungen hat
		if (!has_service_permission()) {
			wp_send_json_error('Keine Berechtigung');
		}

		// Überprüfen, ob das erforderliche Feld 'job_id' gesetzt ist
		if (!isset($_POST['job_id'], $_POST['type'], $_POST['requirement_id'], $_POST['field'])) {
			wp_send_json_error('job_id, requirement_id, type or field missing');
		}

		$requirement_id = absint($_POST['requirement_id']);
		$job_id = absint($_POST['job_id']);
		$type = absint($_POST['type']);
		$field = absint($_POST['field']);
		$degree = isset($_POST['degree']) && $type == 2 ? absint($_POST['degree']) : null;

		// Prepare data arrays for insert and update
		$data = array(
			'job_id' => $job_id,
			'type' => $type,
			'field' => $field,
			'degree' => $degree
		);

		// Remove null values to avoid overwriting with NULL in database
		$data = array_filter($data, function($value) { return !is_null($value); });
		$format = array(
			'%d',
			'%d',
			'%d',
			'%d'
		);
		$format = array_slice($format, 0, count($data)); // Adjust format array length
		

		if ($requirement_id > 0) {
			// Aktualisieren Sie den vorhandenen Job
			$updated = $wpdb->update(
				$wpdb->prefix . 'te_requirements',
				$data,
				array('ID' => $requirement_id),
				$format,
				array('%d')
			);
	
			if (false === $updated) {
				wp_send_json_error('Fehler beim Aktualisieren der Anforderung.');
			} else {
				wp_send_json_success('Anforderung erfolgreich aktualisiert.');
			}
		} else {
			// Fügen Sie einen neuen Job hinzu
			$inserted = $wpdb->insert(
				$wpdb->prefix . 'te_requirements',
				$data,
				$format
			);
	
			if (false === $inserted) {
				wp_send_json_error('Fehler beim Hinzufügen der Anforderung.');
			} else {
				wp_send_json_success('Anforderung erfolgreich hinzugefügt.');
			}
		}

		wp_die();
	}

	function edit_game() {
		global $wpdb;
	
		// Überprüfen, ob der Benutzer die erforderlichen Berechtigungen hat
		if (!has_service_permission()) {
			wp_send_json_error('Keine Berechtigung');
		}
	
		// Überprüfen, ob das erforderliche Feld 'assistant_id' gesetzt ist
		if (!isset($_POST['assistant_id'])) {
			wp_send_json_error('assistant_id error');
		}
		if (!isset($_POST['gamekey'])) {
			wp_send_json_error('key error');
		}
		if (!isset($_POST['title'])) {
			wp_send_json_error('title error');
		}
	
		// Sanitize and retrieve POST data
		$game_id = isset($_POST['game_id']) ? intval($_POST['game_id']) : 0;
		$assistant_id = sanitize_text_field($_POST['assistant_id']);
		$title = sanitize_text_field($_POST['title']);
		$gamekey = sanitize_text_field($_POST['gamekey']);
		$image_url = isset($_POST['image_url']) ? sanitize_text_field($_POST['image_url']) : '';
		$info_text = wp_kses_post($_POST['info_text']);
		$start_msg = wp_kses_post($_POST['start_msg']);
		$info_title = isset($_POST['info_title']) ? sanitize_text_field($_POST['info_title']) : '';
		$info_msg = wp_kses_post($_POST['info_msg']);
		$type = isset($_POST['type']) ? intval($_POST['type']) : 0;
	
		// Prepare data arrays for insert and update
		$data = array(
			'assistant_id' => $assistant_id,
			'gamekey' => $gamekey,
			'title' => $title,
			'image_url' => $image_url,
			'info_text' => $info_text,
			'start_msg' => $start_msg,
			'info_title' => $info_title,
			'info_msg' => $info_msg,
			'type' => $type,
		);
	
		// Remove null values to avoid overwriting with NULL in database
		$data = array_filter($data, function($value) { return !is_null($value); });
		$format = array(
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%d',
		);
		$format = array_slice($format, 0, count($data)); // Adjust format array length
	
		if ($game_id > 0) {
			// Aktualisieren Sie das vorhandene Spiel
			$updated = $wpdb->update(
				$wpdb->prefix . 'te_games',
				$data,
				array('ID' => $game_id),
				$format,
				array('%d')
			);
	
			if (false === $updated) {
				wp_send_json_error($wpdb->last_error);
			} else {
				wp_send_json_success('Spiel erfolgreich aktualisiert.');
			}
		} else {
			// Fügen Sie ein neues Spiel hinzu
			$inserted = $wpdb->insert(
				$wpdb->prefix . 'te_games',
				$data,
				$format
			);
	
			if (false === $inserted) {
				wp_send_json_error($wpdb->last_error);
			} else {
				wp_send_json_success('Spiel erfolgreich hinzugefügt.');
			}
		}
		wp_die();
	}

	function edit_job() {
		global $wpdb;
	
		// Überprüfen, ob der Benutzer die erforderlichen Berechtigungen hat
		if (!has_service_permission()) {
			wp_send_json_error('Keine Berechtigung');
		}
	
		// Überprüfen, ob das erforderliche Feld 'job_title' gesetzt ist
		if (!isset($_POST['job_title'])) {
			wp_send_json_error('job_title error');
		}
	
		// Sanitize and retrieve POST data
		$job_id = isset($_POST['job_id']) ? intval($_POST['job_id']) : 0;
		$customer_id = isset($_POST['customer_id']) ? intval($_POST['customer_id']) : null;
		$job_title = sanitize_text_field($_POST['job_title']);
		$company = isset($_POST['company']) ? sanitize_text_field($_POST['company']) : '';
		$job_info = wp_kses_post($_POST['job_info']);
		$notes = isset($_POST['notes']) ? sanitize_text_field($_POST['notes']) : '';
		$job_url = isset($_POST['job_url']) ? sanitize_text_field($_POST['job_url']) : '';
		$post_code = isset($_POST['post_code']) ? sanitize_text_field($_POST['post_code']) : null;
		$school = isset($_POST['school']) ? intval($_POST['school']) : null;
		$english = isset($_POST['english']) ? intval($_POST['english']) : null;
		$mobility = isset($_POST['mobility']) ? intval($_POST['mobility']) : null;
		$license = filter_var($_POST['license'], FILTER_VALIDATE_BOOLEAN);
		$home_office = filter_var($_POST['home_office'], FILTER_VALIDATE_BOOLEAN);
		$part_time = filter_var($_POST['part_time'], FILTER_VALIDATE_BOOLEAN);
		$availability = isset($_POST['availability']) ? intval($_POST['availability']) : null;
	
		// Prepare data arrays for insert and update
		$data = array(
			'customer_id' => $customer_id,
			'job_title' => $job_title,
			'notes' => $notes,
			'company' => $company,
			'job_info' => $job_info,
			'link' => $job_url,
			'post_code' => $post_code,
			'school' => $school,
			'english' => $english,
			'mobility' => $mobility,
			'license' => $license,
			'home_office' => $home_office,
			'part_time' => $part_time,
			'availability' => $availability
		);
		
		// Remove null values to avoid overwriting with NULL in database
		$data = array_filter($data, function($value) { return !is_null($value); });
		$format = array(
			'%d',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%d',
			'%d',
			'%d',
			'%d',
			'%d',
			'%d',
			'%d'

		);
		$format = array_slice($format, 0, count($data)); // Adjust format array length
	
		if ($job_id > 0) {
			// Aktualisieren Sie den vorhandenen Job
			$updated = $wpdb->update(
				$wpdb->prefix . 'te_jobs',
				$data,
				array('ID' => $job_id),
				$format,
				array('%d')
			);
	
			if (false === $updated) {
				wp_send_json_error($wpdb->last_error);
			} else {
				wp_send_json_success('Job erfolgreich aktualisiert.');
			}
		} else {
			// Fügen Sie einen neuen Job hinzu
			$inserted = $wpdb->insert(
				$wpdb->prefix . 'te_jobs',
				$data,
				$format
			);
	
			if (false === $inserted) {
				wp_send_json_error($wpdb->last_error);
			} else {
				wp_send_json_success('Job erfolgreich hinzugefügt.');
			}
		}
		wp_die();
	}	

	function edit_customer() {
		global $wpdb;
	
		// Überprüfen, ob der Benutzer die erforderlichen Berechtigungen hat
		if (!has_service_permission()) {
			wp_send_json_error('Keine Berechtigung');
		}
	
		// Überprüfen, ob das erforderliche Feld 'company_name' gesetzt ist
		if (!isset($_POST['company_name'])) {
			wp_send_json_error('company_name error');
		}
	
		// Sanitize and retrieve POST data
		$customer_id = isset($_POST['customer_id']) ? intval($_POST['customer_id']) : 0;
		$company_name = sanitize_text_field($_POST['company_name']);
		$member_id = isset($_POST['member_id']) ? intval($_POST['member_id']) : null;
		$prename = isset($_POST['prename']) ? sanitize_text_field($_POST['prename']) : null;
		$surname = isset($_POST['surname']) ? sanitize_text_field($_POST['surname']) : null;
		$email = isset($_POST['email']) ? sanitize_email($_POST['email']) : null;
		$mobile = isset($_POST['mobile']) ? sanitize_text_field($_POST['mobile']) : null;
		$position = isset($_POST['position']) ? sanitize_text_field($_POST['position']) : null;
		$state = isset($_POST['state']) ? intval($_POST['state']) : 0;
	
		// Prepare data arrays for insert and update
		$data = array(
			'company_name' => $company_name
		);
		$format = array('%s');
	
		if (!is_null($member_id)) {
			$data['member_id'] = $member_id;
			$format[] = '%d';
		}
		if (!is_null($state)) {
			$data['state'] = $state;
			$format[] = '%d';
		}
		if (!is_null($prename)) {
			$data['prename'] = $prename;
			$format[] = '%s';
		}
		if (!is_null($surname)) {
			$data['surname'] = $surname;
			$format[] = '%s';
		}
		if (!is_null($email)) {
			$data['email'] = $email;
			$format[] = '%s';
		}
		if (!is_null($mobile)) {
			$data['mobile'] = $mobile;
			$format[] = '%s';
		}
		if (!is_null($position)) {
			$data['position'] = $position;
			$format[] = '%s';
		}
	
		if ($customer_id > 0) {
			// Aktualisieren Sie den vorhandenen Kunden
			$updated = $wpdb->update(
				$wpdb->prefix . 'te_customers',
				$data,
				array('ID' => $customer_id),
				$format,
				array('%d')
			);
	
			if (false === $updated) {
				wp_send_json_error('Fehler beim Aktualisieren des Kunden.');
			} else {
				wp_send_json_success('Kunde erfolgreich aktualisiert.');
			}
		} else {
			// Fügen Sie einen neuen Kunden hinzu
			$inserted = $wpdb->insert(
				$wpdb->prefix . 'te_customers',
				$data,
				$format
			);
	
			if (false === $inserted) {
				wp_send_json_error('Fehler beim Hinzufügen des Kunden.');
			} else {
				wp_send_json_success('Kunde erfolgreich hinzugefügt.');
			}
		}
		wp_die();
	}

	function deactivate_job(){
		if (!has_service_permission()) {
			wp_send_json_error('Keine Berechtigung');
		}
		if (!isset($_POST['job_id'])) {
			wp_send_json_error('Keine ID');
		}
		$job_id = absint($_POST['job_id']);
		$job = get_job_by_id($job_id);
		if(!$job){
			wp_send_json_error('Job nicht gefunden');
		}
		change_job_state($job, 0);
		wp_send_json_success('Job Status geändert');
	}

	function reactivate_job(){
		if (!has_service_permission()) {
			wp_send_json_error('Keine Berechtigung');
		}
		if (!isset($_POST['job_id'])) {
			wp_send_json_error('Keine ID');
		}
		$job_id = absint($_POST['job_id']);
		$job = get_job_by_id($job_id);
		if(!$job){
			wp_send_json_error('Job nicht gefunden');
		}
		change_job_state($job, 1);
		wp_send_json_success('Job Status geändert');
	}

	function remove_job(){
		if (!has_service_permission()) {
			wp_send_json_error('Keine Berechtigung');
		}
		if (!isset($_POST['job_id'])) {
			wp_send_json_error('Keine ID');
		}
		$job_id = absint($_POST['job_id']);
		$job = get_job_by_id($job_id);
		if(!$job){
			wp_send_json_error('Job nicht gefunden');
		}
		remove_expired_job($job);
		wp_send_json_success('Eintrag gelöscht');
		wp_die();
	}

	function remove_customer(){
		if (!has_service_permission()) {
			wp_send_json_error('Keine Berechtigung');
		}
		if (!isset($_POST['customer_id'])) {
			wp_send_json_error('Keine ID');
		}
		$customer_id = absint($_POST['customer_id']);
		$customer = get_customer_by_id($customer_id);
		if(!$customer){
			wp_send_json_error('Kunde nicht gefunden');
		}
		global $wpdb;
		$wpdb->delete(
			$wpdb->prefix . 'te_customers',
			array('ID' => $customer_id),
			array('%d')
		);
		if ($wpdb->last_error !== '') {
			wp_send_json_error($wpdb->last_error);
		}else{
			wp_send_json_success('Eintrag gelöscht');
		}
		wp_die();
	}
	
	function remove_talent(){
		if (!has_service_permission()) {
			wp_send_json_error('Keine Berechtigung');
		}
		if (!isset($_POST['talent_id'])) {
			wp_send_json_error('Keine ID');
		}
		$talent_id = absint($_POST['talent_id']);
		$talent = get_talent_by_id($talent_id);
		if(!$talent){
			wp_send_json_error('Talent nicht gefunden');
		}
		global $wpdb;
		$wpdb->delete(
			$wpdb->prefix . 'te_experiences',
			array('talent_id' => $talent_id),
			array('%d')
		);
		$wpdb->delete(
			$wpdb->prefix . 'te_apprenticeship',
			array('talent_id' => $talent_id),
			array('%d')
		);
		$wpdb->delete(
			$wpdb->prefix . 'te_studies',
			array('talent_id' => $talent_id),
			array('%d')
		);
		$wpdb->delete(
			$wpdb->prefix . 'te_eq',
			array('talent_id' => $talent_id),
			array('%d')
		);
		$wpdb->delete(
			$wpdb->prefix . 'te_matching',
			array('talent_id' => $talent_id),
			array('%d')
		);
		$wpdb->delete(
			$wpdb->prefix . 'te_evaluations',
			array('talent_id' => $talent_id),
			array('%d')
		);
		remove_unregistered_talent($talent);
		
		wp_send_json_success('Eintrag gelöscht');
		wp_die();
	}
	
	function send_job_mail(){
		// Prüfe, ob der aktuelle Benutzer die erforderlichen Berechtigungen hat
		if (!has_service_permission()) {
			wp_send_json_error('Keine Berechtigung');
		}
		
		// Prüfe, ob die 'talent_id' in den POST-Daten vorhanden ist
		if (!isset($_POST['talent_id'])) {
			wp_send_json_error('Keine ID');
		}
		
		// Erhalte und validiere die 'talent_id'
		$talent_id = absint($_POST['talent_id']);
		
		// Hole die Anzahl der aktiven Jobs für die gegebene Talent-ID
		$count = get_active_matching_count_for_talent_id($talent_id);
		
		// Wenn keine aktiven Jobs vorhanden sind, sende eine Erfolgsnachricht und beende die Funktion
		if($count == 0){
			wp_send_json_success('Keine Aktiven jobs vorhanden');
		} else {
			// Hole die Talent-Daten basierend auf der Talent-ID
			$talent = get_talent_by_id($talent_id);
			send_new_job_mail($talent, $count);
			
			// Sende eine Erfolgsnachricht mit der Anzahl der neuen Stellen
			wp_send_json_success('Email für ' . $count . ' neue Stellen wurde gesendet');
		}
		
		// Beende die Ausführung des Skripts
		wp_die();
	}
	

	function send_activate_account_mail(){
		if (!has_service_permission()) {
			wp_send_json_error('Keine Berechtigung');
		}
		if (!isset($_POST['talent_id'])) {
			wp_send_json_error('Keine ID');
		}
		
		$talent_id = absint($_POST['talent_id']);
		$talent = get_talent_by_id($talent_id);
		if(!$talent){
			wp_send_json_error('Talent nicht gefunden');
		}
		if (!$talent->member_id){
			wp_send_json_error('member_id nicht gefunden');
		}
		$registration_link = send_register_again($talent);
		wp_send_json_success($registration_link);
		wp_die();
	}

	function create_user(){
		if (!has_service_permission()) {
			wp_send_json_error('Keine Berechtigung');
		}
		if (!isset($_POST['talent_id'])) {
			wp_send_json_error('Keine ID');
		}
		$talent_id = intval($_POST['talent_id']);
		$talent = get_talent_by_id($talent_id);
		if(!$talent){
			wp_send_json_error('Talent nicht gefunden');
		}
		if($talent->member_id){
			wp_send_json_error('member_id schon vorhanden');
		}
		if (!$talent->prename || !$talent->surname || !$talent->email) {
			//one of the mandatory fields missing
			wp_send_json_error('Missing one of the mandatory fields: first_name, last_name, email');
		}
		$custom_email = filter_var($_POST['custom_email'], FILTER_VALIDATE_BOOLEAN);
		$settings = SwpmSettings::get_instance();
		$api_key = $settings->get_value('swpm-addon-api-key');
		if(!$api_key){
			wp_send_json_error('Missing api key');
		}
		$post_arr = array(
			'swpm_api_action' => 'create',
			'key' => $api_key,
			'first_name' => $talent->prename,
			'last_name' => $talent->surname ,
			'email' => $talent->email,
			'membership_level' => '5',
			'send_email' => $custom_email ? false : true,
		);

		// cURL-Optionen setzen
        $options = array(
            CURLOPT_URL => home_url(),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $post_arr,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 30,
        );
    
        // cURL-Anfrage ausführen
        $curl = curl_init();
        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
    
        // Überprüfen Sie den Status der cURL-Anfrage
        if ($http_status == 200) {
            // Erfolgreich ausgeführt
            // JSON-Daten aus der Antwort extrahieren
            $response_data = json_decode($response, true);
			$response_message = $response_data['message'];
			if(isset($response_data['member'])){
				$new_member = $response_data['member'];
				if (isset($new_member['member_id']) && $new_member['member_id'] > 0) {
					global $wpdb;
					$wpdb->update(
						$wpdb->prefix . 'te_talents',
						array(
							'member_id' => $new_member['member_id']
						),
						array('ID' => $talent_id),
						array('%d'),
						array('%d')
					);
					if($custom_email){
						send_missed_call($talent, $new_member);
					}else{
						log_event(2, 'Email mit Registrierungslink wurde verschickt', $talent->ID);
					}
				}else{
					wp_send_json_error('No member_id');
				}
			}else if(isset($response_data['errors'])){
				wp_send_json_error($response_data['errors']);
			}
			
            wp_send_json_success($response_message); // Nachrichten aus der Antwort zurückgeben
        } else {
            // Fehler beim Abrufen der Nachrichten
            wp_send_json_error( 'unerwarteter Fehler' );
        }

		wp_die();
	}

	function delete_experience(){
		if (isset($_POST['experience_id'])) {
			global $wpdb;
	
			// Entferne potenziell gefährliche Zeichen aus der Eingabe
			$experience_id = absint($_POST['experience_id']);
	
			// Überprüfe, ob die Frage existiert
			$data = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}te_experiences WHERE ID = %d", $experience_id));
	
			if ($data) {
				if (!has_edit_talent_permission($data->talent_id)) {
					wp_send_json_error('Keine Berechtigung');
				}
				// Lösche die Frage aus der Datenbank
				$wpdb->delete(
					$wpdb->prefix . 'te_experiences',
					array('ID' => $experience_id),
					array('%d')
				);
	
				wp_send_json_success('Eintrag erfolgreich gelöscht');
			} else {
				wp_send_json_error('Eintrag nicht gefunden');
			}
		} else {
			wp_send_json_error('Keine ID');
		}
		wp_die();
	}

	function delete_apprenticeship(){
		if (isset($_POST['apprenticeship_id'])) {
			global $wpdb;
	
			// Entferne potenziell gefährliche Zeichen aus der Eingabe
			$apprenticeship_id = absint($_POST['apprenticeship_id']);
	
			// Überprüfe, ob die Frage existiert
			$data = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}te_apprenticeship WHERE ID = %d", $apprenticeship_id));
	
			if ($data) {
				if (!has_edit_talent_permission($data->talent_id)) {
					wp_send_json_error('Keine Berechtigung');
				}
				// Lösche die Frage aus der Datenbank
				$wpdb->delete(
					$wpdb->prefix . 'te_apprenticeship',
					array('ID' => $apprenticeship_id),
					array('%d')
				);
	
				wp_send_json_success('Eintrag erfolgreich gelöscht');
			} else {
				wp_send_json_error('Eintrag nicht gefunden');
			}
		} else {
			wp_send_json_error('Keine ID');
		}
		wp_die();
	}

	function delete_study(){
		if (isset($_POST['study_id'])) {
			global $wpdb;
	
			// Entferne potenziell gefährliche Zeichen aus der Eingabe
			$study_id = absint($_POST['study_id']);
	
			// Überprüfe, ob die Frage existiert
			$data = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}te_studies WHERE ID = %d", $study_id));
	
			if ($data) {
				if (!has_edit_talent_permission($data->talent_id)) {
					wp_send_json_error('Keine Berechtigung');
				}
				// Lösche die Frage aus der Datenbank
				$wpdb->delete(
					$wpdb->prefix . 'te_studies',
					array('ID' => $study_id),
					array('%d')
				);
	
				wp_send_json_success('Eintrag erfolgreich gelöscht');
			} else {
				wp_send_json_error('Eintrag nicht gefunden');
			}
		} else {
			wp_send_json_error('Keine ID');
		}
		wp_die();
	}

	function edit_eq() {
		// Überprüfen, ob die erforderlichen Felder gesetzt sind
		if (!isset($_POST['value']) || !isset($_POST['talent_id'])) {
			wp_send_json_error('Erforderliche Felder fehlen');
		}		
	
		// Holen Sie sich die Werte aus dem POST
		$value = wp_kses_post($_POST['value']);
		$talent_id = intval($_POST['talent_id']);
		if (!has_edit_talent_permission($talent_id)) {
			wp_send_json_error('Keine Berechtigung');
		}

		global $wpdb;
	
		// Überprüfen, ob es sich um eine neue EQ-Frage handelt oder eine vorhandene aktualisiert wird
		$eq_id = isset($_POST['eq_id']) ? intval($_POST['eq_id']) : 0;
		if ($eq_id > 0) {
			// Aktualisieren Sie die vorhandene EQ-Frage
			$wpdb->update(
				$wpdb->prefix . 'te_eq',
				array(
					'value' => $value
				),
				array('ID' => $eq_id),
				array('%s'),
				array('%d')
			);
		} else {
			// Fügen Sie eine neue EQ-Frage hinzu
			$wpdb->insert(
				$wpdb->prefix . 'te_eq',
				array(
					'talent_id' => $talent_id,
					'value' => $value
				),
				array('%d', '%s')
			);
		}
	
		// Erfolgsantwort senden
		wp_send_json_success('EQ-Frage erfolgreich aktualisiert/hinzugefügt');
	}
	
	function edit_experience() {
		// Überprüfen, ob die erforderlichen Felder gesetzt sind
		if (!isset($_POST['position']) || !isset($_POST['company']) || !isset($_POST['field']) || !isset($_POST['start_date']) || !isset($_POST['talent_id'])) {
			wp_send_json_error('Erforderliche Felder fehlen');
		}
	
		// Holen Sie sich die Werte aus dem POST und bereinigen Sie sie
		$talent_id = intval($_POST['talent_id']);
		if (!has_edit_talent_permission($talent_id)) {
			wp_send_json_error('Keine Berechtigung');
		}

		$position = sanitize_text_field($_POST['position']);
		$company = sanitize_text_field($_POST['company']);
		$field = intval($_POST['field']);
		$start_date = sanitize_text_field($_POST['start_date']);
		$end_date = isset($_POST['end_date']) ? sanitize_text_field($_POST['end_date']) : '9999-12-31';
		$activity = isset($_POST['activity']) ? wp_kses_post($_POST['activity']) : '';
		

		global $wpdb;
	
		// Überprüfen, ob es sich um eine neue Berufserfahrung handelt oder eine vorhandene aktualisiert wird
		$experience_id = isset($_POST['experience_id']) ? intval($_POST['experience_id']) : 0;
		if ($experience_id > 0) {
			// Aktualisieren Sie die vorhandene Berufserfahrung
			$updated = $wpdb->update(
				$wpdb->prefix . 'te_experiences',
				array(
					'position' => $position,
					'company' => $company,
					'field' => $field,
					'activity' => $activity,
					'start_date' => $start_date,
					'end_date' => $end_date
				),
				array('ID' => $experience_id),
				array('%s', '%s', '%s', '%s', '%s', '%s'),
				array('%d')
			);
			if ($updated === false) {
				wp_send_json_error('Fehler beim Aktualisieren der Berufserfahrung');
			}
		} else {
			// Fügen Sie eine neue Berufserfahrung hinzu
			$inserted = $wpdb->insert(
				$wpdb->prefix . 'te_experiences',
				array(
					'talent_id' => $talent_id,
					'field' => $field,
					'position' => $position,
					'company' => $company,
					'activity' => $activity,
					'start_date' => $start_date,
					'end_date' => $end_date
				),
				array('%d', '%s', '%s', '%s', '%s', '%s', '%s')
			);
			if ($inserted === false) {
				wp_send_json_error('Fehler beim Hinzufügen der Berufserfahrung');
			}
		}
	
		// Erfolgsantwort senden
		wp_send_json_success('Berufserfahrung erfolgreich aktualisiert/hinzugefügt');
	}
	

	function edit_study() {	
		// Überprüfen, ob die erforderlichen Felder gesetzt sind
		if (!isset($_POST['field']) || !isset($_POST['designation']) || !isset($_POST['degree']) || !isset($_POST['start_date']) || !isset($_POST['talent_id'])) {
			wp_send_json_error('Erforderliche Felder fehlen');
		}

		$talent_id = intval($_POST['talent_id']);
		if (!has_edit_talent_permission($talent_id)) {
			wp_send_json_error('Keine Berechtigung');
		}
	
		global $wpdb;
	
		// Holen Sie sich die Werte aus dem POST
		$field = intval($_POST['field']);
		$degree = intval($_POST['degree']);
		$designation = sanitize_text_field($_POST['designation']);
		$start_date = sanitize_text_field($_POST['start_date']);
		$end_date = isset($_POST['end_date']) ? sanitize_text_field($_POST['end_date']) : '9999-12-31';
	
		// Überprüfen, ob es sich um eine neue Ausbildung handelt oder eine vorhandene aktualisiert wird
		$study_id = isset($_POST['study_id']) ? intval($_POST['study_id']) : 0;
		if ($study_id > 0) {
			// Aktualisieren Sie die vorhandene Ausbildung
			$wpdb->update(
				$wpdb->prefix . 'te_studies',
				array(
					'field' => $field,
					'degree' => $degree,
					'designation' => $designation,
					'start_date' => $start_date,
					'end_date' => $end_date,
				),
				array('ID' => $study_id),
				array('%d', '%d', '%s', '%s', '%s'),
				array('%d')
			);
		} else {
			// Fügen Sie eine neue Ausbildung hinzu
			$wpdb->insert(
				$wpdb->prefix . 'te_studies',
				array(
					'talent_id' => $talent_id,
					'field' => $field,
					'degree' => $degree,
					'designation' => $designation,
					'start_date' => $start_date,
					'end_date' => $end_date,
				),
				array('%d', '%d', '%d', '%s', '%s', '%s')
			);
		}
	
		// Erfolgsantwort senden
		wp_send_json_success('Studium erfolgreich aktualisiert/hinzugefügt');
	}

	function edit_apprenticeship() {

		// Überprüfen, ob die erforderlichen Felder gesetzt sind
		if (!isset($_POST['field']) || !isset($_POST['designation']) || !isset($_POST['start_date']) || !isset($_POST['talent_id'])) {
			wp_send_json_error('Erforderliche Felder fehlen');
		}

		$talent_id = intval($_POST['talent_id']);
		if (!has_edit_talent_permission($talent_id)) {
			wp_send_json_error('Keine Berechtigung');
		}
	
		global $wpdb;
	
		// Holen Sie sich die Werte aus dem POST
		$field = intval($_POST['field']);
		$designation = sanitize_text_field($_POST['designation']);
		$start_date = sanitize_text_field($_POST['start_date']);
		$end_date = isset($_POST['end_date']) ? sanitize_text_field($_POST['end_date']) : '9999-12-31';
	
		// Überprüfen, ob es sich um eine neue Ausbildung handelt oder eine vorhandene aktualisiert wird
		$apprenticeship_id = isset($_POST['apprenticeship_id']) ? intval($_POST['apprenticeship_id']) : 0;
		if ($apprenticeship_id > 0) {
			// Aktualisieren Sie die vorhandene Ausbildung
			$wpdb->update(
				$wpdb->prefix . 'te_apprenticeship',
				array(
					'field' => $field,
					'designation' => $designation,
					'start_date' => $start_date,
					'end_date' => $end_date,
				),
				array('ID' => $apprenticeship_id),
				array('%d', '%s', '%s', '%s'),
				array('%d')
			);
		} else {
			// Fügen Sie eine neue Ausbildung hinzu
			$wpdb->insert(
				$wpdb->prefix . 'te_apprenticeship',
				array(
					'talent_id' => $talent_id,
					'field' => $field,
					'designation' => $designation,
					'start_date' => $start_date,
					'end_date' => $end_date,
				),
				array('%d', '%d', '%s', '%s', '%s')
			);
		}
	
		// Erfolgsantwort senden
		wp_send_json_success('Ausbildung erfolgreich aktualisiert/hinzugefügt');
	}

	function save_talent_details(){

		if (isset($_POST['talent_id'], $_POST['prename'], $_POST['surname'], $_POST['email'], $_POST['mobile'], $_POST['availability'], $_POST['post_code'], $_POST['school'], $_POST['english'])) {
			$talent_id = intval($_POST['talent_id']);
			if (!has_edit_talent_permission($talent_id)) {
				wp_send_json_error('Keine Berechtigung');
			}
			$prename = sanitize_text_field($_POST['prename']);
			$surname = sanitize_text_field($_POST['surname']);
			$email = sanitize_text_field($_POST['email']);
			$mobile = sanitize_text_field($_POST['mobile']);
			$post_code = sanitize_text_field($_POST['post_code']);
			$availability = intval($_POST['availability']);
			$license = filter_var($_POST['license'], FILTER_VALIDATE_BOOLEAN);
			$home_office = filter_var($_POST['home_office'], FILTER_VALIDATE_BOOLEAN);
			$part_time = filter_var($_POST['part_time'], FILTER_VALIDATE_BOOLEAN);
			$mobility = absint($_POST['mobility']);
			$school = absint($_POST['school']);
			$english = absint($_POST['english']);
			global $wpdb;
			// Tabellenname für Bewerbungen
			$table_name = $wpdb->prefix . 'te_talents';

			// Daten zum Aktualisieren
			$data = array(
				'prename' => $prename,
				'surname' => $surname,
				'email' => $email,
				'mobile' => $mobile,
				'post_code' => $post_code,
				'availability' => $availability,
				'mobility' => $mobility,
				'license' => $license,
				'home_office' => $home_office,
				'part_time' => $part_time,
				'school' => $school,
				'english' => $english
			);

			// Bedingung für die Aktualisierung
			$where = array('ID' => $talent_id);

			// Aktualisieren der Daten in der Datenbank
			$wpdb->update($table_name, $data, $where);

			// Überprüfen, ob ein Fehler aufgetreten ist
			if ($wpdb->last_error !== '') {
				wp_send_json_error($wpdb->last_error);
			}else{
				wp_send_json_success('Test erfolgreich geändert.');
			}
		} else {
			// Fehlermeldung zurückgeben, wenn erforderliche Daten fehlen
			wp_send_json_error('Ungültige Anfrage.');
		}

		wp_die();
	}

	function save_talent() {
		// Session starten
		session_start();
	
		// Überprüfen, ob eine aktive Sitzung besteht
		if (!isset($_SESSION['active_chat'])) {
			wp_send_json_error('Aktive Sitzung nicht gefunden.');
		}
	
		// Überprüfen, ob alle Formulareingaben vorhanden sind
		$required_fields = array('prename', 'surname', 'email', 'mobile','post_code', 'acceptPrivacy');
		foreach ($required_fields as $field) {
			if (!isset($_POST[$field])) {
				wp_send_json_error('Bitte füllen Sie alle erforderlichen Felder aus.');
			}
		}
	
		// Formulardaten sammeln
		$prename = sanitize_text_field($_POST['prename']);
		$surname = sanitize_text_field($_POST['surname']);
		$email = sanitize_email($_POST['email']);
		$mobile = sanitize_text_field($_POST['mobile']);
		$post_code = sanitize_text_field($_POST['post_code']);
		$oai_test_id = $_SESSION['active_chat']; // Wert aus der Session übernehmen
		$ref = isset($_POST['ad']) ? sanitize_text_field($_POST['ad']) : 'unknown';
	
		// Tabelle und Datenbankverbindung
		global $wpdb;
		$table_name = $wpdb->prefix . 'te_talents';
	
		// Datensatz einfügen
		$insert_result = $wpdb->insert(
			$table_name,
			array(
				'prename' => $prename,
				'surname' => $surname,
				'email' => $email,
				'mobile' => $mobile,
				'post_code' => $post_code,
				'oai_test_id' => $oai_test_id,
				'ref' => $ref,
				'availability' => 0
			),
			array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d')
		);
	
		// Überprüfen, ob das Einfügen erfolgreich war
		if ($insert_result === false) {
			wp_send_json_error('Fehler beim Speichern des Datensatzes.');
		} else {
			unset($_SESSION['active_chat']);
			unset($_SESSION['game']);
			wp_send_json_success('Datensatz erfolgreich gespeichert.');
		}
		wp_die();
	}	

	function delete_chat(){
		session_start();
		if (isset($_SESSION['active_chat'])){
			$thread_id = $_SESSION['active_chat'];
			$result = delete_thread($thread_id);
			if(!$result){
				wp_send_json_error('Fehler beim ausführen');
			}else{
				unset($_SESSION['active_chat']);
				unset($_SESSION['game']);
				wp_send_json_success('Chat erfolgreich gelöscht');
			}
			
		}else{
			wp_send_json_error('Ungültige Anfrage.');
		}
		wp_die();
	}

	function send_message(){
		session_start();
		if (isset($_POST['message'], $_SESSION['active_chat'])){
			$thread_id = $_SESSION['active_chat'];
			$message = sanitize_text_field($_POST['message']);
			if(!add_message_to_thread($thread_id, $message)){
				wp_send_json_error('Fehler beim hinzufügen der Nachricht');
			}
			$run = run_thread($thread_id);
			if(!$run){
				wp_send_json_error('Fehler beim ausführen');
			}else{
				$message = get_message_from_run($run);
				if(!$message){
					wp_send_json_error('Keine Antwort erhalten');
				}
				$parsedMessage = parseMessageFromObject($message);

				// Extrahiere den Status aus der Nachricht
				if (strpos(strtolower($parsedMessage), 'bestanden') !== false) {
					$state = 'success';
				} elseif (strpos(strtolower($parsedMessage), 'durchgefallen') !== false) {
					$state = 'failed';
				} else {
					$state = 'in_progress';
				}

				// Entferne 'bestanden' oder 'nicht bestanden' aus der Nachricht
				$slicedMessage = str_replace(['Test bestanden', 'Test nicht bestanden'], '', $parsedMessage);

				// Daten für die Antwort kodieren
				$data = json_encode(['message' => $slicedMessage, 'state' => $state]);

				// Senden der JSON-Antwort
				wp_send_json_success($data);
			}
		}else{
			wp_send_json_error('Ungültige Anfrage.');
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
		wp_enqueue_script('hammerjs', plugin_dir_url(__FILE__) . 'js/hammer.min.js', array('jquery'), '2.0.8', true);	
		wp_enqueue_script('bootstrap-js', plugin_dir_url(__FILE__) . 'js/bootstrap.bundle.min.js', array('jquery'), '5.3.3', true);	
		wp_enqueue_script('fontawesome', plugin_dir_url(__FILE__) . 'js/all.min.js', array('jquery'), '6.5.2', true);	

	}

}