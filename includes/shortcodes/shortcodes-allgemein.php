<?php    
     /**
     * Hier alle Shortcodes für Alle eintragen!
     */
    function register_shortcodes_allgemein() {
        add_shortcode('logout_button', 'render_logout_button');
        add_shortcode( 'application_button', 'render_application_button' );
        add_shortcode( 'footer_button', 'render_footer_button' );
        add_shortcode( 'unsubscribe', 'render_unsubscribe' );
    }

    function render_unsubscribe(){
        $email = isset($_GET['email']) ? sanitize_text_field($_GET['email']) : null;
        if($email){
            $talent = get_talent_by_email($email);
            if($talent){
                ob_start();
                include TE_DIR.'mails/unsubscribe.php';
                return ob_get_clean();
            } 
        }
        return "<p> Ungültige Anfrage </p>";
    }

    function render_application_button() {
        if ( SwpmMemberUtils::is_member_logged_in() || is_user_logged_in()) {
            $user = wp_get_current_user();
            $button_text = 'Zum Login Bereich';
            $button_url = get_user_home_url($user); // Anpassen Sie die URL entsprechend Ihrer Seitenstruktur    
            $output = '<a class="btn btn-primary" href="' . esc_url( $button_url ) . '">' . esc_html( $button_text ) . '</a>';
        } else {
            $login_url = home_url('/membership-login'); // Login-URL für den Fall, dass der Benutzer nicht eingeloggt ist
            $output = '<a class="btn btn-primary" href="' . esc_url( $login_url ) . '">Jetzt einloggen</a>';
        }
    
        return $output;
    }
    
    function render_footer_button(){
        if ( SwpmMemberUtils::is_member_logged_in() || is_user_logged_in()) {
            $output = render_logout_button();
        } else {
            $login_url = home_url('/membership-login'); // Login-URL für den Fall, dass der Benutzer nicht eingeloggt ist
            $output = '<a href="' . esc_url( $login_url ) . '">Jetzt einloggen</a>';
        }
    
        return $output;
    }

    function render_logout_button() {
        if ( SwpmMemberUtils::is_member_logged_in()) {
            $output = '<div class="swpm-logged-logout-link"><a href="?swpm-logout=true">Logout</a></div>';
        }else if(is_user_logged_in()){
            $output = '<div class="swpm-logged-logout-link"><a href="'.wp_logout_url().'">Logout</a></div>';
        }
        return $output;
    }