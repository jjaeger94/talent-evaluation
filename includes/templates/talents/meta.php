<div class="card mb-3">
    <div class="card-body">
        <div class="row">
            <div class="col">
            <p><strong>Hinzugefügt:</strong> <?php echo date('d.m.Y', strtotime($talent->added)); ?></p>
            </div>
            <div class="col">
            <p><strong>Bearbeitet:</strong> <?php echo date('d.m.Y', strtotime($talent->edited)); ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col">
            <p><strong>Ref:</strong> <?php echo $talent->ref; ?></p>
            </div>
            <div class="col">
            <?php if ($talent->member_id) : ?>
                <p><strong>member_id:</strong> <?php echo $talent->member_id; ?></p>
            <?php else: ?>
                <button id="createUser" class="btn btn-primary">Nutzer anlegen</button>
            <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script>
jQuery(document).ready(function($) {
    // AJAX-Anfrage zum Hinzufügen einer neuen Schule
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
                // if(response.success){
                //     location.reload();
                // }
            },
            error: function(xhr, status, error) {
                // Fehlerbehandlung
                console.error(error);
            }
        });
    });
});
</script>