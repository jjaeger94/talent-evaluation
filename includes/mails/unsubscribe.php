<div class="container">
    <form id="notificationForm" method="post">
        <input type="hidden" name="email" value="<?php echo $talent->email; ?>">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="1" id="registrationNotification" name="registration" <?php echo has_notification($talent->notifications, NOTIFICATION_REGISTRATION) ? 'checked' : ''; ?>>
            <label class="form-check-label" for="registrationNotification">
                Registrierungserinnerung
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="1" id="newJobsNotification" name="new_jobs" <?php echo has_notification($talent->notifications, NOTIFICATION_NEW_JOBS) ? 'checked' : ''; ?>>
            <label class="form-check-label" for="newJobsNotification">
                Benachrichtigungen über neue Jobs
            </label>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Auswahl speichern</button>
    </form>
    <div class="wrap">
        <span id="notificationResult"></span>
    </div>
</div>
<script>
jQuery(document).ready(function($) {
    $('#notificationForm').submit(function(e) {
        e.preventDefault(); // Verhindert das Standardformulareinreichungsverhalten

        // Formulardaten sammeln
        var formData = $(this).serialize();       

        var data = formData + '&action=save_notifications';

        // AJAX-Anfrage senden
        $.ajax({
            type: 'POST',
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: data,
            success: function(response) {
                // Erfolgreiche Verarbeitung
                $('#notificationResult').text(response.data);
 
                // Hier können Sie je nach Bedarf weitere Aktionen ausführen
            },
            error: function(xhr, status, error) {
                // Fehlerbehandlung
                $('#notificationResult').text(error);
            }
        });
    });
});
</script>