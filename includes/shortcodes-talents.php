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