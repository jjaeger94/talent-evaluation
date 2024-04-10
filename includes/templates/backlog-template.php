<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Datum und Uhrzeit</th>
                <th>Log Eintrag</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Include database connection file or open database connection

            $backlogs = get_backlogs_by_application($application);

            // Wenn Einträge vorhanden sind, zeigen Sie sie an
            if (!empty($backlogs)) {
                foreach ($backlogs as $entry) {
                    echo '<tr>';
                    echo '<td>' . $entry->added . '</td>';
                    echo '<td>' . $entry->log . '</td>';
                    echo '</tr>';
                }
            }

            // Anzeige des Erstellungsdatums der Bewerbung
            echo '<tr>';
            echo '<td>' . $application->added . '</td>';
            echo '<td>Bewerbung angelegt</td>';
            echo '</tr>';
            ?>
        </tbody>
    </table>
</div>
