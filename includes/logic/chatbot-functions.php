<?php    
     /**
     * Helper Fuktionen für den chat
     */

     function create_thread() {
        // API-Endpunkt für die Thread-Erstellung
        $endpoint = 'https://api.openai.com/v1/threads';
    
        // API-Schlüssel und Assistant ID aus den Plugin-Optionen abrufen
        $api_key = get_option('te_api_key');
    
        // Überprüfen, ob der API-Schlüssel und die Assistant ID vorhanden sind
        if (empty($api_key)) {
            return false;
        }
    
        // Setzen Sie die Header für die cURL-Anfrage
        $headers = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $api_key,
            'OpenAI-Beta: assistants=v2'
        );
    
        // Daten für die cURL-Anfrage
        $data = json_encode(array());
    
        // cURL-Optionen setzen
        $options = array(
            CURLOPT_URL => $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => $headers,
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
            // Erfolgreich erstellt, geben Sie die Thread-ID zurück
            $data = json_decode($response, true);
            return $data['id'];
        } else {
            // Fehler beim Erstellen des Threads
            return false;
        }
    }

    function add_message_to_thread($thread_id, $message_content) {
        // API-Endpunkt für die Nachrichtenhinzufügung
        $endpoint = 'https://api.openai.com/v1/threads/' . urlencode($thread_id) . '/messages';
    
        // API-Schlüssel und Assistant ID aus den Plugin-Optionen abrufen
        $api_key = get_option('te_api_key');
    
        // Überprüfen, ob der API-Schlüssel und die Assistant ID vorhanden sind
        if (empty($api_key)) {
            return false;
        }
    
        // Setzen Sie die Header für die cURL-Anfrage
        $headers = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $api_key,
            'OpenAI-Beta: assistants=v2'
        );
    
        // Daten für die cURL-Anfrage
        $data = json_encode(array(
            'role' => 'user',
            'content' => $message_content
        ));
    
        // cURL-Optionen setzen
        $options = array(
            CURLOPT_URL => $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => $headers,
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
            // Erfolgreich hinzugefügt
            return true;
        } else {
            // Fehler beim Hinzufügen der Nachricht
            return false;
        }
    }

    function run_thread($thread_id) {
        // API-Endpunkt für das Ausführen eines Threads
        $runs_endpoint = 'https://api.openai.com/v1/threads/' . $thread_id . '/runs';
    
        $api_key = get_option('te_api_key');
        $assistant_id = get_assistant_id();
    
        // Überprüfen, ob der API-Schlüssel und die Assistant ID vorhanden sind
        if (empty($api_key) || empty($assistant_id)) {
            return false;
        }
    
        // Setzen Sie die Header für die cURL-Anfrage
        $headers = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $api_key,
            'OpenAI-Beta: assistants=v2'
        );
    
        // Daten für die cURL-Anfrage
        $data = json_encode(array(
            'assistant_id' => $assistant_id
        ));
    
        // cURL-Optionen setzen
        $options = array(
            CURLOPT_URL => $runs_endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => $headers,
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
            
            $timeout = 60;
            $start_time = time();

            while(in_array($response_data['status'], ['queued', 'in_progress', 'cancelling'])){
                if (time() - $start_time >= $timeout) {
                    // Timeout erreicht, brechen Sie die Schleife ab
                    break;
                }
                // Kurz warten, bevor der Status überprüft wird
                sleep(3); // Zum Beispiel 5 Sekunden warten
                                
                // Den Status des Threads überwachen
                $response_data = poll_run_status($thread_id, $response_data['id']);
            }
            
            // Überprüfen, ob der Status des Threads "queued", "in_progress" oder "cancelling" ist
            if ($response_data['status'] == 'completed') {
                // Wenn der Status "completed" ist, fordern Sie die letzte Nachricht im Thread an
                return $response_data;
            } else {
                // Wenn der Status unbekannt ist oder ein anderer ist, geben Sie false zurück
                return false;
            }
        } else {
            // Fehler beim Ausführen des Threads
            return false;
        }
    }    

    function poll_run_status($thread_id, $run_id) {
        // API-Endpunkt für das Abfragen des Status eines Threads
        $run_endpoint = 'https://api.openai.com/v1/threads/' . $thread_id . '/runs/' . $run_id;
    
        // API-Schlüssel aus den Plugin-Optionen abrufen
        $api_key = get_option('te_api_key');
    
        // Überprüfen, ob der API-Schlüssel vorhanden ist
        if (empty($api_key)) {
            return false;
        }
    
        // Setzen Sie die Header für die cURL-Anfrage
        $headers = array(
            'Authorization: Bearer ' . $api_key,
            'OpenAI-Beta: assistants=v2'
        );
    
        // cURL-Optionen setzen
        $options = array(
            CURLOPT_URL => $run_endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
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
            return json_decode($response, true);
        } else {
            // Fehler beim Abfragen des Status des Threads
            return false;
        }
    }
    
    function get_message_by_id($thread_id, $message_id) {
        // API-Endpunkt für das Abrufen einer Nachricht
        $message_endpoint = 'https://api.openai.com/v1/threads/' . $thread_id . '/messages/' . $message_id;
    
        $api_key = get_option('te_api_key');
        $assistant_id = get_assistant_id();
    
        // Überprüfen, ob der API-Schlüssel und die Assistant ID vorhanden sind
        if (empty($api_key) || empty($assistant_id)) {
            return false;
        }
    
        // Setzen Sie die Header für die cURL-Anfrage
        $headers = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $api_key,
            'OpenAI-Beta: assistants=v2'
        );
    
        // cURL-Optionen setzen
        $options = array(
            CURLOPT_URL => $message_endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
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
            // Erfolgreich ausgeführt, geben Sie die Antwort zurück
            return json_decode($response, true);
        } else {
            // Fehler beim Abrufen der Nachricht
            return false;
        }
    }
    
    function get_message_from_run($run_object) {
        // Überprüfen, ob das übergebene Objekt ein gültiges Run-Objekt ist
        if (!isset($run_object['id'])) {
            return false;
        }

        $run_id = $run_object['id'];
    
        $thread_id = $run_object['thread_id'];
    
        // Überprüfen, ob der Thread-ID vorhanden ist
        if (empty($thread_id)) {
            return false;
        }
    
        // Überprüfen, ob der Run-Status "completed" ist
        if ($run_object['status'] != 'completed') {
            return false;
        }
    
        $steps = get_run_steps_by_id($thread_id, $run_id);
        
        if(!$steps){
            return false;
        }

        $message_id = get_message_id_from_run_steps($steps);
        // Nachricht aus dem Run abrufen
        return get_message_by_id($thread_id, $message_id);
    }

    function list_messages_by_thread($thread_id) {
        // API-Endpunkt für das Abrufen von Nachrichten eines Threads
        $messages_endpoint = 'https://api.openai.com/v1/threads/' . $thread_id . '/messages';
    
        $api_key = get_option('te_api_key');
    
        // Überprüfen, ob der API-Schlüssel vorhanden ist
        if (empty($api_key)) {
            return false;
        }
    
        // Setzen Sie die Header für die cURL-Anfrage
        $headers = array(
            'Authorization: Bearer ' . $api_key,
            'OpenAI-Beta: assistants=v2'
        );
    
        // cURL-Optionen setzen
        $options = array(
            CURLOPT_URL => $messages_endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
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
            return $response_data['data']; // Nachrichten aus der Antwort zurückgeben
        } else {
            // Fehler beim Abrufen der Nachrichten
            return false;
        }
    }

    function delete_thread($thread_id) {
        // API-Endpunkt für das Löschen eines Threads
        $delete_endpoint = 'https://api.openai.com/v1/threads/' . $thread_id;
    
        $api_key = get_option('te_api_key');
    
        // Überprüfen, ob der API-Schlüssel vorhanden ist
        if (empty($api_key)) {
            return false;
        }
    
        // Setzen Sie die Header für die cURL-Anfrage
        $headers = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $api_key,
            'OpenAI-Beta: assistants=v2'
        );
    
        // cURL-Optionen setzen
        $options = array(
            CURLOPT_URL => $delete_endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CUSTOMREQUEST => 'DELETE',
            CURLOPT_HTTPHEADER => $headers,
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
            // Erfolgreich gelöscht
            return true;
        } else {
            // Fehler beim Löschen des Threads
            return false;
        }
    }  

    function get_run_steps_by_id($thread_id, $run_id) {
        $api_key = get_option('te_api_key');
    
        // Überprüfen, ob der API-Schlüssel vorhanden ist
        if (empty($api_key)) {
            return false;
        }
    
        // API-Endpunkt für den Abruf der Schritte eines bestimmten Laufs
        $endpoint = 'https://api.openai.com/v1/threads/' . $thread_id . '/runs/' . $run_id . '/steps';
    
        // Setzen Sie die Header für die cURL-Anfrage
        $headers = array(
            'Authorization: Bearer ' . $api_key,
            'Content-Type: application/json',
            'OpenAI-Beta: assistants=v2'
        );
    
        // cURL-Optionen setzen
        $options = array(
            CURLOPT_URL => $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
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
            // Erfolgreich: JSON-Daten zurückgeben
            return json_decode($response, true);
        } else {
            // Fehler: false zurückgeben
            return false;
        }
    }

    function get_message_id_from_run_steps($run_steps) {
        $message_ids = array();
    
        // Überprüfen, ob Daten vorhanden sind
        if (!empty($run_steps['data'])) {
            // Durchlaufe die Schritte
            foreach ($run_steps['data'] as $step) {
                // Überprüfen, ob der Schritt ein Nachrichtenerstellungs-Schritt ist
                if ($step['type'] === 'message_creation') {
                    // Extrahiere die Nachrichten-ID und füge sie zum Array hinzu
                    $message_ids[] = $step['step_details']['message_creation']['message_id'];
                }
            }
        }
    
        return !empty( $message_ids ) ? $message_ids[0] : null;
    }
    
    function parseMessageFromObject($message_object) {
        // Überprüfen, ob das übergebene Objekt existiert und ein Inhalt hat
        if (isset($message_object['content']) && !empty($message_object['content'])) {
            // Extrahiere den Wert unter content->value
            $value = $message_object['content'][0]['text']['value'];
            return $value;
        } else {
            // Wenn kein Inhalt vorhanden ist, gib eine Fehlermeldung zurück
            return 'No content found in the message object.';
        }
    }

    function get_assistant_id() {
        if(!$_SESSION['game']){
            return false;
        }
        $game = $_SESSION['game'];
        if($game == 'burger'){
            return 'asst_3MWRUUDVcZR8zRe5DjmYAqJD';
        }else if($game == 'glasses'){
            return 'asst_n5KxIqgqswb4ZV7HSvaIgZsg';
        }
        return false;
    }
    
    function transcribe_audio($file_path, $type) {
        // API-Schlüssel und Assistant ID aus den Plugin-Optionen abrufen
        $api_key = get_option('te_api_key');
    
        $ch = curl_init();
    
        curl_setopt($ch, CURLOPT_URL, "https://api.openai.com/v1/audio/transcriptions");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer $api_key",
            "Content-Type: multipart/form-data"
        ));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array(
            'file' => new CURLFile($file_path, 'audio/'.$type, 'audio.'.$type),
            'model' => 'whisper-1'
        ));
        $response = curl_exec($ch);
    
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            throw new Exception('cURL error: ' . $error_msg);
        }
    
        curl_close($ch);
    
        $result = json_decode($response, true);
    
        if (isset($result['text'])) {
            return $result['text'];
        } else {
            throw new Exception('Error in transcription response: ' . $response);
        }
    }