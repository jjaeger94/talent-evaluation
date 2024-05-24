<?php
include TE_DIR.'forms/customer-form.php';
if(!isset($_GET['add']) || $_GET['add'] == false):
global $wpdb;
$jobs_table = $wpdb->prefix . 'te_jobs';
$jobs = $wpdb->get_results($wpdb->prepare(
    "SELECT * FROM $jobs_table WHERE customer_id = %d ORDER BY added DESC",
    $id
));
include TE_DIR.'tables/jobs-table-template.php';
endif;
?>