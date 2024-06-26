<div class="card mb-3">
    <div class="card-body">
        <h5 class="card-title"><?php echo $talent->prename . ' ' . $talent->surname; ?></h5>
        <?php if (isset($matching)) : ?>
            <form id="matchingForm" method="post">
                <input type="hidden" name="matching_id" value="<?php echo $matching->ID; ?>">
                <div class="form-group mb-3">
                    <label for="matching"><strong>Matching Status</strong></label>
                    <select class="form-select" id="matching" name="matching">
                        <option value="0" <?php echo (isset($matching->value) && $matching->value == 0) ? 'selected' : ''; ?>>
                            <?php echo get_matching_state(0); ?>
                        </option>
                        <option value="1" <?php echo (isset($matching->value) && $matching->value == 1) ? 'selected' : ''; ?>>
                            <?php echo get_matching_state(1); ?>
                        </option>
                        <option value="2" <?php echo (isset($matching->value) && $matching->value == 2) ? 'selected' : ''; ?>>
                            <?php echo get_matching_state(2); ?>
                        </option>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label for="job_info"><strong>Personalisierte Job Beschreibung</strong></label>
                    <textarea rows="3" class="form-control" id="job_info" name="job_info"><?php echo isset($matching->job_info) ? esc_attr($matching->job_info) : ''; ?></textarea>
                </div>
            </form>
            <button form="matchingForm" type="submit" class="btn btn-primary">Speichern</button>
        <?php else: ?>
            <form id="activateMatchingForm" method="post">
                <input type="hidden" name="talent_id" value="<?php echo $talent->ID; ?>">
                <input type="hidden" name="job_id" value="<?php echo $job->ID; ?>">
                <div class="form-group mb-3">
                    <label for="notes"><strong>Personalisierte Job Beschreibung</strong></label>
                    <textarea rows="3" class="form-control" id="job_info" name="job_info"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Matching beginnen</button>
            </form>
        <?php endif; ?>
    </div>
        <div class="wrap">
            <span id="compareResult"></span>
        </div>
    </div>
</div>
<script>
jQuery(document).ready(function($) {
    $('#activateMatchingForm').submit(function(e) {
        e.preventDefault(); // Verhindert das Standardformulareinreichungsverhalten
        var formData = $(this).serialize();
        // AJAX-Anfrage senden
        $.ajax({
            type: 'POST',
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: formData + '&action=activate_matching',
            success: function(response) {
                // Erfolgreiche Verarbeitung
                console.log(response);
                // Seite neu laden, um die aktualisierten Daten anzuzeigen
                if(response.success){
                    $('#compareResult').text(response.data);
                    //location.reload();
                }
            },
            error: function(xhr, status, error) {
                // Fehlerbehandlung
                console.error(error);
            }
        });
    });
    $('#matchingForm').submit(function(e) {
        e.preventDefault(); // Verhindert das Standardformulareinreichungsverhalten
        // Formulardaten sammeln
        var formData = $(this).serialize();
        // AJAX-Anfrage senden
        $.ajax({
            type: 'POST',
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: formData + '&action=save_matching',
            success: function(response) {
                // Erfolgreiche Verarbeitung
                console.log(response);
                if(response.success){
                    $('#compareResult').text('Erfolgreich geändert');
                }
                // Hier können Sie je nach Bedarf weitere Aktionen ausführen
            },
            error: function(xhr, status, error) {
                // Fehlerbehandlung
                console.error(error);
            }
        });
    });
});
</script>