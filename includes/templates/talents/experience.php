<?php
function get_experience_field($field) {
    switch ($field) {
        case 1:
            return 'Geschäftsführung/Vorstand';
        case 2:
            return 'Vertrieb und Marketing';
        case 3:
            return 'Finanzen und Buchhaltung';
        case 4:
            return 'Personalwesen/Personalabteilung';
        case 5:
            return 'Produktion/Operations';
        case 6:
            return 'Forschung und Entwicklung';
        case 7:
            return 'Kundendienst und Suppor';
        case 8:
            return 'Informationstechnologie';
        case 9:
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
                <?php if ($experiences) : ?>
                    <?php foreach ($experiences as $experience) : ?>
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $experience->position; ?></h5>
                                    <p class="card-text"><?php echo $experience->company; ?></p>
                                    <div class="row">
                                        <div class="col">
                                            <p class="card-text"><?php echo $experience->start_date; ?></p>
                                        </div>
                                        <div class="col">
                                            <p class="card-text"><?php echo $experience->end_date; ?></p>
                                        </div>
                                    </div>
                                    <a href="#" class="btn btn-primary edit-experience" data-id="<?php echo $experience->ID; ?>" data-field="<?php echo $experience->field; ?>" data-position="<?php echo $experience->position; ?>" data-company="<?php echo $experience->company; ?>" data-start-date="<?php echo $experience->start_date; ?>" data-end-date="<?php echo $experience->end_date; ?>">Bearbeiten</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                <button type="button" class="btn btn-primary" id="addExperienceBtnOpen">Berufserfahrung hinzufügen</button>
            </div>
        </div>
    </div>

    <!-- Modal zum Bearbeiten einer Berufserfahrung -->
    <div class="modal fade" id="editExperienceModal" tabindex="-1" role="dialog" aria-labelledby="editExperienceModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editExperienceModalLabel">Berufserfahrung bearbeiten</h5>
                </div>
                <div class="modal-body">
                    <!-- Formular zum Bearbeiten der Berufserfahrung -->
                    <form id="editExperienceForm">
                        <input type="hidden" id="experience_id" name="experience_id" value="0">
                        <input type="hidden" name="user_id" value="<?php echo $talent->ID; ?>">
                        <div class="form-group">
                            <label for="field">Bereich:</label>
                            <select class="form-control" id="exp_field" name="field" required>
                            <?php for ($i = 1; $i <= 9; $i++) : ?>
                                <?php $selectedExperienceField = ($experience && $experience->field == $i) ? 'selected' : ''; ?>
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
                            <input type="date" class="form-control" id="exp_end_date" name="end_date">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" id="addExperienceBtnClose">Schließen</button>
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
        $('#addExperienceBtnOpen').click(()=>{
            $('#editExperienceModal').modal('show');
        });
        $('#addExperienceBtnClose').click(()=>{
            $('#experience_id').val(0);
            $('#editExperienceModal').modal('hide');
        });
           
        // Modales Fenster öffnen, um Berufserfahrung zu bearbeiten
        $('.edit-experience').click(function() {
            $('#experience_id').val($(this).data('id'));
            $('#exp_position').val($(this).data('position'));
            $('#exp_company').val($(this).data('company'));
            $('#exp_field').val($(this).data('field'));
            $('#exp_start_date').val($(this).data('start-date'));
            $('#exp_end_date').val($(this).data('end-date'));
            $('#editExperienceModal').modal('show');
        });

        // AJAX-Anfrage zum Speichern von bearbeiteter Berufserfahrung
        $('#editExperienceForm').submit(function(e) {
            e.preventDefault(); // Verhindert das Standardformulareinreichungsverhalten

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
                },
                error: function(xhr, status, error) {
                    // Fehlerbehandlung
                    console.error(error);
                }
            });
        });
    });
</script>
