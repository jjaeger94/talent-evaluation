<?php    
     /**
     * Hier alle Shortcodes für Bewerber eintragen!
     */
function register_shortcodes_talents() {
        add_shortcode('chatbot_page', 'render_chatbot_page_content');
        add_shortcode('game_info', 'render_game_info');
        add_shortcode('start_game_button', 'render_start_btn');
        add_shortcode('get_game_image', 'render_game_image');
        add_shortcode('landing_page_talent', 'render_talent_page');
        add_shortcode('matching_talent', 'render_matching_page');
        add_shortcode('preferences_talent', 'render_preferences_page');
        add_shortcode('contact_talent', 'render_contact_page');
        add_shortcode('profile_talent', 'render_talent_page');
}

function render_contact_page(){
     ob_start();
     $auth = SwpmAuth::get_instance();
     if ($auth->is_logged_in()) {
          $member_id = SwpmMemberUtils::get_logged_in_members_id();
          // Überprüfen, ob Bewerbungsdetails vorhanden sind
          $talent = get_talent_by_member_id($member_id);
          // Überprüfen, ob das Talent gefunden wurde
          if ($talent) {               
               ob_start(); // Puffer starten
               include TE_DIR.'contact/contact.php'; // Pfad zur Datei mit dem Test-Formular
               return ob_get_clean();
          } else {
              // Talent nicht gefunden
              return '<p>ID nicht gefunden.</p>';
          }
     } else {
          include TE_DIR.'swpm/login.php';
     }
     return ob_get_clean();
}

function render_preferences_page(){
     ob_start();
     $auth = SwpmAuth::get_instance();
     if ($auth->is_logged_in()) {
          $member_id = SwpmMemberUtils::get_logged_in_members_id();
          // Überprüfen, ob Bewerbungsdetails vorhanden sind
          $talent = get_talent_by_member_id($member_id);
          // Überprüfen, ob das Talent gefunden wurde
          if ($talent) {
               $job_ids = [];
               $preference_job_ids = [];

               $jobs = get_demojobs();
               $jobs_by_id = [];
               foreach ($jobs as $job) {
                    $jobs_by_id[$job->ID] = $job;
               }

               // Extrahiere die IDs aus den Job-Objekten
               foreach ($jobs as $job) {
                    $job_ids[] = $job->ID;
               }
               $preferences = get_preferences_for_talent_id($talent->ID);
               // Extrahiere die job_ids aus den Preference-Objekten
               foreach ($preferences as $preference) {
                    $preference_job_ids[] = $preference->job_id;
               }
               $difference_ids = array_diff($job_ids, $preference_job_ids);
               
               ob_start(); // Puffer starten
               include TE_DIR.'details/talent-preferences-template.php'; // Pfad zur Datei mit dem Test-Formular
               return ob_get_clean();
          } else {
              // Talent nicht gefunden
              return '<p>ID nicht gefunden.</p>';
          }
     } else {
          include TE_DIR.'swpm/login.php';
     }
     return ob_get_clean();
}

function render_matching_page(){
     ob_start();
     $auth = SwpmAuth::get_instance();
     if ($auth->is_logged_in()) {
          $member_id = SwpmMemberUtils::get_logged_in_members_id();
          // Überprüfen, ob Bewerbungsdetails vorhanden sind
          $talent = get_talent_by_member_id($member_id);
          // Überprüfen, ob das Talent gefunden wurde
          if ($talent) {
               $matching = get_active_matching_for_talent_id($talent->ID);
              // Abfrage, um den Chatverlauf abzurufen
              ob_start(); // Puffer starten
              include TE_DIR.'details/talent-matching-template.php'; // Pfad zur Datei mit dem Test-Formular
              return ob_get_clean(); 
          } else {
              // Talent nicht gefunden
              return '<p>ID nicht gefunden.</p>';
          }
     } else {
          include TE_DIR.'swpm/login.php';
     }
     return ob_get_clean();
}

function render_talent_page(){
     ob_start();
     $auth = SwpmAuth::get_instance();
     if ($auth->is_logged_in()) {
          $member_id = SwpmMemberUtils::get_logged_in_members_id();
          // Überprüfen, ob Bewerbungsdetails vorhanden sind
          $talent = get_talent_by_member_id($member_id);
          // Überprüfen, ob das Talent gefunden wurde
          if ($talent) {
              // Abfrage, um den Chatverlauf abzurufen
              $apprenticeships = get_apprenticeships_by_talent_id($talent->ID);
              $studies = get_studies_by_talent_id($talent->ID);
              $experiences = get_experiences_by_talent_id($talent->ID);
              $eq = get_eq_by_talent_id($talent->ID);
              $resumes = get_uploaded_resumes_for_talent($talent->ID);
              $documents = get_uploaded_documents_for_talent($talent->ID);
              ob_start(); // Puffer starten
              include TE_DIR.'details/talent-profile-template.php'; // Pfad zur Datei mit dem Test-Formular
              return ob_get_clean(); 
          } else {
              // Talent nicht gefunden
              return '<p>ID nicht gefunden.</p>';
          }
     } else {
          include TE_DIR.'swpm/login.php';
     }
     return ob_get_clean();
}

function render_game_image(){
     $key = isset($_GET['game']) ? sanitize_text_field($_GET['game']) : 'burger';
     $game = get_game_by_key($key);
     if($game){
          return '<img src="' . $game->image_url . '" alt="Kundin">';
     }else{
          return '';
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
     $key = isset($_GET['game']) ? sanitize_text_field($_GET['game']) : 'burger';
     $game = get_game_by_key($key);
     if(!isset($game)){
          return 'Spiel nicht gefunden';
     }
     ob_start();
     echo $game->info_text;
     return ob_get_clean();
 }

function render_chatbot_page_content() {
     if(!isset($_GET['game'])){
          return do_shortcode('[insert page="1632" display="content"]');
     }
     $key = sanitize_text_field($_GET['game']);
     $game = get_game_by_key($key);
     if(!$game){
          return 'Game not found';
     }
     if(!isset($_SESSION['game'])){
          $_SESSION['game'] = $key;
     }
     $state = 'in_progress';
     $messages = [];
     if (isset($_SESSION['active_chat'])) {
          if($key != $_SESSION['game']){
               ob_start();
               echo '<p>Bitte beende erst dein letztes Spiel oder löschen den Chat</p>';
               include TE_DIR.'chatbot/delete-chat.php';
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
               }else if($game->type == 1 && isset($game->first_msg) && $game->first_msg !== ''){
                    //Bot first
                    if(add_message_to_thread($thread_id, $game->first_msg, 'assistant')){
                         $messages = list_messages_by_thread($thread_id);
                    }
               }else if($game->type == 2 && isset($game->first_msg) && $game->first_msg !== ''){
                    //User first
                    if(add_message_to_thread($thread_id, $game->first_msg)){
                         $messages = list_messages_by_thread($thread_id);
                    }
               }
         } else {
               // Wenn ein Fehler beim Abrufen der Nachrichten aufgetreten ist, handle den Fehler entsprechend
               return "Fehler beim Abrufen der Nachrichten";
         }
     } else {
          
          // Wenn kein aktiver Chat vorhanden ist, erstelle einen neuen Thread und speichere die Thread-ID in der Sitzung
          $thread_id = create_thread(); // Annahme: Funktion create_thread() erstellt einen neuen Thread und gibt die Thread-ID zurück
          if ($thread_id !== false) {
               $_SESSION['active_chat'] = $thread_id;
               if($game->type == 1 && isset($game->first_msg) && $game->first_msg !== ''){
                    //Bot first
                    if(add_message_to_thread($thread_id, $game->first_msg, 'assistant')){
                         $messages = list_messages_by_thread($thread_id);
                    }
               }else if($game->type == 2 && isset($game->first_msg) && $game->first_msg !== ''){
                    //User first
                    if(add_message_to_thread($thread_id, $game->first_msg)){
                         $messages = list_messages_by_thread($thread_id);
                    }
               }
          } else {
               // Wenn ein Fehler beim Erstellen des Threads aufgetreten ist, handle den Fehler entsprechend
               return "Fehler beim Erstellen des Threads";
          }
     }

     ob_start();
     include TE_DIR.'chatbot/chatbot-info.php';
     include TE_DIR.'chatbot/chatbot-page.php';
     return ob_get_clean();
 }