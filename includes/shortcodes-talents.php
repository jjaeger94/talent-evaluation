<?php    
     /**
     * Hier alle Shortcodes für Bewerber eintragen!
     */
function register_shortcodes_talents() {
        add_shortcode( 'consent_form', 'render_consent_form' );
        add_shortcode( 'commitment_test', 'render_commitment_test' );
        add_shortcode('test_start', 'render_test_start');
        add_shortcode('get_company', 'get_company_shortcode');
}

function get_company_shortcode(){
     if(!isset($_GET['key'])){
          return 'Keine Berechtigung';
     }
     $key = sanitize_text_field( $_GET['key'] );
     $uid = 0;
     if(isset($_GET['uid'])){
          $uid = intval($_GET['uid']);
          if (commitment_hash($uid) != $key) {
               return 'Keine Berechtigung';
          }
     }else if(isset($_GET['jid'])){
          $jid = intval($_GET['jid']);
          if (commitment_hash($jid) != $key) {
               return 'Keine Berechtigung';
          }
          $job = get_job_by_id_permissionless($jid);
          if(!$job){
               return 'Falsche Stellen ID';
          }
          $uid = $job->user_id;
     }else if(isset($_GET['aid'])){
          $aid = intval($_GET['aid']);
          if (commitment_hash($aid) != $key) {
               return 'Keine Berechtigung';
          }
          $application = get_job_by_id_permissionless($aid);
          if(!$application){
               return 'Falsche Bewerbungs ID';
          }
          $uid = $application->user_id;
     }else{
          return 'Firma nicht gefunden';
     }
     $wp_user = get_user_by( 'id', $uid );
     $swpm_user = SwpmMemberUtils::get_user_by_email($wp_user->user_email);
     return SwpmMemberUtils::get_member_field_by_id($swpm_user->member_id, 'company_name');
}

function render_test_start() {
     // Überprüfe, ob die erforderlichen Parameter vorhanden sind
     if (isset($_GET['jid'], $_GET['key'])) {
          $jid = intval($_GET['jid']);
          $key = sanitize_text_field($_GET['key']);
          
          // Überprüfe den Hash
          if (commitment_hash($jid) == $key) {
               // Überprüfe, ob eine application_id übergeben wurde
               if (isset($_GET['aid'])) {
                    $application_id = intval($_GET['aid']);
                    // Lade die Application
                    $application = get_application_by_id_permissionless($application_id);
                    if(!$application){
                         return '<p>Bewerbung nicht gefunden.</p>';
                    }
               }
               $job = get_job_by_id_permissionless($jid);
               if(!$job){
                    return '<p>Stelle nicht gefunden.</p>';
               }
               // Fragendaten aus der Datenbank abrufen
               $questions = get_questions_by_test_id($job->test_id); // Hier müsstest du die entsprechende Funktion implementieren

               // Formular für die Antworten
               ob_start();
               include plugin_dir_path(__FILE__) . 'templates/commitment/test-page.php';
               return ob_get_clean();
          } else {
               return '<p>Ungültiger Zugriff.</p>';
          }
     } else {
          return '<p>Ungültige Anfrage.</p>';
     }
 }

// Funktion zum Abrufen aller aktiven Stellen aus der Datenbank
function get_active_jobs_by_user_id($user_id) {
         global $wpdb;

         // SQL-Abfrage, um alle aktiven Stellen abzurufen
         $query = $wpdb->prepare( "
             SELECT ID, job_title
             FROM {$wpdb->prefix}te_jobs
             WHERE user_id = %d
             AND state = 'active'
             ORDER BY added DESC
         ", $user_id );

         // Stellen abrufen
         $jobs = $wpdb->get_results( $query );

         return $jobs;
         
}

function render_commitment_test(){
     if(isset($_GET['key'])){
          $key = sanitize_text_field( $_GET['key'] );
          $link = '';
          if(isset($_GET['aid'])){
               $aid = intval( $_GET['aid'] );
               if(commitment_hash($aid) != $key){
                    return '<div class="alert alert-info" role="alert">Kein Zugriff</div>';
               }
               $application = get_application_by_id_permissionless($aid);
               if(!$application){
                    return '<div class="alert alert-info" role="alert">Bewerbung nicht gefunden</div>';
               }
               $jid = $application->job_id;
          }else if(isset($_GET['jid'])){
               $jid = intval( $_GET['jid'] );
               if(commitment_hash($jid) != $key){
                    return '<div class="alert alert-info" role="alert">Kein Zugriff</div>';
               }
          }else if(isset($_GET['uid'])){
               $uid = intval( $_GET['uid'] );
               if(commitment_hash($uid) != $key){
                    return '<div class="alert alert-info" role="alert">Kein Zugriff</div>';
               }
               $jobs = get_active_jobs_by_user_id($uid);
               if($jobs && count($jobs) == 1){
                    $jid = $jobs[0]->ID;
               }else{
                    $jid = 0;
                    foreach ( $jobs as $job ){
                         $job->hash = commitment_hash($job->ID);
                    }
                    ob_start();
                    include plugin_dir_path( __FILE__ ) . 'templates/commitment/select-job.php';
                    return ob_get_clean();
               }
          }
          $job = get_job_by_id_permissionless($jid);
          $test = get_test_by_id($job->test_id);
          // Baue den Link zusammen
          $link = esc_url( home_url( '/test-starten/?jid=' . $jid ));
          // Füge die Application ID hinzu, wenn verfügbar
          if (isset($aid)) {
               $link .= '&aid=' . $aid;
          }
          // Füge den Hash-Key hinzu
          $link .= '&key=' . commitment_hash($jid);
          ob_start();
          include plugin_dir_path( __FILE__ ) . 'templates/commitment/book-page.php';
          return ob_get_clean();
     }else{
          return '<div class="alert alert-info" role="alert">Kein Zugriff</div>';
     }
}

function render_consent_form(){
     if ( isset( $_GET['id'], $_GET['key'] ) ) {
     $application_id = intval( $_GET['id'] );
     $review_path = sanitize_text_field( $_GET['key'] );

     $application = get_application_by_id_permissionless($application_id);

     if ( $application ) {

          $review = get_review_by_id_permissionless($application->review_id);
          if($review_path == $review->filepath){
               $job = get_job_by_id_permissionless($application->job_id);
               // Tabelle aus Vorlagendatei einfügen
               ob_start();
               include plugin_dir_path( __FILE__ ) . 'templates/forms/consent-form.php';
               return ob_get_clean();
          }else{
               return '<div class="alert alert-info" role="alert">Kein Zugriff</div>';
          }

     } else {
          // Keine Bewerbungsdetails gefunden, Nachricht ausgeben
          return '<div class="alert alert-info" role="alert">Kein Zugriff</div>';
     }
     } else {
          // Keine ID-Parameter übergeben, Meldung ausgeben
          return '<div class="alert alert-warning" role="alert">Kein Zugriff</div>';
      }
}