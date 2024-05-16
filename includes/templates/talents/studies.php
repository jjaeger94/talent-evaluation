<?php
function get_study_field($field) {
    switch ($field) {
        case 1:
            return 'Informatik und Informationstechnologie';
        case 2:
            return 'Betriebswirtschaftslehre (BWL) und Management';
        case 3:
            return 'Gesundheitswissenschaften und Medizin';
        case 4:
            return 'Erziehungswissenschaften und Pädagogik';
        case 5:
            return 'Umweltwissenschaften und Nachhaltigkeit';
        case 6:
            return 'Design und Kreativwirtschaft';
        case 7:
            return 'Tourismus- und Eventmanagement';
        case 8:
            return 'Sozialwissenschaften und Soziale Arbeit';
        case 9:
            return 'Naturwissenschaften und Forschung';
        case 10:
            return 'Sonstige';
        // Weitere Fälle hinzufügen, falls erforderlich
        default:
            return '';
    }
}
function get_study_degree($degree) {
    switch ($degree) {
        case 1:
            return 'Kein Abschluss';
        case 2:
            return 'Bachelor';
        case 3:
            return 'Master';
        case 4:
            return 'Doktor';
        case 5:
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
            <?php if ($studies) : ?>
                <?php foreach ($studies as $study) : ?>
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo get_study_degree($study->degree); ?></h5>
                                <p class="card-text"> <?php echo $study->designation; ?></p>
                                <a href="#" class="btn btn-primary edit-study" data-id="<?php echo $study->ID; ?>" data-designation="<?php echo $study->designation; ?>" data-field="<?php echo $study->field; ?>" data-degree="<?php echo $study->degree; ?>">Bearbeiten</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            <button type="button" class="btn btn-primary" id="addStudyBtnOpen" >Studium hinzufügen</button>
        </div>
    </div>
    </div>

    <!-- Modal zum Bearbeiten eines Studiums -->
    <div class="modal fade" id="editStudyModal" tabindex="-1" role="dialog" aria-labelledby="editStudyModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editStudyModalLabel">Studium bearbeiten</h5>
                </div>
                <div class="modal-body">
                    <!-- Formular zum Bearbeiten des Studiums -->
                    <form id="editStudyForm">
                        <input type="hidden" id="study_id" name="study_id" value="0">
                        <input type="hidden" name="user_id" value="<?php echo $talent->ID; ?>">
                        <div class="form-group">
                            <label for="field">Studienfeld:</label>
                            <select class="form-control" id="study_field" name="field" required>
                            <?php for ($i = 1; $i <= 10; $i++) : ?>
                                <?php $selectedStudyField = ($study && $study->field == $i) ? 'selected' : ''; ?>
                                <option value="<?php echo $i; ?>" <?php echo $selectedStudyField; ?>><?php echo get_study_field($i); ?></option>
                            <?php endfor; ?>
                        </select>
                        </div>
                        <div class="form-group">
                            <label for="degree">Abschluss:</label>
                            <select class="form-control" id="study_degree" name="degree" required>
                            <?php for ($i = 1; $i <= 5; $i++) : ?>
                                <?php $selectedStudyDegree = ($study && $study->degree == $i) ? 'selected' : ''; ?>
                                <option value="<?php echo $i; ?>" <?php echo $selectedStudyDegree; ?>><?php echo get_study_degree($i); ?></option>
                            <?php endfor; ?>
                        </select>
                        </div>
                        <div class="form-group">
                            <label for="designation">Bezeichnung:</label>
                            <input type="text" class="form-control" id="study_designation" name="designation" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" id="addStudyBtnClose">Schließen</button>
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
            $('#editStudyModal').modal('show');
        });
        $('#addStudyBtnClose').click(()=>{
            $('#study_id').val(0);
            $('#editStudyModal').modal('hide');
        });
           
        // Modales Fenster öffnen, um ein Studium zu bearbeiten
        $('.edit-study').click(function() {
            $('#study_id').val($(this).data('id'));
            $('#study_field').val($(this).data('field'));
            $('#study_designation').val($(this).data('designation'));
            $('#study_degree').val($(this).data('degree'));
            $('#editStudyModal').modal('show');

        });

        // AJAX-Anfrage zum Speichern eines bearbeiteten Studiums
        $('#editStudyForm').submit(function(e) {
            e.preventDefault(); // Verhindert das Standardformulareinreichungsverhalten

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
