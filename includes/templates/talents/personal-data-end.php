<button form="talentDetailForm" type="submit" class="btn btn-primary mt-2">Daten speichern</button>
</div>
</div>
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