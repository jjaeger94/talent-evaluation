<?php
// Include the WordPress core

// Check if a StepStone job page is available
function is_stepstone_job_available($url) {
    $response = wp_remote_get($url);
    if (is_wp_error($response)) {
        return false;
    }
    $body = wp_remote_retrieve_body($response);
    // Überprüfen des Inhalts.
    return strpos($body, 'Stellenanzeige ist nicht mehr verfügbar') === false;
}

// Check if a Indeed job page is available
function is_indeed_job_available($url) {
    $response = wp_remote_get($url);
    if (is_wp_error($response)) {
        return false;
    }
    $body = wp_remote_retrieve_body($response);
    // Überprüfen des Inhalts.
    return strpos($body, 'Diese Stellenanzeige ist auf Indeed abgelaufen') === false;
}




require_once(dirname(__FILE__).'/../../../wp-load.php');

function delete_unavailable_jobs() {
    // Schritt 1: Alle Jobs aus der Datenbank holen
    $jobs = get_all_jobs(1);
    
    foreach ($jobs as $job) {
        // Schritt 2: Link überprüfen
        $url = $job->link;
        
        // Schritt 3: Überprüfen ob es ein StepStone-Link ist
        if (strpos($url, 'stepstone.de') !== false) {
            // StepStone-Seite aufrufen und Inhalt prüfen
            if (!is_stepstone_job_available($url)) {
                // Job Deaktivieren wenn die Seite nicht verfügbar ist
                change_job_state($job, 0);
            }
        } else if (strpos($url, 'indeed') !== false) {
            // Indeed-Links überspringen
            if (!is_indeed_job_available($url)) {
                // Job Deaktivieren wenn die Seite nicht verfügbar ist
                change_job_state($job, 0);
            }
        }
    }
}

function register_users(){
    $unregistered_users = get_members_with_empty_user_name();

    foreach ($unregistered_users as $user) {
        $talent = get_talent_by_member_id($user->member_id);
        if ($talent) {
            $events = get_talent_events($talent->ID);
            $event_type_1_found = false;
            $event_type_2 = NULL;

            foreach ($events as $event) {
                if ($event->event_type == 1) {
                    $event_type_1_found = true;
                    $event_time = strtotime($event->added);
                    if (time() - $event_time > 2 * 7 * 24 * 60 * 60) { // länger als 2 Wochen
                        remove_unregistered_talent($talent);
                        continue 2; // springt zur nächsten Schleifeniteration des äußeren Loops
                    }
                }
                if ($event->event_type == 2) {
                    $event_type_2 = $event;
                }
            }

            if (!$event_type_1_found && $event_type_2) {
                $event_time = strtotime($event_type_2->added);
                if (time() - $event_time > 7 * 24 * 60 * 60) { // länger als eine Woche
                    send_register_again($talent);
                }
            }
        }
    }
}

function talent_evaluation_cronjob() {
    register_users();
    delete_unavailable_jobs();

    // Speichere die aktuelle Zeit
    $current_time = current_time('mysql');
    update_option('te_last_run', $current_time);
}

// Führe die Funktion aus
talent_evaluation_cronjob();
