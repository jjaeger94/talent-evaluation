<?php
// Include database connection file or open database connection

$backlogs = get_backlogs_by_application($application);

// Wenn Einträge vorhanden sind, zeigen Sie sie an
if (!empty($backlogs)) {
    foreach ($backlogs as $entry) {
        // Anzeige des Timestamps und Textes des Eintrags
        echo '<div>';
        echo '<p>' . $entry->added . '</p>';
        echo '<p>' . $entry->log . '</p>';
        echo '</div>';
    }
}

echo '<div>';
echo '<p>' . $application->added . '</p>';
echo '<p>Bewerbung angelegt</p>';
echo '</div>';

?>
