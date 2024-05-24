<?php
include TE_DIR.'forms/job-form.php';
if(!isset($_GET['add']) || $_GET['add'] == false):
?><br><?php
global $wpdb;
$requirements_table = $wpdb->prefix . 'te_requirements';
$requirements = $wpdb->get_results($wpdb->prepare(
    "SELECT * FROM $requirements_table WHERE job_id = %d ORDER BY added DESC",
    $job->ID
));
include TE_DIR.'filters/requirements-list.php';
?><br><?php
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

    $countryCode = 'DE'; // Deutschland
    $postal_codes_20 = getPostalCodesInRadius($job->post_code, 20, $countryCode);
    $postal_codes_50 = getPostalCodesInRadius($job->post_code, 50, $countryCode);
    $postal_codes_100 = getPostalCodesInRadius($job->post_code, 100, $countryCode);

    // Abfrage vorbereiten
    $query .= " ORDER BY added DESC";
    $prepared_query = $wpdb->prepare($query, ...$params);

    // Talente abrufen
    $results = $wpdb->get_results($prepared_query);
    $talents = [];
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

    include TE_DIR.'tables/talents-table-template.php';
endif;
?>