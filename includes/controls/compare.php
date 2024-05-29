<div class="card mb-3">
    <div class="card-body">
        <h5 class="card-title"><?php echo $talent->prename . ' ' . $talent->surname; ?></h5>
        <?php if (isset($matching)) : ?>
            <div class="row">
                <div class="col">
                    <p><strong>Matching ID:</strong> <?php echo $matching->ID; ?></p>
                </div>
                <div class="col">
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
                    </form>
                </div>
                <div class="col">
                    <button form="matchingForm" type="submit" class="btn btn-success">Speichern</button>
                </div>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col">
                    <button id="activateMatching" class="btn btn-primary">Matching beginnen</button>
                </div>
            </div>
        <?php endif; ?>
    </div>
        <div class="wrap">
            <span id="compareResult"></span>
        </div>
    </div>
</div>
<script>
jQuery(document).ready(function($) {
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
    // AJAX-Anfrage zum Hinzufügen einer neuen Schule
    $('#activateMatching').click(()=>{
        // AJAX-Anfrage senden
        $.ajax({
            type: 'POST',
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: 'talent_id=<?php echo $talent->ID; ?>&job_id=<?php echo $job->ID; ?>&action=activate_matching',
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
});
</script>