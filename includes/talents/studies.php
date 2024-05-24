<?php if ($talent) : ?>
<div class="row">
<p class="card-title"><strong>Studium</strong></p>
    <?php if ($studies) : ?>
        <?php foreach ($studies as $study) : ?>
            <div class="col-md-4 mb-3">
                <?php include TE_DIR.'blocks/study-card.php'; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<div class="row mb-3">
<div class="col">
    <button class="btn btn-primary" id="addStudyBtnOpen" >Studium hinzufügen</button>
</div>
</div>

    <!-- Modal zum Bearbeiten eines Studiums -->
    <div class="modal fade" id="editStudyModal" tabindex="-1" role="dialog" aria-labelledby="editStudyModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editStudyModalLabel">Studium bearbeiten</h5>
                    <button class="btn-close" id="addStudyBtnClose" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Formular zum Bearbeiten des Studiums -->
                    <form id="editStudyForm">
                        <input type="hidden" id="study_id" name="study_id" value="0">
                        <input type="hidden" name="talent_id" value="<?php echo $talent->ID; ?>">
                        <div class="form-group">
                            <label for="field">Studienfeld:</label>
                            <select class="form-select" id="study_field" name="field" required>
                            <?php for ($i = 1; $i <= 10; $i++) : ?>
                                <?php $selectedStudyField = (isset($study) && $study->field == $i) ? 'selected' : ''; ?>
                                <option value="<?php echo $i; ?>" <?php echo $selectedStudyField; ?>><?php echo get_study_field($i); ?></option>
                            <?php endfor; ?>
                        </select>
                        </div>
                        <div class="form-group">
                            <label for="degree">Abschluss:</label>
                            <select class="form-select" id="study_degree" name="degree" required>
                            <?php for ($i = 1; $i <= 5; $i++) : ?>
                                <?php $selectedStudyDegree = (isset($study) && $study->degree == $i) ? 'selected' : ''; ?>
                                <option value="<?php echo $i; ?>" <?php echo $selectedStudyDegree; ?>><?php echo get_study_degree($i); ?></option>
                            <?php endfor; ?>
                        </select>
                        </div>
                        <div class="form-group">
                            <label for="designation">Bezeichnung:</label>
                            <input type="text" class="form-control" id="study_designation" name="designation" required>
                        </div>
                        <div class="form-group">
                            <label for="start_date">Startdatum:</label>
                            <input type="date" class="form-control" id="study_start_date" name="start_date" required>
                        </div>
                        <div class="form-group">
                            <label for="end_date">Enddatum:</label>
                            <input type="date" class="form-control" id="study_end_date" name="end_date">
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="study_current_job" name="current_job">
                            <label class="form-check-label" for="current_job">Ich bin noch dabei</label>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button class="btn btn-danger" id="editStudyBtnDelete">Eintrag entfernen</button>
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
        $('#addStudyBtnOpen').click(()=>{
            $('#editStudyBtnDelete').hide();
            $('#editStudyModal').modal('show');
        });
        $('#addStudyBtnClose').click(()=>{
            $('#study_id').val(0);
            $('#editStudyModal').modal('hide');
        });

        $('#study_current_job').change(function() {
            if ($(this).is(':checked')) {
                $('#study_end_date').prop('disabled', true).val('');
            } else {
                $('#study_end_date').prop('disabled', false);
            }
        }).change();
           
        // Modales Fenster öffnen, um ein Studium zu bearbeiten
        $('.edit-study').click(function() {
            $('#study_id').val($(this).data('id'));
            $('#study_field').val($(this).data('field'));
            $('#study_designation').val($(this).data('designation'));
            $('#study_degree').val($(this).data('degree'));
            $('#study_start_date').val($(this).data('start-date'));
            $('#study_end_date').val($(this).data('end-date'));
            if ($('#study_end_date').val() == '9999-12-31') {
                $('#study_current_job').prop('checked', true);
                $('#study_end_date').prop('disabled', true).val('');
            } else {
                $('#study_current_job').prop('checked', false);
                $('#study_end_date').prop('disabled', false);
            }
            $('#editStudyBtnDelete').show();
            $('#editStudyModal').modal('show');

        });

        $('#editStudyBtnDelete').click(()=>{
            let id = $('#study_id').val();
            if (id != 0 && confirm('Eintrag wirklich löschen?')){
                if($('#talentDetailForm')[0]){
                    $('#talentDetailForm').trigger('submit');
                }
                // AJAX-Anfrage senden
                $.ajax({
                    type: 'POST',
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    data: 'study_id='+id+'&action=delete_study',
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

        // AJAX-Anfrage zum Speichern eines bearbeiteten Studiums
        $('#editStudyForm').submit(function(e) {
            e.preventDefault(); // Verhindert das Standardformulareinreichungsverhalten
            if($('#talentDetailForm')[0]){
                $('#talentDetailForm').trigger('submit');
            }

            // Formulardaten sammeln
            var formData = $(this).serialize();

            // AJAX-Anfrage senden
            $.ajax({
                type: 'POST',
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                data: formData + '&action=edit_study',
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
