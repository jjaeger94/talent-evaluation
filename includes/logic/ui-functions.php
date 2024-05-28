<?php
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
            return 'Kundendienst und Suppor';
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
