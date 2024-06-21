<?php
function send_register_again($talent){
    $settings = SwpmSettings::get_instance();
    $link_for = 'one';
    $send_email = false;
    $links = SwpmUtils::get_registration_complete_prompt_link($link_for, $send_email, $talent->member_id);
    $registration_link = $links[0];
    
    // Setze den Betreff und die Absender-Adresse der E-Mail
    $subject = 'Willkommen bei Convii';
    
    $from_address = $settings->get_value('email-from');
    $headers = 'From: ' . $from_address . "\r\n";
    
    // Starte die Ausgabe-Pufferung und inkludieren das E-Mail-Template
    ob_start();
    include TE_DIR . 'mails/register_again.php';
    $message = ob_get_clean();
    
    // Sende die E-Mail
    wp_mail($talent->email, $subject, $message, $headers);
    log_event(1, 'Email für Registrierung wurde erneut verschickt', $talent->ID);
    return $registration_link;
}

function send_missed_call($talent, $new_member){
    //Nutze andere email vorlage
    // Setze den Betreff und die Absender-Adresse der E-Mail
    $subject = 'Willkommen bei Convii';
    $from_address = $settings->get_value('email-from');
    $headers = 'From: ' . $from_address . "\r\n";
    
    // Starte die Ausgabe-Pufferung und inkludieren das E-Mail-Template
    ob_start();
    include TE_DIR . 'mails/register_missed_call.php';
    $message = ob_get_clean();
    
    // Sende die E-Mail
    wp_mail($talent->email, $subject, $message, $headers);
    log_event(2, 'Email mit Nachricht zum Erstgespräch und Registrierungslink wurde verschickt', $talent->ID);
}

function send_new_job_mail($talent, $count){
    if($count > 0){
        // Setze den Betreff und die Absender-Adresse der E-Mail
        $subject = 'Neue Stellen';
        $settings = SwpmSettings::get_instance();
        $from_address = $settings->get_value('email-from');
        $headers = 'From: ' . $from_address . "\r\n";
        
        // Starte die Ausgabe-Pufferung und inkludieren das E-Mail-Template
        ob_start();
        include TE_DIR . 'mails/new_job_mail.php';
        $message = ob_get_clean();
        
        // Sende die E-Mail
        wp_mail($talent->email, $subject, $message, $headers);
        log_event(3, 'Mail mit '.$count.' offenen Stellen wurde gesendet', $talent->ID);
    }
}

function send_consultation_mail($talent){
    $admin_email = get_option('admin_email');
    // Setze den Betreff und die Absender-Adresse der E-Mail
    $subject = 'Neue Gesprächsanfrage';
    $settings = SwpmSettings::get_instance();
    $from_address = $settings->get_value('email-from');
    $headers = 'From: ' . $from_address . "\r\n";
    
    // Starte die Ausgabe-Pufferung und inkludieren das E-Mail-Template
    ob_start();
    include TE_DIR . 'mails/request_consultation_mail.php';
    $message = ob_get_clean();
    
    // Sende die E-Mail
    wp_mail($admin_email, $subject, $message, $headers);
    log_event(4, 'Talent hat beim Matching Erstgespräch angefordert', $talent->ID);
}