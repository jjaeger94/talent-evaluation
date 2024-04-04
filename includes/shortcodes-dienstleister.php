<?php
    /**
     * Hier alle Shortcodes für Dienstleister eintragen!
     */
    public function register_shortcodes_dienstleister() {
        add_shortcode('dienstleister_page', 'dienstleister_page_shortcode');
    }

    /**
     * Shortcode-Callback für die Dienstleister-Seite
     *
     * @param array $atts Array von Attributen, die im Shortcode verwendet werden können.
     * @param string $content Der Inhalt innerhalb des Shortcodes, wenn der Shortcode als Paar verwendet wird.
     * @return string Der HTML-Inhalt der Firmenkunden-Seite.
     */
    public function dienstleister_page_shortcode($atts, $content = null) {
        // Hier den Inhalt der Firmenkunden-Seite einfügen
        return "Hier können Dienstleister Bewerber auflisten und Scores vergeben.";
}