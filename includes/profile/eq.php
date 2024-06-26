<?php
/**
 * Template zur Anzeige von EQ-Fragen
 *
 * Verfügbar sind folgende Variablen:
 * - $talent: Das Talent-Objekt
 * - $eq_questions: Ein Array von EQ-Fragen
 */
?>

<?php if ($talent) : ?>
<div class="row mb-3">
    <?php if ($eq) : ?>
        <div class="col-md-12 mb-3">
            <p class="card-title"><strong>Was ist dir im Beruf besonders wichtig?</strong></p>
            <p class="card-text"><?php echo $eq->value; ?></p>
            <button class="btn btn-primary edit-eq" data-id="<?php echo $eq->ID; ?>" data-value="<?php echo $eq->value; ?>">Antwort bearbeiten</button>
        </div>
    <?php else : ?>
        <div class="col-md-12 mb-3">
            <p class="card-title"><strong>Was ist dir im Beruf besonders wichtig?</strong></p>
        </div>
        <div class="col">
        <button class="btn btn-primary" id="addEqBtnOpen">Antwort hinzufügen</button>
        </div>
    <?php endif; ?>
</div>

    <!-- Modal zum Bearbeiten einer EQ-Frage -->
    <div class="modal fade" id="editEqModal" tabindex="-1" role="dialog" aria-labelledby="editEqModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editEqModalLabel">Was ist dir im Beruf besonders wichtig?</h5>
                    <button class="btn-close" id="addEqBtnClose" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Formular zum Bearbeiten der EQ-Frage -->
                    <form id="editEqForm">
                        <input type="hidden" id="eq_id" name="eq_id" value="0">
                        <input type="hidden" name="talent_id" value="<?php echo $talent->ID; ?>">
                        <div class="form-group">
                            <label for="value">Antwort:</label>
                            <textarea class="form-control" id="eq_value" name="value" rows="3" required></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" id="submitEqFormBtn" class="btn btn-primary">Hinzufügen/Ändern</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php else : ?>
    <p>Talent nicht gefunden.</p>
<?php endif; ?>

<script>
    jQuery(document).ready(function($) {
        $('#addEqBtnOpen').click(()=>{
            $('#editEqModal').modal('show');
        });
        $('#addEqBtnClose').click(()=>{
            $('#eq_id').val(0);
            $('#editEqModal').modal('hide');
        });
           
        // Modales Fenster öffnen, um EQ-Frage zu bearbeiten
        $('.edit-eq').click(function() {
            $('#eq_id').val($(this).data('id'));
            $('#eq_value').val($(this).data('value'));
            $('#editEqModal').modal('show');
        });

        // AJAX-Anfrage zum Speichern von bearbeiteter EQ-Frage
        $('#editEqForm').submit(function(e) {
            e.preventDefault(); // Verhindert das Standardformulareinreichungsverhalten
            if($('#talentDetailForm')[0]){
                $('#talentDetailForm').trigger('submit');
            }
            $('#submitEqFormBtn').prop('disabled', true);

            // Formulardaten sammeln
            var formData = $(this).serialize();

            // AJAX-Anfrage senden
            $.ajax({
                type: 'POST',
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                data: formData + '&action=edit_eq',
                success: function(response) {
                    // Erfolgreiche Verarbeitung
                    console.log(response);
                    // Hier können Sie je nach Bedarf weitere Aktionen ausführen
                    // Z.B. Seite neu laden, um die aktualisierten Daten anzuzeigen
                    if(response.success){
                        location.reload();
                    }
                    $('#submitEqFormBtn').prop('disabled', false);
                },
                error: function(xhr, status, error) {
                    // Fehlerbehandlung
                    console.error(error);
                    $('#submitEqFormBtn').prop('disabled', false);
                }
            });
        });
    });
</script>
