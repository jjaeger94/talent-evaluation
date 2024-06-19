<?php
// Include the WordPress core

require_once(dirname(__FILE__).'/../../../wp-load.php');

function talent_evaluation_cronjob() {
    // Speichere die aktuelle Zeit
    $current_time = current_time('mysql');
    update_option('te_last_run', $current_time);
}

// Führe die Funktion aus
talent_evaluation_cronjob();
