<?php    
     /**
     * Hier alle Shortcodes für Bewerber eintragen!
     */
function register_shortcodes_talents() {
        add_shortcode('chatbot_page', 'render_chatbot_page_content');
        add_shortcode('chatbot_page_info', 'render_chatbot_info');
        add_shortcode('game_info', 'render_game_info');
        add_shortcode('start_game_button', 'render_start_btn');
        add_shortcode('get_game_image', 'render_game_image');
        add_shortcode('landing_page_talent', 'render_talent_page');
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
              ob_start(); // Puffer starten
              include_once('details/talent-detail-template.php'); // Pfad zur Datei mit dem Test-Formular
              return ob_get_clean(); 
          } else {
              // Talent nicht gefunden
              return '<p>ID nicht gefunden.</p>';
          }
     } else {
          include plugin_dir_path(__FILE__) . 'swpm/login.php';
     }
     return ob_get_clean();
}

function render_game_image(){
     $game = isset($_GET['game']) ? sanitize_text_field($_GET['game']) : 'burger';
     if($game == 'glasses'){
          return '<img src="' . home_url('/wp-content/uploads/2024/05/sahra.jpeg') . '" alt="Kundin">';
     }else{
          return '<img src="' . home_url('/wp-content/uploads/2024/05/Dieter_ohne_rand.') . '" alt="Kundin">';
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
          include plugin_dir_path(__FILE__) . 'chatbot/burger/game-info.php';
     }else if($game == 'glasses'){
          include plugin_dir_path(__FILE__) . 'chatbot/glasses/game-info.php';
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
          include plugin_dir_path(__FILE__) . 'chatbot/burger/chatbot-info.php';
     }else if($game == 'glasses'){
          include plugin_dir_path(__FILE__) . 'chatbot/glasses/chatbot-info.php';
     };
     return ob_get_clean();
}

function render_chatbot_page_content() {
     if(!isset($_GET['game'])){
          return do_shortcode('[insert page="1632" display="content"]');
     }
     $game = sanitize_text_field($_GET['game']);
     if(!isset($_SESSION['game'])){
          $_SESSION['game'] = $game;
     }
     $state = 'in_progress';
     $messages = [];
     if (isset($_SESSION['active_chat'])) {
          if($game != $_SESSION['game']){
               ob_start();
               echo '<p>Bitte beende erst dein letztes Spiel oder löschen den Chat</p>';
               include plugin_dir_path(__FILE__) . 'chatbot/delete-chat.php';
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
         } else {
               // Wenn ein Fehler beim Abrufen der Nachrichten aufgetreten ist, handle den Fehler entsprechend
               return "Fehler beim Abrufen der Nachrichten";
         }
     } else {
          
          // Wenn kein aktiver Chat vorhanden ist, erstelle einen neuen Thread und speichere die Thread-ID in der Sitzung
          $thread_id = create_thread(); // Annahme: Funktion create_thread() erstellt einen neuen Thread und gibt die Thread-ID zurück
          if ($thread_id !== false) {
               $_SESSION['active_chat'] = $thread_id;
          } else {
               // Wenn ein Fehler beim Erstellen des Threads aufgetreten ist, handle den Fehler entsprechend
               return "Fehler beim Erstellen des Threads";
          }
     }

     ob_start();
     if($game == 'burger'){
          include plugin_dir_path(__FILE__) . 'chatbot/burger/chatbot-info.php';
     }else if($game == 'glasses'){
          include plugin_dir_path(__FILE__) . 'chatbot/glasses/chatbot-info.php';
     };
     include plugin_dir_path(__FILE__) . 'chatbot/chatbot-page.php';
     return ob_get_clean();
 }