<?php    
     /**
     * Hier alle Shortcodes für Bewerber eintragen!
     */
function register_shortcodes_talents() {
        add_shortcode( 'consent_form', 'render_consent_form' );
    }

function render_consent_form(){
     if ( isset( $_GET['id'] ) && isset( $_GET['key'] ) ) {
     $application_id = intval( $_GET['id'] );
     $review_path = sanitize_text_field( $_GET['key'] );

     $application = get_application_by_id_permissionless($application_id);

     if ( $application ) {

          $review = get_review_by_id_permissionless($application->review_id);
          if($review_path == $review->filepath){
               $job = get_job_by_id_permissionless($application->job_id);
               // Tabelle aus Vorlagendatei einfügen
               ob_start();
               include plugin_dir_path( __FILE__ ) . 'templates/forms/consent-form.php';
               return ob_get_clean();
          }else{
               return '<div class="alert alert-info" role="alert">Kein Zugriff</div>';
          }

     } else {
          // Keine Bewerbungsdetails gefunden, Nachricht ausgeben
          return '<div class="alert alert-info" role="alert">Kein Zugriff</div>';
     }
     } else {
          // Keine ID-Parameter übergeben, Meldung ausgeben
          return '<div class="alert alert-warning" role="alert">Kein Zugriff</div>';
      }
}

function get_job_by_id_permissionless($job_id){
     $temp_db = open_database_connection();

     // SQL-Abfrage, um die Jobdetails abzurufen
     $query = $temp_db->prepare( "
          SELECT *
          FROM {$temp_db->prefix}jobs
          WHERE ID = %d
     ", $job_id );

     // Jobdetails abrufen
     $jobs = $temp_db->get_results( $query );

     // Überprüfen, ob Jobdetails vorhanden sind
     return ! empty( $jobs ) ? $jobs[0] : null;
}

function get_review_by_id_permissionless($review_id){
     $temp_db = open_database_connection();

     // SQL-Abfrage, um die Jobdetails abzurufen
     $query = $temp_db->prepare( "
          SELECT *
          FROM {$temp_db->prefix}reviews
          WHERE ID = %d
     ", $review_id );

     // Jobdetails abrufen
     $reviews = $temp_db->get_results( $query );

     // Überprüfen, ob Jobdetails vorhanden sind
     return ! empty( $reviews ) ? $reviews[0] : null;
}

function get_application_by_id_permissionless($application_id){
     // Datenbankverbindung öffnen
     $temp_db = open_database_connection();

     // SQL-Abfrage, um die Bewerbungsdetails abzurufen
     $query = $temp_db->prepare( "
         SELECT *
         FROM {$temp_db->prefix}applications
         WHERE ID = %d
     ", $application_id );

     // Bewerbungsdetails abrufen
     $applications = $temp_db->get_results( $query );

     // Überprüfen, ob Bewerbungsdetails vorhanden sind
     return ! empty( $applications ) ? $applications[0] : null;
}