<?php    
     /**
     * Hier alle Shortcodes für Firmenkunden eintragen!
     */
    function register_shortcodes_firmenkunden() {
        add_shortcode('firmenkunden_page', 'firmenkunden_page_shortcode');
    }
    

    /**
     * Shortcode-Callback für die Firmenkunden-Seite
     *
     * @param array $atts Array von Attributen, die im Shortcode verwendet werden können.
     * @param string $content Der Inhalt innerhalb des Shortcodes, wenn der Shortcode als Paar verwendet wird.
     * @return string Der HTML-Inhalt der Dienstleister-Seite.
     */
    function firmenkunden_page_shortcode($atts, $content = null) {
        // Hier den Inhalt der Dienstleister-Seite einfügen
        return "Hier können Firmenkunden Bewerber hinzufügen und Dokumente hochladen.";
    }