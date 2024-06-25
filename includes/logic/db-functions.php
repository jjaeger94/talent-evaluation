<?php
function get_file_by_id($id){
    global $wpdb;
    $query = $wpdb->prepare( "
        SELECT *
        FROM {$wpdb->prefix}te_resumes
        WHERE ID = {$id}
    ");
    // Bewerbungsdetails abrufen
    return $wpdb->get_row( $query );
}
function get_uploaded_resumes_for_talent($talent_id) {
    global $wpdb;
    $resumes_table = $wpdb->prefix . 'te_resumes';
    $results = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM $resumes_table WHERE talent_id = %d",
        $talent_id
    ), ARRAY_A);
    return $results;
}

function save_resume_path($talent_id, $filename) {
    global $wpdb;
    $resumes_table = $wpdb->prefix . 'te_resumes';

    $wpdb->insert(
        $resumes_table,
        [
            'talent_id' => $talent_id,
            'file' => $filename
        ],
        [
            '%d',
            '%s'
        ]
    );
}

function change_job_state($job, $value){
    global $wpdb;
    $table_name = $wpdb->prefix . 'te_jobs';

    // Daten zum Aktualisieren
    $data = array(
        'state' => $value,
    );

    // Bedingung für die Aktualisierung
    $where = array('ID' => $job->ID);

    // Aktualisieren der Daten in der Datenbank
    $wpdb->update($table_name, $data, $where);
}

function remove_expired_job($job){
    global $wpdb;
    $wpdb->delete(
        $wpdb->prefix . 'te_requirements',
        array('job_id' => $job->ID),
        array('%d')
    );
    $wpdb->delete(
        $wpdb->prefix . 'te_matching',
        array('job_id' => $job->ID),
        array('%d')
    );
    $wpdb->delete(
        $wpdb->prefix . 'te_events',
        array('job_id' => $job->ID),
        array('%d')
    );
    $wpdb->delete(
        $wpdb->prefix . 'te_jobs',
        array('ID' => $job->ID),
        array('%d')
    );
}

function remove_unregistered_talent($talent){
    global $wpdb;
    $wpdb->delete(
        $wpdb->prefix . 'te_events',
        array('talent_id' => $talent->ID),
        array('%d')
    );
    if($talent->member_id){
        $wpdb->delete(
            $wpdb->prefix . 'swpm_members_tbl',
            array('member_id' => $talent->member_id),
            array('%d')
        );
    }
    $wpdb->delete(
        $wpdb->prefix . 'te_talents',
        array('ID' => $talent->ID),
        array('%d')
    );
}
function get_members_with_empty_user_name() {
    global $wpdb;

    // Name der Tabelle
    $table_name = $wpdb->prefix . 'swpm_members_tbl';

    // SQL-Abfrage, um alle Mitglieder zu finden, bei denen user_name leer ist
    $query = "SELECT * FROM $table_name WHERE user_name = ''";

    // Ergebnisse abrufen
    $members = $wpdb->get_results($query);

    return $members;
}
function convert_encoding($string){
    return utf8_decode(stripslashes_deep($string));
}
function get_talent_events($talent_id) {
    global $wpdb;
    return $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}te_events WHERE talent_id = %d ORDER BY added DESC",
        $talent_id
    ));
}
function get_job_events($job_id) {
    global $wpdb;
    return $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}te_events WHERE job_id = %d ORDER BY added DESC",
        $job_id
    ));
}
function get_matching_events($matching_id) {
    global $wpdb;
    return $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}te_events WHERE matching_id = %d ORDER BY added DESC",
        $matching_id
    ));
}

function log_event($event_type, $event_description, $talent_id = null, $job_id = null, $matching_id = null) {
    $user_id = get_current_user_id();
    global $wpdb;
    $wpdb->insert(
        $wpdb->prefix . 'te_events',
        [
            'event_type' => $event_type,
            'event_description' => $event_description,
            'talent_id' => $talent_id,
            'job_id' => $job_id,
            'matching_id' => $matching_id,
            'user_id' => $user_id
        ],
        [
            '%d',
            '%s',
            '%d',
            '%d',
            '%d',
            '%d'
        ]
    );
}

function get_active_matching_for_talent_id($talent_id){
    global $wpdb;
    $matching_table = $wpdb->prefix . 'te_matching';
    $jobs_table = $wpdb->prefix . 'te_jobs';

    $query = $wpdb->prepare("
        SELECT m.*
        FROM {$matching_table} m
        INNER JOIN {$jobs_table} j ON m.job_id = j.ID
        WHERE m.talent_id = %d
        AND m.value = 0
        AND j.state = 1
    ", $talent_id);

    return $wpdb->get_results($query);
}

function get_positive_matching_count_for_job_id($job_id){
    global $wpdb;
    $matching_table = $wpdb->prefix . 'te_matching';
    $jobs_table = $wpdb->prefix . 'te_jobs';

    $query = $wpdb->prepare("
        SELECT COUNT(*)
        FROM {$matching_table} m
        INNER JOIN {$jobs_table} j ON m.job_id = j.ID
        WHERE m.job_id = %d
        AND m.value = 2
        AND j.state = 1
    ", $job_id);
    return $wpdb->get_var($query);
}

function get_active_matching_count_for_talent_id($talent_id){
    global $wpdb;
    $matching_table = $wpdb->prefix . 'te_matching';
    $jobs_table = $wpdb->prefix . 'te_jobs';

    $query = $wpdb->prepare("
        SELECT COUNT(*)
        FROM {$matching_table} m
        INNER JOIN {$jobs_table} j ON m.job_id = j.ID
        WHERE m.talent_id = %d
        AND m.value = 0
        AND j.state = 1
    ", $talent_id);
    return $wpdb->get_var($query);
}

function get_matching_for_ids($talent_id, $job_id){
    global $wpdb;
    $table_name = $wpdb->prefix . 'te_matching';
    return $wpdb->get_row($wpdb->prepare("SELECT * FROM {$table_name} WHERE talent_id = %d AND job_id = %d", $talent_id, $job_id));
}
function get_all_customers(){
    global $wpdb;
    return $wpdb->get_results("SELECT ID, company_name FROM {$wpdb->prefix}te_customers");
}
function get_talents_for_job($job, $requirements = null){
    global $wpdb;
    $talents_table = $wpdb->prefix . 'te_talents';

    // Grundabfrage
    $query = "SELECT * FROM $talents_table WHERE member_id > 0 AND school >= %d";
    //AND availability <= %d

    // Parameter für die Abfrage
    $params = array($job->school);

    // Zusätzliche Bedingungen für license und home_office
    if ($job->license) {
        $query .= " AND license = %d";
        $params[] = $job->license;
    }

    // if (!$job->home_office) {
    //     $query .= " AND home_office = %d";
    //     $params[] = $job->home_office;
    // }

    // if (!$job->part_time) {
    //     $query .= " AND part_time = %d";
    //     $params[] = $job->part_time;
    // }

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
        
        foreach ($results as $result){
            if(SwpmMemberUtils::get_member_field_by_id($result->member_id, 'user_name')){
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
            }
        }
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
    $query = "SELECT * FROM $jobs_table WHERE school <= %d";
    //AND availability >= %d

    // Parameter für die Abfrage
    $params = array($talent->school);

    // Zusätzliche Bedingungen für license und home_office
    if (!$talent->license) {
        $query .= " AND license = %d";
        $params[] = $talent->license;
    }

    // if ($talent->home_office) {
    //     $query .= " AND home_office = %d";
    //     $params[] = $talent->home_office;
    // }

    // if ($talent->part_time) {
    //     $query .= " AND part_time = %d";
    //     $params[] = $talent->part_time;
    // }

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
            if($talent->mobility == 0){
                array_push($jobs, $job);
            }else if($talent->mobility == 20){
                if(in_array($job->post_code, $postal_codes_20)){
                    array_push($jobs, $job);
                }
            }else if($talent->mobility == 50){
                if(in_array($job->post_code, $postal_codes_50)){
                    array_push($jobs, $job);
                }
            }else if($talent->mobility == 100){
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
    return $wpdb->get_row( $query );
}

function get_talent_by_id($talent_id){
    global $wpdb;
    $query = $wpdb->prepare( "
        SELECT *
        FROM {$wpdb->prefix}te_talents
        WHERE ID = {$talent_id}
    ");
    // Bewerbungsdetails abrufen
    return $wpdb->get_row( $query );
}

function get_customer_by_id($customer_id){
    global $wpdb;
    $query = $wpdb->prepare( "
        SELECT *
        FROM {$wpdb->prefix}te_customers
        WHERE ID = {$customer_id}
    ");
    // Bewerbungsdetails abrufen
    return $wpdb->get_row( $query );
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
    return $wpdb->get_row( $query );
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
    global $wpdb;

    // SQL-Abfrage, um die Jobdetails abzurufen
    $query = $wpdb->prepare( "
        SELECT *
        FROM {$wpdb->prefix}te_jobs
        WHERE ID = %d
    ", $job_id );

    // Jobdetails abrufen
    return $wpdb->get_row( $query );
}