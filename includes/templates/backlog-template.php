<div class="table-responsive">
    <table class="table table-striped">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Datum und Uhrzeit</th>
                <th scope="col">Ereignis</th>
                <th scope="col">Kommentar</th>
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
                    echo '<td>' . $entry->comment . '</td>';
                    echo '</tr>';
                }
            }

            // Anzeige des Erstellungsdatums der Bewerbung
            echo '<tr>';
            echo '<td>' . $application->added . '</td>';
            echo '<td>Bewerbung angelegt</td>';
            echo '<td></td>';
            echo '</tr>';
            ?>
        </tbody>
    </table>
</div>
