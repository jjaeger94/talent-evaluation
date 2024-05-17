<?php
function get_apprenticeship_field($field) {
    switch ($field) {
        case 1:
            return 'Gewerblich-technische Ausbildungsberufe';
        case 2:
            return 'Kaufmännische Ausbildungsberufe';
        case 3:
            return 'Sozialpädagogische und Gesundheitsberufe';
        case 4:
            return 'Informationstechnologie und Medien';
        case 5:
            return 'Handwerkliche Berufe';
        case 6:
            return 'Sonstige';
        // Weitere Fälle hinzufügen, falls erforderlich
        default:
            return '';
    }
}
?>
<?php if ($talent) : ?>
    <div class="card mb-3">
    <div class="card-body">
        <div class="row">
            <?php if ($apprenticeships) : ?>
                <?php foreach ($apprenticeships as $apprenticeship) : ?>
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $apprenticeship->designation; ?></h5>
                                <p class="card-text"><?php echo get_apprenticeship_field($apprenticeship->field); ?></p>
                                <?php $object = $apprenticeship;?>
                                <?php include 'blocks/render-date.php'; ?>
                                <button href="#" class="btn btn-primary edit-apprenticeship" data-id="<?php echo $apprenticeship->ID; ?>" data-designation="<?php echo $apprenticeship->designation; ?>" data-field="<?php echo $apprenticeship->field; ?>" data-start-date="<?php echo $apprenticeship->start_date; ?>" data-end-date="<?php echo $apprenticeship->end_date; ?>">Bearbeiten</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
            <div class="col">
                <p>Hier kannst du deine Ausbildungen hinzufügen</p>
            </div>
            <?php endif; ?>
            <button type="button" class="btn btn-primary" id="addApprenticeshipBtnOpen" >Ausbildung hinzufügen</button>
        </div>
    </div>
    </div>

    <!-- Modal zum Bearbeiten einer Ausbildung -->
    <div class="modal fade" id="editApprenticeshipModal" tabindex="-1" role="dialog" aria-labelledby="editApprenticeshipModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editApprenticeshipModalLabel">Ausbildung bearbeiten</h5>
                    <button type="button" class="btn-close" id="addApprenticeshipBtnClose" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Formular zum Bearbeiten der Ausbildung -->
                    <form id="editApprenticeshipForm">
                        <input type="hidden" id="apprenticeship_id" name="apprenticeship_id" value="0">
                        <input type="hidden" name="user_id" value="<?php echo $talent->ID; ?>">
                        <div class="form-group">
                            <label for="field">Ausbildungsfeld:</label>
                            <select class="form-control" id="app_field" name="field" required>
                            <?php for ($i = 1; $i <= 6; $i++) : ?>
                                <?php $selectedApprenticeshipField = (isset($apprenticeship) && $apprenticeship->field == $i) ? 'selected' : ''; ?>
                                <option value="<?php echo $i; ?>" <?php echo $selectedApprenticeshipField; ?>><?php echo get_apprenticeship_field($i); ?></option>
                            <?php endfor; ?>
                        </select>
                        </div>
                        <div class="form-group">
                            <label for="designation">Bezeichnung:</label>
                            <input type="text" class="form-control" id="app_designation" name="designation" required>
                        </div>
                        <div class="form-group">
                            <label for="start_date">Startdatum:</label>
                            <input type="date" class="form-control" id="app_start_date" name="start_date" required>
                        </div>
                        <div class="form-group">
                            <label for="end_date">Enddatum:</label>
                            <input type="date" class="form-control" id="app_end_date" name="end_date">
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="app_current_job" name="current_job">
                            <label class="form-check-label" for="current_job">Ich bin noch dabei</label>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-danger" id="editApprenticeshipBtnDelete">Eintrag entfernen</button>
                            <button type="submit" class="btn btn-primary">Hinzufügen/Ändern</button>
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
        $('#addApprenticeshipBtnOpen').click(()=>{
            $('#editApprenticeshipBtnDelete').hide();
            $('#editApprenticeshipModal').modal('show');
        });
        $('#addApprenticeshipBtnClose').click(()=>{
            $('#apprenticeship_id').val(0);
            $('#editApprenticeshipModal').modal('hide');
        });

        $('#app_current_job').change(function() {
            if ($(this).is(':checked')) {
                $('#app_end_date').prop('disabled', true).val('');
            } else {
                $('#app_end_date').prop('disabled', false);
            }
        }).change();

        $('#editApprenticeshipBtnDelete').click(()=>{
            let id = $('#apprenticeship_id').val();
            if (id != 0 && confirm('Eintrag wirklich löschen?')){
                // AJAX-Anfrage senden
                $.ajax({
                    type: 'POST',
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    data: 'apprenticeship_id='+id+'&action=delete_apprenticeship',
                    success: function(response) {
                        // Erfolgreiche Verarbeitung
                        console.log(response);
                        // Hier können Sie je nach Bedarf weitere Aktionen ausführen
                        // Z.B. Seite neu laden, um die aktualisierten Daten anzuzeigen
                        if(response.success){
                            location.reload();
                        }
                        
                    },
                    error: function(xhr, status, error) {
                        // Fehlerbehandlung
                        console.error(error);
                    }
                });
            }
        });
           
        // Modales Fenster öffnen, um eine Ausbildung zu bearbeiten
        $('.edit-apprenticeship').click(function() {
            $('#apprenticeship_id').val($(this).data('id'));
            $('#app_field').val($(this).data('field'));
            $('#app_designation').val($(this).data('designation'));
            $('#app_start_date').val($(this).data('start-date'));
            $('#app_end_date').val($(this).data('end-date'));
            if ($('#app_end_date').val() == '9999-12-31') {
                $('#app_current_job').prop('checked', true);
                $('#app_end_date').prop('disabled', true).val('');
            } else {
                $('#app_current_job').prop('checked', false);
                $('#app_end_date').prop('disabled', false);
            }
            $('#editApprenticeshipBtnDelete').show();
            $('#editApprenticeshipModal').modal('show');

        });

        // AJAX-Anfrage zum Speichern einer bearbeiteten Ausbildung
        $('#editApprenticeshipForm').submit(function(e) {
            e.preventDefault(); // Verhindert das Standardformulareinreichungsverhalten

            // Formulardaten sammeln
            var formData = $(this).serialize();

            // AJAX-Anfrage senden
            $.ajax({
                type: 'POST',
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                data: formData + '&action=edit_apprenticeship',
                success: function(response) {
                    // Erfolgreiche Verarbeitung
                    console.log(response);
                    // Hier können Sie je nach Bedarf weitere Aktionen ausführen
                    // Z.B. Seite neu laden, um die aktualisierten Daten anzuzeigen
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
