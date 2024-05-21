<div class="card mb-3">
    <div class="card-body">
        <div class="row">
            <div class="col">
                <?php if (!$talent->member_id) : ?>
                    <p>"Bitte lege zuerst den Nutzer an um eine Email zu senden"</p>
                <?php else: ?>
                    <button id="activateAccount" class="btn btn-primary">Email erneut senden</button>
                <?php endif; ?> 
            </div>
        </div>
        <div class="wrap">
            <span id="send-mail-result"></span>
		</div>
    </div>
</div>
<script>
jQuery(document).ready(function($) {
    // AJAX-Anfrage zum Hinzufügen einer neuen Schule
    $('#activateAccount').click(()=>{
        // AJAX-Anfrage senden
        $.ajax({
            type: 'POST',
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: 'member_id=<?php echo $talent->member_id; ?>&action=send_activate_account_mail',
            success: function(response) {
                // Erfolgreiche Verarbeitung
                console.log(response);
                // Seite neu laden, um die aktualisierten Daten anzuzeigen
                if(response.success){
                    $('#send-mail-result').text(response.data);
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