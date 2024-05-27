<div class="card mb-3">
    <div class="card-body">
        <h5 class="card-title"><?php echo $talent->prename . ' ' . $talent->surname; ?></h5>
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
            <?php endif; ?>
            </div>
        </div>
        <div class="row">
        <form id="editNotesForm" method="post">
            <input type="hidden" name="talent_id" value="<?php echo $talent->ID; ?>">
            <div class="form-group mb-3">
                <label for="notes"><strong>Notizen</strong></label>
                <textarea rows="3" class="form-control" id="notes" name="notes"><?php echo isset($talent->notes) ? esc_attr($talent->notes) : ''; ?></textarea>
            </div>
            </form>
        </div>
        <div class="row">
        <div class="col">
            <button form="editNotesForm" type="submit" class="btn btn-success">Notizen speichern</button>
        </div>
        <div class="col">
            <?php if ($talent->member_id) : ?>
                <button id="activateAccount" class="btn btn-primary">Email erneut senden</button>
            <?php else: ?>
                <button id="createUser" class="btn btn-primary">Nutzer anlegen</button>
            <?php endif; ?>
        </div>
        <div class="col">
            <button id="removeTalent" class="btn btn-danger">Eintrag entfernen</button>
        </div>
    </div>
    <div class="wrap">
        <span id="metaResult"></span>
    </div>
    </div>
</div>
<script>
jQuery(document).ready(function($) {
    $('#editNotesForm').submit(function(e) {
        e.preventDefault(); // Verhindert das Standardformulareinreichungsverhalten
        // Formulardaten sammeln
        var formData = $(this).serialize();
        // AJAX-Anfrage senden
        $.ajax({
            type: 'POST',
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: formData + '&action=save_talent_notes',
            success: function(response) {
                // Erfolgreiche Verarbeitung
                console.log(response);
                if(response.success){
                    $('#metaResult').text('Erfolgreich gespeichert');
                }
                // Hier können Sie je nach Bedarf weitere Aktionen ausführen
            },
            error: function(xhr, status, error) {
                // Fehlerbehandlung
                console.error(error);
            }
        });
    });
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
                    $('#metaResult').text(response.data);
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