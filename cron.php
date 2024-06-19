<?php
// Include the WordPress core

require_once(dirname(__FILE__).'/../../../wp-load.php');

function talent_evaluation_cronjob() {
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

    // Speichere die aktuelle Zeit
    $current_time = current_time('mysql');
    update_option('te_last_run', $current_time);
}

// Führe die Funktion aus
talent_evaluation_cronjob();
