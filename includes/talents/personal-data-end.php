<button form="talentDetailForm" type="submit" class="btn btn-success mt-2">Daten speichern</button>
<div class="wrap">
    <span id="personal-data-result"></span>
</div>
</div>
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