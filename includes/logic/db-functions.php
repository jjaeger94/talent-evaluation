<?php
function get_matching_for_ids($talent_id, $job_id){
    global $wpdb;
    $table_name = $wpdb->prefix . 'te_matching';
    return  $wpdb->get_row($wpdb->prepare("SELECT * FROM {$table_name} WHERE talent_id = %d AND job_id = %d", $talent_id, $job_id));
}
function get_all_customers(){
    global $wpdb;
    return $wpdb->get_results("SELECT ID, company_name FROM {$wpdb->prefix}te_customers");
}
function get_talents_for_job($job, $requirements = null){
    global $wpdb;
    $talents_table = $wpdb->prefix . 'te_talents';

    // Grundabfrage
    $query = "SELECT * FROM $talents_table WHERE member_id > 0 AND school >= %d AND availability <= %d";

    // Parameter für die Abfrage
    $params = array($job->school, $job->availability);

    // Zusätzliche Bedingungen für license und home_office
    if ($job->license) {
        $query .= " AND license = %d";
        $params[] = $job->license;
    }

    if (!$job->home_office) {
        $query .= " AND home_office = %d";
        $params[] = $job->home_office;
    }

    // Gruppierung der Anforderungen nach Typ

    if ($requirements) {
        $grouped_requirements = [];
        foreach ($requirements as $requirement) {
            $grouped_requirements[$requirement->type][] = $requirement;
        }
        $all_requirement_filters = [];
        // Filtern der Talente basierend auf den gruppierten Anforderungen
        foreach ($grouped_requirements as $type => $type_requirements) {
            $type_requirement_filters = [];
            $table_name = get_table_name($type); // Tabellenname für die Anforderung
            $requirement_filter = "EXISTS (
            SELECT * FROM $table_name AS requirements
            WHERE requirements.talent_id = $talents_table.ID";

            foreach ($type_requirements as $requirement) {
                    $type_filter= "(requirements.field = %d";
                    
                    $params[] = $requirement->field;
                
                // Falls der Typ "Studien" ist, füge die Überprüfung für den Abschluss hinzu
                if ($type == 2) {
                    $type_filter .=  " AND requirements.degree >= %d";
                    $params[] = $requirement->degree;
                }
                $type_filter .=  ")";
                array_push($type_requirement_filters, $type_filter);
            }
            // Füge die Anforderungsfilter für diesen Typ zur Hauptabfrage hinzu
            if (!empty($type_requirement_filters)) {
                $requirement_filter .= " AND (" . implode(" OR ", $type_requirement_filters) . ")";
                $requirement_filter .= ")";
            }
            array_push($all_requirement_filters, $requirement_filter);
        }
        // Füge die Anforderungsfilter für diesen Typ zur Hauptabfrage hinzu
        if (!empty($all_requirement_filters)) {
            $query .= " AND (" . implode(" OR ", $all_requirement_filters) . ")";
        }   
    }

    // Abfrage vorbereiten
    $query .= " ORDER BY added DESC";
    $prepared_query = $wpdb->prepare($query, ...$params);

    // Talente abrufen
    $results = $wpdb->get_results($prepared_query);
    $talents = [];

    if(!empty($results)){
        $countryCode = 'DE'; // Deutschland
        $postal_codes_20 = getPostalCodesInRadius($job->post_code, 20, $countryCode);
        $postal_codes_50 = getPostalCodesInRadius($job->post_code, 50, $countryCode);
        $postal_codes_100 = getPostalCodesInRadius($job->post_code, 100, $countryCode);
        
        foreach ($results as $result) :
            if($result->mobility == 0){
                array_push($talents, $result);
            }else if($result->mobility == 20){
                if(in_array($result->post_code, $postal_codes_20)){
                    array_push($talents, $result);
                }
            }else if($result->mobility == 50){
                if(in_array($result->post_code, $postal_codes_50)){
                    array_push($talents, $result);
                }
            }else if($result->mobility == 100){
                if(in_array($result->post_code, $postal_codes_100)){
                    array_push($talents, $result);
                }
            }
        endforeach;
    }

    return $talents;
}

function get_jobs_for_talent($talent, $apprenticeships = null, $studies = null, $experiences = null){
    global $wpdb;
    $apprenticeships = $apprenticeships ? $apprenticeships : get_apprenticeships_by_talent_id($talent->ID);
    $studies = $studies ? $studies : get_studies_by_talent_id($talent->ID);
    $experiences = $experiences ? $experiences :get_experiences_by_talent_id($talent->ID);
    $jobs_table = $wpdb->prefix . 'te_jobs';

    // Grundabfrage
    $query = "SELECT * FROM $jobs_table WHERE school <= %d AND availability >= %d";

    // Parameter für die Abfrage
    $params = array($talent->school, $talent->availability);

    // Zusätzliche Bedingungen für license und home_office
    if (!$talent->license) {
        $query .= " AND license = %d";
        $params[] = $talent->license;
    }

    if ($talent->home_office) {
        $query .= " AND home_office = %d";
        $params[] = $talent->home_office;
    }

    // Abfrage vorbereiten
    $query .= " ORDER BY added DESC";
    $prepared_query = $wpdb->prepare($query, ...$params);

    // Talente abrufen
    $basic = $wpdb->get_results($prepared_query);
    
    $unfiltered = [];
    if(!empty($basic)){
        $requirements_table = $wpdb->prefix . 'te_requirements';
        $talent_requirements = [];
        $talent_requirements[1] = $apprenticeships;
        $talent_requirements[2] = $studies;
        $talent_requirements[3] = $experiences;
        foreach ($basic as $job) :
            $requirements = $wpdb->get_results($wpdb->prepare(
                "SELECT * FROM $requirements_table WHERE job_id = %d ORDER BY added DESC",
                $job->ID
            ));
            if(empty($requirements) || requirements_match($requirements, $talent_requirements)){
                array_push($unfiltered, $job);
            }
        endforeach;
    }

    $jobs = [];

    if(!empty($unfiltered)){
        $countryCode = 'DE'; // Deutschland
        $postal_codes_20 = getPostalCodesInRadius($talent->post_code, 20, $countryCode);
        $postal_codes_50 = getPostalCodesInRadius($talent->post_code, 50, $countryCode);
        $postal_codes_100 = getPostalCodesInRadius($talent->post_code, 100, $countryCode);
        
        foreach ($unfiltered as $job) :
            if($job->mobility == 0){
                array_push($jobs, $job);
            }else if($job->mobility == 20){
                if(in_array($job->post_code, $postal_codes_20)){
                    array_push($jobs, $job);
                }
            }else if($job->mobility == 50){
                if(in_array($result->post_code, $postal_codes_50)){
                    array_push($jobs, $job);
                }
            }else if($job->mobility == 100){
                if(in_array($job->post_code, $postal_codes_100)){
                    array_push($jobs, $job);
                }
            }
        endforeach;
    }

    return $jobs;
}

function get_requirements_for_job_id($job_id) {
    global $wpdb;
    $requirements_table = $wpdb->prefix . 'te_requirements';
    return $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM $requirements_table WHERE job_id = %d ORDER BY added DESC",
        $job_id
    ));

}

function get_table_name($type) {
    global $wpdb;
    $types = [
        1 => $wpdb->prefix . 'te_apprenticeship',
        2 => $wpdb->prefix . 'te_studies',
        3 => $wpdb->prefix . 'te_experiences'
    ];
    return isset($types[$type]) ? $types[$type] : null;
}

function get_talent_by_member_id($member_id){
    global $wpdb;
    $query = $wpdb->prepare( "
        SELECT *
        FROM {$wpdb->prefix}te_talents
        WHERE member_id = {$member_id}
    ");
    // Bewerbungsdetails abrufen
    $talents = $wpdb->get_results( $query );

    // Überprüfen, ob Bewerbungsdetails vorhanden sind
    return ! empty( $talents ) ? $talents[0] : null;
}

function get_talent_by_id($talent_id){
    if ( current_user_can( 'dienstleister' ) ) {
        global $wpdb;
        $query = $wpdb->prepare( "
            SELECT *
            FROM {$wpdb->prefix}te_talents
            WHERE ID = {$talent_id}
        ");
        // Bewerbungsdetails abrufen
        $talents = $wpdb->get_results( $query );

        // Überprüfen, ob Bewerbungsdetails vorhanden sind
        return ! empty( $talents ) ? $talents[0] : null;
    } else {
        return null;
    }
}

function get_customer_by_id($customer_id){
    if ( current_user_can( 'dienstleister' ) ) {
        global $wpdb;
        $query = $wpdb->prepare( "
            SELECT *
            FROM {$wpdb->prefix}te_customers
            WHERE ID = {$customer_id}
        ");
        // Bewerbungsdetails abrufen
        $customers = $wpdb->get_results( $query );

        // Überprüfen, ob Bewerbungsdetails vorhanden sind
        return ! empty( $customers ) ? $customers[0] : null;
    } else {
        return null;
    }
}

function get_apprenticeships_by_talent_id($talent_id){
    global $wpdb;

    // SQL-Abfrage, um die Jobdetails abzurufen
    $query = $wpdb->prepare( "
        SELECT *
        FROM {$wpdb->prefix}te_apprenticeship
        WHERE talent_id = {$talent_id}
    ");

    // Jobdetails abrufen
    return $wpdb->get_results( $query );
}

function get_eq_by_talent_id($talent_id){
    global $wpdb;

    // SQL-Abfrage, um die Jobdetails abzurufen
    $query = $wpdb->prepare( "
        SELECT *
        FROM {$wpdb->prefix}te_eq
        WHERE talent_id = {$talent_id}
    ");

    // Jobdetails abrufen
    $eq = $wpdb->get_results( $query );

    // Überprüfen, ob Jobdetails vorhanden sind
    return ! empty( $eq ) ? $eq[0] : null;
}

function get_experiences_by_talent_id($talent_id){
    global $wpdb;

    // SQL-Abfrage, um die Jobdetails abzurufen
    $query = $wpdb->prepare( "
        SELECT *
        FROM {$wpdb->prefix}te_experiences
        WHERE talent_id = {$talent_id}
    ");

    // Jobdetails abrufen
    return $wpdb->get_results( $query );
}

function get_studies_by_talent_id($talent_id){
    global $wpdb;

    // SQL-Abfrage, um die Jobdetails abzurufen
    $query = $wpdb->prepare( "
        SELECT *
        FROM {$wpdb->prefix}te_studies
        WHERE talent_id = {$talent_id}
    ");

    // Jobdetails abrufen
    return $wpdb->get_results( $query );
}

function get_job_by_id( $job_id ) {
    if ( current_user_can( 'dienstleister' ) ) {
        global $wpdb;

        // SQL-Abfrage, um die Jobdetails abzurufen
        $query = $wpdb->prepare( "
            SELECT *
            FROM {$wpdb->prefix}te_jobs
            WHERE ID = %d
        ", $job_id );

        // Jobdetails abrufen
        $jobs = $wpdb->get_results( $query );

        // Überprüfen, ob Jobdetails vorhanden sind
        return ! empty( $jobs ) ? $jobs[0] : null;
    }else{
        return null;
    }
}