<?php    
     /**
     * Hier alle Shortcodes für Bewerber eintragen!
     */
function register_shortcodes_talents() {
        add_shortcode( 'consent_form', 'render_consent_form' );
    }

function render_consent_form(){
     ob_start();
     include plugin_dir_path( __FILE__ ) . 'templates/forms/consent-form.php';
     return ob_get_clean();
}