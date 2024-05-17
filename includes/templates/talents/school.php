<?php
// Funktion zur Umwandlung der Abschlusszahl in Text
function get_degree_text($degree) {
    switch ($degree) {
        case '0':
            return 'Kein Abschluss';
        case '1':
            return 'Hauptschulabschluss';
        case '2':
            return 'Realschulabschluss und vergleichbare Schulabschlüsse';
        case '3':
            return 'Fachhochschulreife';
        case '4':
            return 'Abitur';
        // Weitere Fälle hinzufügen, falls erforderlich
        default:
            return '';
    }
}
?>
<div class="card mb-3">
    <div class="card-body">
        <div class="row">
        <div class="col-md-12 mb-3">
        <!-- Schulische Ausbildung anzeigen -->
        <?php if ($school): ?>
            <!-- Wenn Schuldaten vorhanden sind, zeige sie an -->
            <div class="col">
                <p><strong>Abschluss:</strong> <?php echo get_degree_text($school->degree); ?></p>
            </div>
        <?php endif;?>
            <!-- Wenn keine Schuldaten vorhanden sind, zeige Button zum Hinzufügen -->
            <div class="col">
                <button type="button" class="btn btn-primary" id="addSchoolBtnOpen" >Schulabschluss auswählen</button>
            </div>
        </div>
        </div>
    </div>
</div>
<!-- Modal zum Hinzufügen einer neuen Schule -->
<div class="modal fade" id="addSchoolModal" tabindex="-1" role="dialog" aria-labelledby="addSchoolModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSchoolModalLabel">Schulabschluss hinzufügen/ändern</h5>
            </div>
            <form id="addSchoolForm">
                <div class="modal-body">
                    <!-- Verstecktes Eingabefeld für user_id -->
                    <input type="hidden" name="user_id" value="<?php echo $talent->ID; ?>">
                    <?php if ($school): ?>
                        <!-- Wenn Schuldaten vorhanden sind, verstecktes Eingabefeld für ID einfügen -->
                        <input type="hidden" name="school_id" value="<?php echo $school->ID; ?>">
                    <?php endif; ?>
                    <div class="form-group">
                        <label for="degree">Abschluss:</label>
                        <select class="form-control" id="degree" name="degree" required>
                            <?php for ($i = 0; $i <= 4; $i++) : ?>
                                <?php $selectedSchoolDegree = ($school && $school->degree == $i) ? 'selected' : ''; ?>
                                <option value="<?php echo $i; ?>" <?php echo $selectedSchoolDegree; ?>><?php echo get_degree_text($i); ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="addSchoolBtnClose">Schließen</button>
                    <button type="submit" class="btn btn-primary">Hinzufügen/Ändern</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
jQuery(document).ready(function($) {
    $('#addSchoolBtnOpen').click(()=>{
        $('#addSchoolModal').modal('show');
    });
    $('#addSchoolBtnClose').click(()=>{
        $('#addSchoolModal').modal('hide');
    });

    // AJAX-Anfrage zum Hinzufügen einer neuen Schule
    $('#addSchoolForm').submit(function(e) {
        e.preventDefault(); // Verhindert das Standardformulareinreichungsverhalten

        // Formulardaten sammeln
        var formData = $(this).serialize();

        // AJAX-Anfrage senden
        $.ajax({
            type: 'POST',
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: formData + '&action=add_school',
            success: function(response) {
                // Erfolgreiche Verarbeitung
                console.log(response);
                // Hier können Sie je nach Bedarf weitere Aktionen ausführen
                // Z.B. Modal schließen, Seite aktualisieren usw.
                $('#addSchoolModal').modal('hide');
                // Seite neu laden, um die aktualisierten Daten anzuzeigen
                if(response.success){
                    location.reload();
                }
            },
            error: function(xhr, status, error) {
                // Fehlerbehandlung
                console.error(error);
            }
        });
    });
});
</script>