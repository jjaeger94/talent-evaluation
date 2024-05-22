<button class="btn btn-danger" id="button-delete-thread">Chat löschen</button>
<script>
    jQuery(document).ready(function($) {       
         // Event Listener für den Löschen-Button hinzufügen
         $('#button-delete-thread').click(function(e) {
            e.preventDefault();
            // AJAX-Anfrage senden, um den Thread zu löschen
            $.ajax({
                type: 'POST',
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                data: {
                    action: 'delete_chat'
                },
                success: function(response) {
                    if(response.success){
                        // Erfolgreich: Seite neu laden
                        location.reload();
                    }else{
                        console.error(response.data);
                    }
                },
                error: function(xhr, status, error) {
                    // Fehler: Fehlermeldung anzeigen oder Fehlerbehandlung durchführen
                    console.error(error);
                }
            });
        });
    });
</script>