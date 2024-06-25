<form id="talentDetailForm" method="post">
<input type="hidden" name="talent_id" value="<?php echo $talent->ID; ?>">
<p class="card-title"><strong>Persöhnliche Daten:</strong></p>
    <div class="form-group mb-3">
        <label for="prename">Vorname:</label>
        <input type="text" class="form-control" id="prename" name="prename" value="<?php echo $talent->prename; ?>">
    </div>
    <div class="form-group mb-3">
        <label for="surname">Nachname:</label>
        <input type="text" class="form-control" id="surname" name="surname" value="<?php echo $talent->surname; ?>">
    </div>
    <div class="form-group mb-3">
        <label for="email">E-Mail:</label>
        <input type="email" class="form-control" id="email" name="email" value="<?php echo $talent->email; ?>">
    </div>
    <div class="form-group mb-3">
        <label for="mobile">Telefonnummer:</label>
        <input type="text" class="form-control" id="mobile" name="mobile" value="<?php echo $talent->mobile; ?>">
    </div>
    <div class="form-group mb-1">
        <label for="post_code">PLZ:</label>
        <input type="text" class="form-control" id="post_code" name="post_code" value="<?php echo $talent->post_code; ?>">
    </div>
    <div class="form-group mb-3">
        <label for="field">Verfügbarkeit:</label><?php echo info_button('personal_data_availability'); ?>
        <select class="form-select" id="availability" name="availability" required>
        <?php for ($i = 0; $i <= 7; $i++) : ?>
            <?php $selectedAvailability= ($talent->availability == $i) ? 'selected' : ''; ?>
            <option value="<?php echo $i; ?>" <?php echo $selectedAvailability; ?>><?php echo get_availability_string($i); ?></option>
        <?php endfor; ?>
        </select>
    </div>
    <?php include 'mobility.php'; ?>
    <?php include 'school.php'; ?>
</form>
<button form="talentDetailForm" type="submit" class="btn btn-primary mt-2">Basisdaten speichern</button>
<div class="wrap">
    <span id="personal-data-result"></span>
</div>
<script>
jQuery(document).ready(function($) {
    let formChanged = false;
    $('#talentDetailForm').on('change', 'input, textarea, select', function() {
        formChanged = true;
    });

    $('#talentDetailForm').submit(function(e) {
        e.preventDefault(); // Verhindert das Standardformulareinreichungsverhalten

        if(formChanged){
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
                        formChanged = false;
                        $('#personal-data-result').text('Erfolgreich gespeichert');
                    }
                    // Hier können Sie je nach Bedarf weitere Aktionen ausführen
                },
                error: function(xhr, status, error) {
                    // Fehlerbehandlung
                    console.error(error);
                }
            });
        }else{
            $('#personal-data-result').text('Erfolgreich gespeichert');
        }
    });
});
</script>