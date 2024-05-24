<button class="btn btn-primary mb-3" onclick="showAddRequirementForm()">Neue Anforderung hinzufügen</button>
<div id="requirements-list">
    <?php if ($requirements): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Typ</th>
                    <th>Feld</th>
                    <th>Grad</th>
                    <th>Aktionen</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($requirements as $requirement): ?>
                    <tr data-id="<?php echo $requirement->ID; ?>">
                        <td><?php echo esc_html($requirement->type); ?></td>
                        <td><?php echo esc_html($requirement->field); ?></td>
                        <td><?php echo esc_html($requirement->degree); ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="showEditRequirementForm(<?php echo $requirement->ID; ?>)">Bearbeiten</button>
                            <button class="btn btn-danger btn-sm" onclick="deleteRequirement(<?php echo $requirement->ID; ?>)">Entfernen</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Keine Anforderungen gefunden.</p>
    <?php endif; ?>

    <div id="requirement-form-modal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Anforderung hinzufügen/bearbeiten</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Schließen">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="requirement-form">
                        <input type="hidden" name="job_id" value="<?php echo $job_id; ?>">
                        <input type="hidden" name="requirement_id" id="requirement_id">
                        <div class="form-group">
                            <label for="type">Typ</label>
                            <input type="number" class="form-control" id="type" name="type" required>
                        </div>
                        <div class="form-group">
                            <label for="field">Feld</label>
                            <input type="number" class="form-control" id="field" name="field" required>
                        </div>
                        <div class="form-group">
                            <label for="degree">Grad</label>
                            <input type="number" class="form-control" id="degree" name="degree" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Speichern</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showAddRequirementForm() {
    $('#requirement_id').val('');
    $('#type').val('');
    $('#field').val('');
    $('#degree').val('');
    $('#requirement-form-modal').modal('show');
}

function showEditRequirementForm(id) {
    var row = $('tr[data-id="' + id + '"]');
    $('#requirement_id').val(id);
    $('#type').val(row.find('td:eq(1)').text());
    $('#field').val(row.find('td:eq(2)').text());
    $('#degree').val(row.find('td:eq(3)').text());
    $('#requirement-form-modal').modal('show');
}

function deleteRequirement(id) {
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
                    $('tr[data-id="' + id + '"]').remove();
                } else {
                    alert('Fehler beim Entfernen der Anforderung');
                }
            }
        });
    }
}

jQuery(document).ready(function($) {
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
            }
        });
    });
});
</script>
