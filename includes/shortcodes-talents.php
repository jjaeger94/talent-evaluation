<?php    
     /**
     * Hier alle Shortcodes für Bewerber eintragen!
     */
function register_shortcodes_talents() {
        add_shortcode( 'consent_form', 'render_consent_form' );
    }

function render_consent_form(){
     if ( isset( $_GET['id'] ) ) {
     $application_id = intval( $_GET['id'] );

     $application = get_application_by_id_permissionless($application_id);

     if ( $application ) {

          $job = get_job_by_id_permissionless($application->job_id);
          // Tabelle aus Vorlagendatei einfügen
          ob_start();
          include plugin_dir_path( __FILE__ ) . 'templates/forms/consent-form.php';
          return ob_get_clean();
     } else {
          // Keine Bewerbungsdetails gefunden, Nachricht ausgeben
          $output = '<div class="alert alert-info" role="alert">Es wurden keine Bewerbungsdetails gefunden.</div>';
     }
     } else {
          // Keine ID-Parameter übergeben, Meldung ausgeben
          return '<div class="alert alert-warning" role="alert">Es wurde keine Bewerbungs-ID angegeben.</div>';
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
     $job = $temp_db->get_results( $query );

     // Überprüfen, ob Jobdetails vorhanden sind
     return ! empty( $job ) ? $job[0] : null;
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
     $application = $temp_db->get_results( $query );

     // Überprüfen, ob Bewerbungsdetails vorhanden sind
     return ! empty( $application ) ? $application[0] : null;
}