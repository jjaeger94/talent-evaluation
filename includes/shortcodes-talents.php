<?php    
     /**
     * Hier alle Shortcodes für Bewerber eintragen!
     */
function register_shortcodes_talents() {
        add_shortcode('consent_form', 'render_consent_form');
        add_shortcode('commitment_test', 'render_commitment_test');
        add_shortcode('test_methode_btn', 'render_test_methode_btn');
        add_shortcode('book_link', 'get_book_link');
        add_shortcode('book_cover', 'get_book_cover');
        add_shortcode('book_title', 'get_book_title');
        add_shortcode('test_start', 'render_test_start');
        add_shortcode('get_company', 'get_company_shortcode');
        add_shortcode('chatbot_page', 'render_chatbot_page_content');
        add_shortcode('chatbot_page_info', 'render_chatbot_info');
        add_shortcode('game_info', 'render_game_info');
        add_shortcode('start_game_button', 'render_start_btn');
        add_shortcode('get_game_image_url', 'render_game_image');
}

function render_game_image(){
     $game = isset($_GET['game']) ? sanitize_text_field($_GET['game']) : 'burger';
     if($game == 'glasses'){
          return 'https://commitiq.de/wp-content/uploads/2024/05/sahra.jpeg';
     }else{
          return 'https://commitiq.de/wp-content/uploads/2024/05/Dieter_ohne_rand.png';
     }
}

function render_start_btn(){
     // Überprüfen, ob Parameter mitgegeben werden
     if (!empty($_SERVER['QUERY_STRING'])) {
         // URL zum Sales Game mit allen Übergabeparametern der aktuellen Seite
         $sales_game_url = esc_url(home_url('/sales-game/') . '?' . $_SERVER['QUERY_STRING']);
     } else {
         // Keine Parameter mitgegeben, nur die Basis-URL des Sales Games verwenden
         $sales_game_url = esc_url(home_url('/sales-game/'));
     }
 
     // Button-HTML erzeugen
     $button_html = '<a href="' . $sales_game_url . '" class="btn btn-primary btn-lg">Spiel starten</a>';
 
     // Button ausgeben
     return $button_html;
 }
 

 function render_game_info(){
     $game = isset($_GET['game']) ? sanitize_text_field($_GET['game']) : 'burger';

     ob_start();
     if($game == 'burger'){
          include plugin_dir_path(__FILE__) . 'templates/chatbot/burger/game-info.php';
     }else if($game == 'glasses'){
          include plugin_dir_path(__FILE__) . 'templates/chatbot/glasses/game-info.php';
     }
     return ob_get_clean();
 }

function render_chatbot_info(){
     if(!isset($_GET['game'])){
          return do_shortcode('[insert page="1632" display="content"]');
     }
     $game = sanitize_text_field($_GET['game']);
     ob_start();
     if($game == 'burger'){
          include plugin_dir_path(__FILE__) . 'templates/chatbot/burger/chatbot-info.php';
     }else if($game == 'glasses'){
          include plugin_dir_path(__FILE__) . 'templates/chatbot/glasses/chatbot-info.php';
     };
     return ob_get_clean();
}

function render_chatbot_page_content() {
     if(!isset($_GET['game'])){
          return "";
     }
     $game = sanitize_text_field($_GET['game']);
     if(!isset($_SESSION['game'])){
          $_SESSION['game'] = $game;
     }
     $state = 'in_progress';
     if (isset($_SESSION['active_chat'])) {
          if($game != $_SESSION['game']){
               ob_start();
               echo '<p>Bitte beende erst dein letztes Spiel oder löschen den Chat</p>';
               include plugin_dir_path(__FILE__) . 'templates/chatbot/delete-chat.php';
               return ob_get_clean();
          }
         // Wenn ein aktiver Chat vorhanden ist, hole die Thread-ID aus der Sitzung
         $thread_id = $_SESSION['active_chat'];
         // Nachrichten des Threads abrufen
         $messages = list_messages_by_thread($thread_id);
         if ($messages !== false) {
               if(!empty($messages)){
                    $parsedMessage = $messages[0]['content'][0]['text']['value'];
                    // Extrahiere den Status aus der Nachricht
                    if (strpos(strtolower($parsedMessage), 'bestanden') !== false) {
                         $state = 'success';
                    } elseif (strpos(strtolower($parsedMessage), 'durchgefallen') !== false) {
                         $state = 'failed';
                    } else {
                         $state = 'in_progress';
                    }
               }
               
               // Wenn die Nachrichten erfolgreich abgerufen wurden, tue etwas damit
               // Zum Beispiel die Nachrichten im Chat anzeigen
               ob_start();
               include plugin_dir_path(__FILE__) . 'templates/chatbot/chatbot-page.php';
               return ob_get_clean();
         } else {
               // Wenn ein Fehler beim Abrufen der Nachrichten aufgetreten ist, handle den Fehler entsprechend
               return "Fehler beim Abrufen der Nachrichten";
         }
     } else {
          $messages = [];
          // Wenn kein aktiver Chat vorhanden ist, erstelle einen neuen Thread und speichere die Thread-ID in der Sitzung
          $thread_id = create_thread(); // Annahme: Funktion create_thread() erstellt einen neuen Thread und gibt die Thread-ID zurück
          if ($thread_id !== false) {
               $_SESSION['active_chat'] = $thread_id;
               // Weiteren Code ausführen, z.B. Nachrichten des neuen Threads anzeigen oder andere Aktionen durchführen
               ob_start();
               include plugin_dir_path(__FILE__) . 'templates/chatbot/chatbot-page.php';
               return ob_get_clean();
          } else {
               // Wenn ein Fehler beim Erstellen des Threads aufgetreten ist, handle den Fehler entsprechend
               return "Fehler beim Erstellen des Threads";
          }
     }
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

function check_test_params(){
     $params = ['jid' => 0, 'aid' => 0, 'error' => ''];
     if(isset($_GET['key'])){
         $key = sanitize_text_field( $_GET['key'] );
         if(isset($_GET['aid'])){
             $aid = intval( $_GET['aid'] );
             if(commitment_hash($aid) != $key){
                 $params['error'] = 'Kein Zugriff';
                 return $params;
             }
             $application = get_application_by_id_permissionless($aid);
             if(!$application){
                 $params['error'] = 'Bewerbung nicht gefunden';
                 return $params;
             }
             $params['aid'] = $aid;
             $params['jid'] = $application->job_id;
         }else if(isset($_GET['jid'])){
             $jid = intval( $_GET['jid'] );
             if(commitment_hash($jid) != $key){
                 $params['error'] = 'Kein Zugriff';
                 return $params;
             }
             $params['jid'] = $jid;
         }else{
             $params['error'] = 'Kein Zugriff';
             return $params;
         }
     }else{
         $params['error'] = 'Kein Zugriff';
         return $params;
     }
     return $params;
 }

 function get_book_link(){
     $params = check_test_params();
     if($params['error']){
         return '<div class="alert alert-info" role="alert">'.$params['error'].'</div>';
     }
     $jid = $params['jid'];
     $aid = $params['aid'];
     $job = get_job_by_id_permissionless($jid);
     $test = get_test_by_id_permissionless($job->test_id);
     // Baue den Link zusammen
     ob_start(); // Starte die Pufferung
     // HTML-Code hier
     echo '<li><a href="' . esc_url( add_query_arg( array(
          'link' => $test->affiliate_link,
      ), home_url('/pdf-viewer-page') ) ) . '" target="_blank">Fachartikel öffnen</a></li>';
      return ob_get_clean(); 
}

 function get_book_title(){
     $params = check_test_params();
     if($params['error']){
         return '<div class="alert alert-info" role="alert">'.$params['error'].'</div>';
     }
     $jid = $params['jid'];
     $aid = $params['aid'];
     $job = get_job_by_id_permissionless($jid);
     $test = get_test_by_id_permissionless($job->test_id);
     // Baue den Link zusammen
     return $test->book_title;
 } 
 
 function get_book_cover(){
     $params = check_test_params();
     if($params['error']){
         return '<div class="alert alert-info" role="alert">'.$params['error'].'</div>';
     }
     $jid = $params['jid'];
     $aid = $params['aid'];
     $job = get_job_by_id_permissionless($jid);
     $test = get_test_by_id_permissionless($job->test_id);
     ob_start();
     include plugin_dir_path( __FILE__ ) . 'templates/commitment/book-page-cover.php';
     return ob_get_clean();
 } 

 function render_test_methode_btn(){
     $params = check_test_params();
     if($params['error']){
         return '<div class="alert alert-info" role="alert">'.$params['error'].'</div>';
     }
     $jid = $params['jid'];
     $aid = $params['aid'];
     $job = get_job_by_id_permissionless($jid);
     $test = get_test_by_id_permissionless($job->test_id);
     // Baue den Link zusammen
     $link = esc_url( home_url( '/test-starten/?jid=' . $jid ));
     // Füge die Application ID hinzu, wenn verfügbar
     if ($aid) {
         $link .= '&aid=' . $aid;
     }
     // Füge den Hash-Key hinzu
     $link .= '&key=' . commitment_hash($jid);
     ob_start();
     include plugin_dir_path( __FILE__ ) . 'templates/commitment/book-page-btn.php';
     return ob_get_clean();
 } 

function render_commitment_test(){
     if(isset($_GET['key'])){
          $key = sanitize_text_field( $_GET['key'] );
          $jid = 0;
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
               if(!$jobs && count($jobs) == 1){
                    $jid = $jobs[0]->ID;
               }else{
                    foreach ( $jobs as $job ){
                         $job->hash = commitment_hash($job->ID);
                    }
                    ob_start();
                    include plugin_dir_path( __FILE__ ) . 'templates/commitment/select-job.php';
                    return ob_get_clean();
               }
          }else{
               return '<div class="alert alert-info" role="alert">Kein Zugriff</div>';
          }
          $job = get_job_by_id_permissionless($jid);
          ob_start();
          include plugin_dir_path( __FILE__ ) . 'templates/commitment/selected-job.php';
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