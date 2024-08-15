<form id="talentDetailForm" method="post">
    <div class="form-group mb-3">
        <label for="prename">Vorname:</label>
        <input type="text" class="form-control" id="prename" name="prename" value="<?php echo isset($talent->prename) ? $talent->prename : ''; ?>">
    </div>
    <div class="form-group mb-3">
        <label for="surname">Nachname:</label>
        <input type="text" class="form-control" id="surname" name="surname" value="<?php echo isset($talent->surname) ? $talent->surname : ''; ?>">
    </div>
    <div class="form-group mb-3">
        <label for="email">E-Mail:</label>
        <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($talent->email) ? $talent->email : ''; ?>">
    </div>
    <div class="form-group mb-3">
        <label for="mobile">Telefonnummer:</label>
        <input type="text" class="form-control" id="mobile" name="mobile" value="<?php echo isset($talent->post_code) ? $talent->post_code : ''; ?>">
    </div>
    <div class="form-group mb-1">
        <label for="post_code">PLZ:</label>
        <input type="text" class="form-control" id="post_code" name="post_code" value="<?php echo isset($talent->post_code) ? $talent->post_code : ''; ?>">
    </div>
    <button type="submit" class="btn btn-primary"><?php echo 'Neues Talent anlegen'; ?></button>
</form>
<div class="row d-flex justify-content-end">
<div id="form-message"></div>
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
                    formChanged = false;
                    $('#form-message').text('Erfolgreich gespeichert');
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
