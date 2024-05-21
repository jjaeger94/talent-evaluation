<div class="card mb-3">
    <div class="card-body">
        <div class="row">
            <div class="col">
                <?php if ($talent->member_id) : ?>
                    <button id="activateAccount" class="btn btn-primary">Email erneut senden</button>
                <?php else: ?>
                    <button id="createUser" class="btn btn-primary">Nutzer anlegen</button>
                <?php endif; ?>
            </div>
            <div class="col">
                <?php if (!$talent->member_id) : ?>
                    <button id="removeTalent" class="btn btn-danger">Eintrag entfernen</button>
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
    $('#removeTalent').click(()=>{
        // AJAX-Anfrage senden
        $.ajax({
            type: 'POST',
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: 'talent_id=<?php echo $talent->ID; ?>&action=remove_talent',
            success: function(response) {
                // Erfolgreiche Verarbeitung
                console.log(response);
                // Seite neu laden, um die aktualisierten Daten anzuzeigen
                if(response.success){
                    window.location.href = '<?php echo home_url('/talents/');?>';
                }
            },
            error: function(xhr, status, error) {
                // Fehlerbehandlung
                console.error(error);
            }
        });
    });

    $('#createUser').click(()=>{
        // AJAX-Anfrage senden
        $.ajax({
            type: 'POST',
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: 'talent_id=<?php echo $talent->ID; ?>&action=create_user',
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