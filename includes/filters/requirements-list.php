<?php
function get_apprenticeship_fields() {
    $fields = '';
    for ($i = 1; $i <= 6; $i++) {
        $fields .= '<option value="' . $i . '">' . get_apprenticeship_field($i) . '</option>';
    }
    return $fields;
}

function get_study_fields() {
    $fields = '';
    for ($i = 1; $i <= 5; $i++) {
        $fields .= '<option value="' . $i . '">' . get_study_field($i) . '</option>';
    }
    return $fields;
}

function get_experience_fields() {
    $fields = '';
    for ($i = 1; $i <= 9; $i++) {
        $fields .= '<option value="' . $i . '">' . get_experience_field($i) . '</option>';
    }
    return $fields;
}
?>
<?php if ($requirements): ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Typ</th>
                <th>Feld</th>
                <th>Abschluss</th>
                <th>Aktionen</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($requirements as $requirement): ?>
            <tr data-id="<?php echo $requirement->ID; ?>">
                <td data-value="<?php echo $requirement->type; ?>" ><?php echo esc_html(get_type_label($requirement->type)); ?></td>
                <td data-value="<?php echo $requirement->field; ?>"><?php echo esc_html(get_field_label($requirement->type, $requirement->field)); ?></td>
                <td data-value="<?php echo $requirement->degree; ?>"><?php echo esc_html(get_study_degree($requirement->degree)); ?></td>
                <td>
                    <button class="btn btn-warning btn-sm edit-requirement-btn">Bearbeiten</button>
                    <button class="btn btn-danger btn-sm delete-requirement-btn">Entfernen</button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    </table>
<?php else: ?>
    <p>Keine Anforderungen gefunden.</p>
<?php endif; ?>
<button class="btn btn-primary mb-3" id="add-requirement" >Neue Anforderung hinzufügen</button>

<div id="requirement-form-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Anforderung hinzufügen/bearbeiten</h5>
                <button class="btn-close" id="requirementFormBtnClose" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form id="requirement-form">
                <input type="hidden" name="job_id" id="job_id" value="<?php echo $job->ID; ?>">
                <input type="hidden" name="requirement_id" id="requirement_id" value="0">
                <div class="form-group mb-3">
                    <label for="type">Typ</label>
                    <select class="form-select" id="type" name="type" required>
                        <option value="1">Ausbildung</option>
                        <option value="2">Studium</option>
                        <option value="3">Berufserfahrung</option>
                    </select>
                </div>
                <div class="form-group mb-3" id="field-group">
                    <label for="field">Feld</label>
                    <select class="form-select" id="field" name="field" required>
                        <!-- Dynamische Optionen werden hier per JavaScript eingefügt -->
                    </select>
                </div>
                <div class="form-group mb-3" id="degree-group">
                    <label for="degree">Abschluss:</label>
                    <select class="form-select" id="degree" name="degree" required>
                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                            <option value="<?php echo $i; ?>"><?php echo get_study_degree($i); ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Speichern</button>
            </form>
            </div>
        </div>
    </div>
</div>

<script>

jQuery(document).ready(function($) {
    $('.edit-requirement-btn').click(function() {
        
        var row = $(this).closest('tr');
        var id = row.data('id');
        $('#requirement_id').val(id);
        $('#type').val(row.find('td:eq(0)').data('value')); // Typ
        $('#field').val(row.find('td:eq(1)').data('value')); // Feld
        $('#degree').val(row.find('td:eq(2)').data('value')); // Grad
        $('#requirement-form-modal').modal('show');
    });

    $('.delete-requirement-btn').click(function() {
        var row = $(this).closest('tr');
        var id = row.data('id');
        if (confirm('Möchten Sie diese Anforderung wirklich entfernen?')) {
            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'POST',
                data: {
                    action: 'delete_requirement',
                    requirement_id: id
                },
                success: function(response) {
                    if (response.success) {
                        row.remove();
                    } else {
                        alert('Fehler beim Entfernen der Anforderung');
                    }
                }
            });
        }
    });

    $('#type').change(function() {
        var type = $(this).val();
        var fieldGroup = $('#field-group');
        var degreeGroup = $('#degree-group');

        fieldGroup.find('select').empty();

        if (type == '1') {
            // Ausbildung
            fieldGroup.find('select').html('<?php echo get_apprenticeship_fields(); ?>');
            degreeGroup.hide();
            $('#degree').prop('required', false);
        } else if (type == '2') {
            // Studium
            fieldGroup.find('select').html('<?php echo get_study_fields(); ?>');
            degreeGroup.show();
            $('#degree').prop('required', true);
        } else if (type == '3') {
            // Berufserfahrung
            fieldGroup.find('select').html('<?php echo get_experience_fields(); ?>');
            degreeGroup.hide();
            $('#degree').prop('required', false);
        }
    }).change();

    $('#requirementFormBtnClose').click(()=>{
            $('#requirement_id').val(0);
            $('#requirement-form-modal').modal('hide');
        });

    $('#add-requirement').click(function () {
        $('#requirement_id').val(0);
        $('#type').val(1);
        $('#field').val(1);
        $('#degree').val(1);
        $('#degree-group').hide();
        $('#requirement-form-modal').modal('show');
    });

    $('#requirement-form').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: formData + '&action=save_requirement',
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Fehler beim Speichern der Anforderung');
                }
                console.log(response);
            }
        });
    });
});
</script>
