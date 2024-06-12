<?php if ($talent) : ?>
<div class="row">
    <p class="card-title"><strong>Berufserfahrung</strong></p>
    <?php if ($experiences) : ?>
        <?php foreach ($experiences as $experience) : ?>
            <div class="col-md-4 mb-3">
                <?php include TE_DIR.'cards/experience-card.php'; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<div class="row mb-3">
<div class="col">
    <button class="btn btn-primary" id="addExperienceBtnOpen">Berufserfahrung hinzufügen</button>
    </div>
</div>

    <!-- Modal zum Bearbeiten einer Berufserfahrung -->
    <div class="modal fade" id="editExperienceModal" tabindex="-1" role="dialog" aria-labelledby="editExperienceModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editExperienceModalLabel">Berufserfahrung bearbeiten</h5>
                    <button class="btn-close" id="addExperienceBtnClose" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Formular zum Bearbeiten der Berufserfahrung -->
                    <form id="editExperienceForm">
                        <input type="hidden" id="experience_id" name="experience_id" value="0">
                        <input type="hidden" name="talent_id" value="<?php echo $talent->ID; ?>">
                        <div class="form-group">
                            <label for="field">Bereich:</label>
                            <select class="form-control" id="exp_field" name="field" required>
                            <?php for ($i = 1; $i <= 9; $i++) : ?>
                                <?php $selectedExperienceField = (isset($experience) && $experience->field == $i) ? 'selected' : ''; ?>
                                <option value="<?php echo $i; ?>" <?php echo $selectedExperienceField; ?>><?php echo get_experience_field($i); ?></option>
                            <?php endfor; ?>
                        </select>
                        </div>
                        <div class="form-group">
                            <label for="position">Position:</label>
                            <input type="text" class="form-control" id="exp_position" name="position" required>
                        </div>
                        <div class="form-group">
                            <label for="company">Firma:</label>
                            <input type="text" class="form-control" id="exp_company" name="company" required>
                        </div>
                        <div class="form-group">
                            <label for="start_date">Startdatum:</label>
                            <input type="date" class="form-control" id="exp_start_date" name="start_date" required>
                        </div>
                        <div class="form-group">
                            <label for="end_date">Enddatum:</label>
                            <input type="date" class="form-control" id="exp_end_date" name="end_date" required>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="exp_current_job" name="current_job">
                            <label class="form-check-label" for="current_job">Ich bin noch dabei</label>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button class="btn btn-danger" id="addExperienceBtnDelete">Eintrag entfernen</button>
                            <button type="submit" id="submitExperienceFormBtn" class="btn btn-primary">Hinzufügen/Ändern</button>
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
        $('#addExperienceBtnOpen').click(()=>{
            $('#addExperienceBtnDelete').hide();
            $('#editExperienceModal').modal('show');
        });
        $('#addExperienceBtnClose').click(()=>{
            $('#experience_id').val(0);
            $('#editExperienceModal').modal('hide');
        });

        $('#exp_current_job').change(function() {
            if ($(this).is(':checked')) {
                $('#exp_end_date').prop('disabled', true).val('');
            } else {
                $('#exp_end_date').prop('disabled', false);
            }
        }).change();

        $('#addExperienceBtnDelete').click(()=>{
            let id = $('#experience_id').val();
            if (id != 0 && confirm('Eintrag wirklich löschen?')){
                if($('#talentDetailForm')[0]){
                    $('#talentDetailForm').trigger('submit');
                }
                // AJAX-Anfrage senden
                $.ajax({
                    type: 'POST',
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    data: 'experience_id='+id+'&action=delete_experience',
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
           
        // Modales Fenster öffnen, um Berufserfahrung zu bearbeiten
        $('.edit-experience').click(function() {
            $('#experience_id').val($(this).data('id'));
            $('#exp_position').val($(this).data('position'));
            $('#exp_company').val($(this).data('company'));
            $('#exp_field').val($(this).data('field'));
            $('#exp_start_date').val($(this).data('start-date'));
            $('#exp_end_date').val($(this).data('end-date'));
            if ($('#exp_end_date').val() == '9999-12-31') {
                $('#exp_current_job').prop('checked', true);
                $('#exp_end_date').prop('disabled', true).val('');
            } else {
                $('#exp_current_job').prop('checked', false);
                $('#exp_end_date').prop('disabled', false);
            }
            $('#addExperienceBtnDelete').show();
            $('#editExperienceModal').modal('show');
        });

        // AJAX-Anfrage zum Speichern von bearbeiteter Berufserfahrung
        $('#editExperienceForm').submit(function(e) {
            e.preventDefault(); // Verhindert das Standardformulareinreichungsverhalten
            if($('#talentDetailForm')[0]){
                $('#talentDetailForm').trigger('submit');
            }
            $('#submitExperienceFormBtn').prop('disabled', true);
            // Formulardaten sammeln
            var formData = $(this).serialize();

            // AJAX-Anfrage senden
            $.ajax({
                type: 'POST',
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                data: formData + '&action=edit_experience',
                success: function(response) {
                    // Erfolgreiche Verarbeitung
                    console.log(response);
                    // Hier können Sie je nach Bedarf weitere Aktionen ausführen
                    // Z.B. Seite neu laden, um die aktualisierten Daten anzuzeigen
                    if(response.success){
                        location.reload();
                    }
                    $('#submitExperienceFormBtn').prop('disabled', false);
                },
                error: function(xhr, status, error) {
                    // Fehlerbehandlung
                    console.error(error);
                    $('#submitExperienceFormBtn').prop('disabled', false);
                }
            });
        });
    });
</script>
