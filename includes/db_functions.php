<?php
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