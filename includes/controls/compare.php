<div class="card mb-3">
    <div class="card-body">
        <h5 class="card-title"><?php echo $talent->prename . ' ' . $talent->surname; ?></h5>
        <?php if (isset($matching)) : ?>
            <form id="matchingForm" method="post">
                <input type="hidden" name="matching_id" value="<?php echo $matching->ID; ?>">
                <div class="form-group mb-3">
                    <label for="matching"><strong>Status</strong></label>
                    <select class="form-select" id="matching" name="matching">
                        <?php for ($i = 0; $i <= 6; $i++) : ?>
                            <option value="<?php echo $i; ?>" <?php echo (isset($matching->value) && $matching->value == $i) ? 'selected' : ''; ?>>
                                <?php echo get_matching_state($i); ?>
                            </option>
                        <?php endfor; ?>
                        <option value="99" <?php echo (isset($matching->value) && $matching->value == 99) ? 'selected' : ''; ?>>
                            <?php echo get_matching_state(99); ?>
                        </option>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label for="added"><strong>Hinzugefügt</strong></label>
                    <input type="text" id="added" class="form-control" value="<?php echo date('d.m.Y H:i:s', strtotime($matching->added)); ?>" readonly>
                </div>
                <div class="form-group mb-3">
                    <label for="edited"><strong>Bearbeitet</strong></label>
                    <input type="text" id="edited" class="form-control" value="<?php echo date('d.m.Y H:i:s', strtotime($matching->edited)); ?>" readonly>
                </div>
            </form>
            <button form="matchingForm" type="submit" class="btn btn-primary">Speichern</button>
        <?php else: ?>
            <form id="activateMatchingForm" method="post">
                <input type="hidden" name="talent_id" value="<?php echo $talent->ID; ?>">
                <input type="hidden" name="job_id" value="<?php echo $job->ID; ?>">
                <button type="submit" class="btn btn-primary">Vormerken</button>
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
                    location.reload();
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