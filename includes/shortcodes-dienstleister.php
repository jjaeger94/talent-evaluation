<?php
    /**
     * Hier alle Shortcodes für Dienstleister eintragen!
     */
    function register_shortcodes_dienstleister() {
        add_shortcode('dienstleister_page', 'dienstleister_page_shortcode');
        add_shortcode( 'show_tasks', 'show_tasks_table' );
    }

    /**
     * Shortcode-Callback für die Dienstleister-Seite
     *
     * @param array $atts Array von Attributen, die im Shortcode verwendet werden können.
     * @param string $content Der Inhalt innerhalb des Shortcodes, wenn der Shortcode als Paar verwendet wird.
     * @return string Der HTML-Inhalt der Firmenkunden-Seite.
     */
    function dienstleister_page_shortcode($atts, $content = null) {
        // Hier den Inhalt der Firmenkunden-Seite einfügen
        return "Hier können Dienstleister Bewerber auflisten und Scores vergeben.";
}

    // Kurzer Shortcode zum Anzeigen der Kandidatentabelle
    function show_tasks_table() {
        // Überprüfen, ob der Benutzer eingeloggt ist
        if ( current_user_can( 'dienstleister' ) ) {
            // Erfassen Sie die in den Optionen gespeicherten Daten
            $temp_db = open_database_connection();

            // SQL-Abfrage, um Kandidaten des aktuellen Benutzers abzurufen
            $query = $temp_db->prepare( "
                SELECT ID, email, state, added, edited
                FROM {$temp_db->prefix}applications
                ORDER BY added DESC
            " );
    
            // Stellen abrufen
            $tasks = $temp_db->get_results( $query );
    
            // Überprüfen, ob Jobs vorhanden sind
            if ( $tasks ) {
                // Tabelle aus Vorlagendatei einfügen
                ob_start();
                include plugin_dir_path( __FILE__ ) . 'templates/tsks-table-template.php';
                $output = ob_get_clean();
            } else {
                // Keine Jobs gefunden, Nachricht ausgeben
                $output = '<div class="alert alert-info" role="alert">Es wurden keine Aufgaben gefunden.</div>';
            }
    
            return $output;
        } else {
            return 'Bitte loggen Sie sich ein, um Ihre Aufgaben zu sehen.';
        }
    }