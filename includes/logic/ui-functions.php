<?php
function get_customer_state($state){
    $states = [
        0 => 'Neu',
        1 => 'Angefragt',
        2 => 'Interessent',
        3 => 'Partner',
        4 => 'Abgelehnt',
    ];

    return isset($states[$state]) ? $states[$state] : 'Neu';
}

function get_job_state($state){
    $states = [
        0 => 'Inaktiv',
        1 => 'Aktiv'

    ];

    return isset($states[$state]) ? $states[$state] : 'Unbekannt';
}

function get_product_type($state){
    $states = [
        0 => 'Default'

    ];

    return isset($states[$state]) ? $states[$state] : 'Unbekannt';
}

function get_game_type($state){
    $states = [
        0 => 'Default',
        1 => 'Bot First',
        2 => 'User First'
    ];

    return isset($states[$state]) ? $states[$state] : 'Unbekannt';
}

function get_event_type($type) {
    $types = [
        1 => 'Registrierungserinnerung',
        2 => 'Registrierungsmail',
        3 => 'Offene Stellen',
        4 => 'Erstgespräch',
        5 => 'Dokument hochgeladen',
        6 => 'Benachrichtigungen angepasst'

    ];

    return isset($types[$type]) ? $types[$type] : 'Unbekannt';
}

function get_date_string($object){
    
    if ($object->end_date != '9999-12-31' && $object->end_date != '0000-00-00'){
        $dateString = date("d.m.Y", strtotime($object->start_date)) . '-' . date("d.m.Y", strtotime($object->end_date));
    }else{
        $dateString = 'seit ' . date("d.m.Y", strtotime($object->start_date));
    }

    return $dateString;
}
function get_display_name($user_id) {
    if ($user_id == 0)
        return 'System';
    if (!$user = get_userdata($user_id))
        return $user_id;
    return $user->data->display_name;
}
function requirements_match($requirements, $talent_requirements){
    $grouped_requirements = [];
    foreach ($requirements as $requirement) {
        $grouped_requirements[$requirement->type][] = $requirement;
    }
    foreach ($grouped_requirements as $type => $type_requirements) {
        if($type == 2 && isset($talent_requirements[2])){
            foreach ($type_requirements as $requirement) {
                foreach ($talent_requirements[2] as $talent_requirement) {
                    if(($requirement->field == $talent_requirement->field) && ($requirement->degree <= $talent_requirement->degree)){
                        return true;
                    }
                }
            }
        }else if(($type == 1 && isset($talent_requirements[1])) || ($type == 3 && isset($talent_requirements[3]))){
            foreach ($type_requirements as $requirement) {
                foreach ($talent_requirements[$type] as $talent_requirement) {
                    if($requirement->field == $talent_requirement->field){
                        return true;
                    }
                }
            }
        }
    }
    return false;
}

function get_talent_state($talent){
    if(!isset($talent)){
        return 'Error';
    }else if(!$talent->member_id){
        return 'Neu';
    }else if($talent->member_id){
        $username = SwpmMemberUtils::get_member_field_by_id($talent->member_id, 'user_name');
        if($username){
            if(get_active_matching_count_for_talent_id($talent->ID) > 0){
                return 'In bearbeitung';
            }else{
                return 'Registriert';
            }
            
        }else{
            return 'Warten';
        }
    }
    return 'Error';
}

function get_preference_state($type){
    $types = [
        0 => 'Nicht gesetzt',
        1 => 'Negativ',
        2 => 'Positiv'
    ];
    return isset($types[$type]) ? $types[$type] : 'Nicht gesetzt';
}

function get_matching_state($type) {
    $types = [
        0 => 'Vorgemerkt',
        1 => 'Unternehmen kontaktiert',
        2 => 'Profil verschickt',
        3 => 'Kennenlerngespräch angefordert',
        4 => 'Kennenlerngespräch geplant',
        5 => 'Vetrag unterschrieben',
        6 => 'Provision erhalten',
        99 => 'Abgelehnt'
    ];

    return isset($types[$type]) ? $types[$type] : 'Nicht gesetzt';
}

function get_english_level($type){
    $types = [
        0 => 'Nicht vorhanden',
        1 => 'Grundkenntnisse',
        2 => 'konversationsfähig',
        3 => 'fließend'
    ];

    return isset($types[$type]) ? $types[$type] : 'Nicht vorhanden';
}
function get_school_degree($type) {
    $types = [
        0 => 'Kein Abschluss',
        1 => 'Hauptschulabschluss',
        2 => 'Realschulabschluss und vergleichbare Schulabschlüsse',
        3 => 'Fachhochschulreife',
        4 => 'Abitur',
    ];

    return isset($types[$type]) ? $types[$type] : 'Kein Abschluss';
}

function get_mobility_label($type){
    $types = [
        0 => 'über 100 km',
        20 => 'bis 20 km',
        50 => 'bis 50 km',
        100 => 'bis 100 km'
    ];
    return isset($types[$type]) ? $types[$type] : 'über 100 km';
}

function get_type_label($type) {
    $types = [
        1 => 'Ausbildung',
        2 => 'Studium',
        3 => 'Berufserfahrung'
    ];
    return isset($types[$type]) ? $types[$type] : 'Unbekannt';
}

function get_field_label($type, $field) {
    if ($type == 1) {
        return get_apprenticeship_field($field);
    } elseif ($type == 2) {
        return get_study_field($field);
    } elseif ($type == 3) {
        return get_experience_field($field);
    }
    return 'Unbekannt';
}

function get_apprenticeship_field($field) {
    switch ($field) {
        case 1:
            return 'Gewerblich-technische Ausbildungsberufe';
        case 2:
            return 'Kaufmännische Ausbildungsberufe';
        case 3:
            return 'Sozialpädagogische und Gesundheitsberufe';
        case 4:
            return 'Informationstechnologie und Medien';
        case 5:
            return 'Handwerkliche Berufe';
        case 6:
            return 'Sonstige';
        // Weitere Fälle hinzufügen, falls erforderlich
        default:
            return '';
    }
}

function get_experience_field($field) {
    switch ($field) {
        case 1:
            return 'Geschäftsführung/Vorstand';
        case 2:
            return 'Vertrieb und Marketing';
        case 3:
            return 'Finanzen und Buchhaltung';
        case 4:
            return 'Personalwesen/Personalabteilung';
        case 5:
            return 'Produktion/Operations';
        case 6:
            return 'Forschung und Entwicklung';
        case 7:
            return 'Kundendienst und Support';
        case 8:
            return 'Informationstechnologie';
        case 9:
            return 'Sonstige';
        // Weitere Fälle hinzufügen, falls erforderlich
        default:
            return '';
    }
}

function get_study_field($field) {
    switch ($field) {
        case 1:
            return 'Informatik und Informationstechnologie';
        case 2:
            return 'Betriebswirtschaftslehre (BWL) und Management';
        case 3:
            return 'Gesundheitswissenschaften und Medizin';
        case 4:
            return 'Erziehungswissenschaften und Pädagogik';
        case 5:
            return 'Umweltwissenschaften und Nachhaltigkeit';
        case 6:
            return 'Design und Kreativwirtschaft';
        case 7:
            return 'Tourismus- und Eventmanagement';
        case 8:
            return 'Sozialwissenschaften und Soziale Arbeit';
        case 9:
            return 'Naturwissenschaften und Forschung';
        case 10:
            return 'Sonstige';
        // Weitere Fälle hinzufügen, falls erforderlich
        default:
            return '';
    }
}

function get_study_degree($degree) {
    switch ($degree) {
        case 1:
            return 'Kein Abschluss';
        case 2:
            return 'Bachelor';
        case 3:
            return 'Master';
        case 4:
            return 'Doktor';
        case 5:
            return 'Sonstige';
        // Weitere Fälle hinzufügen, falls erforderlich
        default:
            return '';
    }
}

function get_availability_string($availability){
    switch ($availability) {
        case 0:
            return 'Sofort';
        case 1:
            return 'in einem Monat';
        case 2:
            return 'in 2 Monaten';
        case 3:
            return 'in 3 Monaten';
        case 4:
            return 'in 4 Monaten';
        case 5:
            return 'in 5 Monaten';
        case 6:
            return 'in 6 Monaten';
        case 7:
            return 'Momentan nicht verfügbar';
        // Weitere Fälle hinzufügen, falls erforderlich
        default:
            return '';
    }
}
