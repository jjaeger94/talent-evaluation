<form id="talentDetailForm" method="post">
    <input type="hidden" name="talent_id" value="<?php echo $talent->ID; ?>">
    <div class="card mb-3">
        <div class="card-body">
            <div class="form-group">
                <label for="prename">Vorname:</label>
                <input type="text" class="form-control" id="prename" name="prename" value="<?php echo $talent->prename; ?>">
            </div>
            <div class="form-group">
                <label for="surname">Nachname:</label>
                <input type="text" class="form-control" id="surname" name="surname" value="<?php echo $talent->surname; ?>">
            </div>
            <div class="form-group">
                <label for="email">E-Mail:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $talent->email; ?>">
            </div>
            <div class="form-group">
                <label for="mobile">Telefonnummer:</label>
                <input type="text" class="form-control" id="mobile" name="mobile" value="<?php echo $talent->mobile; ?>">
            </div>
            <div class="form-group">
                <label for="post_code">PLZ:</label>
                <input type="text" class="form-control" id="post_code" name="post_code" value="<?php echo $talent->post_code; ?>">
            </div>
            <div class="form-group">
                <label for="field">Verfügbarkeit:</label>
                <select class="form-control" id="availability" name="availability" required>
                <?php for ($i = 0; $i <= 7; $i++) : ?>
                    <?php $selectedAvailability= ($talent->availability == $i) ? 'selected' : ''; ?>
                    <option value="<?php echo $i; ?>" <?php echo $selectedAvailability; ?>><?php echo get_availability_string($i); ?></option>
                <?php endfor; ?>
            </select>
            </div>
            <!-- Weitere Felder hinzufügen, falls erforderlich -->
            <button id="" type="submit" class="btn btn-primary mt-2">Aktualisieren</button>
        </div>
    </div>
</form>
<script>
jQuery(document).ready(function($) {
    $('#talentDetailForm').submit(function(e) {
        e.preventDefault(); // Verhindert das Standardformulareinreichungsverhalten

        // Formulardaten sammeln
        var formData = $(this).serialize();

        // AJAX-Anfrage senden
        $.ajax({
            type: 'POST',
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: formData + '&action=save_talent_details',
            success: function(response) {
                // Erfolgreiche Verarbeitung
                console.log(response);
                if(response.success){
                    location.reload();
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