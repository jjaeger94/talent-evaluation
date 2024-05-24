<?php if(!isset($_GET['add']) || $_GET['add'] == false): ?>
    <button class="btn btn-primary mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#jobInfoCollapse" aria-expanded="false" aria-controls="jobInfoCollapse">
    Job Infos bearbeiten
</button>
<div class="collapse" id="jobInfoCollapse">
    <div class="card card-body">
    <?php include TE_DIR.'forms/job-form.php';?>
    </div>
</div>
<br>
<?php
global $wpdb;
$requirements_table = $wpdb->prefix . 'te_requirements';
$requirements = $wpdb->get_results($wpdb->prepare(
    "SELECT * FROM $requirements_table WHERE job_id = %d ORDER BY added DESC",
    $job->ID
));
?>
<button class="btn btn-primary mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#requirementCollapse" aria-expanded="false" aria-controls="requirementCollapse">
    Anforderung hinzufügen/bearbeiten
</button>
<div class="collapse" id="requirementCollapse">
    <div class="card card-body">
        <?php include TE_DIR.'filters/requirements-list.php'; ?>
    </div>
</div>
<br>
<?php
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
?>
<button class="btn btn-primary mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#tableCollapse" aria-expanded="false" aria-controls="tableCollapse">
Liste anzeigen
</button>
<div class="collapse" id="tableCollapse">
    <div class="card card-body">
        <?php include TE_DIR.'tables/talents-table-template.php';?>
    </div>
</div>
 <?php    
else:
include TE_DIR.'forms/job-form.php';
endif;
?>