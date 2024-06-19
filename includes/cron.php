<?php

function talent_evaluation_cronjob() {
    // Speichere die aktuelle Zeit
    $current_time = current_time('mysql');
    update_option('te_last_run', $current_time);

    // Ausgabe in der Konsole
    if (defined('WP_CLI') && WP_CLI) {
        WP_CLI::log('Cronjob wurde ausgeführt.');
        WP_CLI::log('Aktuelle Zeit: ' . $current_time);
    }
}

// Wenn WP-CLI verwendet wird
if (defined('WP_CLI') && WP_CLI) {
    WP_CLI::add_command('talents-update', 'talent_evaluation_cronjob');
}