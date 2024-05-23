<form id="edit-job-form" method="post">
    <?php if(isset($job->ID)): ?>
        <input type="hidden" name="job_id" value="<?php echo $job->ID; ?>">
    <?php endif; ?>
    <div class="form-group mb-3">
        <label for="customer_id"><strong>Kunde</strong></label>
        <select class="form-control" id="customer_id" name="customer_id" required>
            <option value="">Wählen Sie einen Kunden</option>
            <?php foreach($customers as $customer): ?>
                <option value="<?php echo $customer->ID; ?>" <?php echo (isset($job->customer_id) && $job->customer_id == $customer->ID) ? 'selected' : ''; ?>>
                    <?php echo esc_html($customer->company_name); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group mb-3">
        <label for="job_title"><strong>Job-Titel</strong></label>
        <input type="text" class="form-control" id="job_title" name="job_title" value="<?php echo isset($job->job_title) ? esc_attr($job->job_title) : ''; ?>" required>
    </div>
    <div class="form-group mb-3">
        <label for="post_code"><strong>Postleitzahl</strong></label>
        <input type="text" class="form-control" id="post_code" name="post_code" value="<?php echo isset($job->post_code) ? esc_attr($job->post_code) : ''; ?>">
    </div>
    <div class="form-group mb-3">
        <label for="school"><strong>benötigter Schulabschluss:</strong></label>
        <select class="form-select" id="school" name="school" required>
        <option value="0" <?php echo (isset($job->school) && $job->school == 0) ? 'selected' : ''; ?> >Kein Abschluss</option>
        <option value="1" <?php echo (isset($job->school) && $job->school == 1) ? 'selected' : ''; ?> >Hauptschulabschluss</option>
        <option value="2" <?php echo (isset($job->school) && $job->school == 2) ? 'selected' : ''; ?> >Realschulabschluss und vergleichbare Schulabschlüsse</option>
        <option value="3" <?php echo (isset($job->school) && $job->school == 3) ? 'selected' : ''; ?> >Fachhochschulreife</option>
        <option value="4" <?php echo (isset($job->school) && $job->school == 4) ? 'selected' : ''; ?> >Abitur</option>
        </select>
    </div>
    <div class="form-check mb-1">
    <input class="form-check-input" type="checkbox" id="license" name="license" <?php echo isset($job->license) && $job->license ? 'checked' : ''; ?>>
        <label class="form-check-label" for="license">Führerhschein (Klasse B)</label>
    </div>
    <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" id="home_office" name="home_office" <?php echo isset($job->home_office) && $job->home_office ? 'checked' : ''; ?>>
        <label class="form-check-label" for="home_office">Home Office möglich</label>
    </div>
    <div class="form-group mb-3">
        <label for="field">Verfügbarkeit:</label><?php echo info_button('jobs_availability'); ?>
        <select class="form-select" id="availability" name="availability" required>
        <?php for ($i = 0; $i <= 7; $i++) : ?>
            <?php $selectedAvailability= (isset($job->availability) && $job->availability == $i) ? 'selected' : ''; ?>
            <option value="<?php echo $i; ?>" <?php echo $selectedAvailability; ?>><?php echo get_availability_string($i); ?></option>
        <?php endfor; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary"><?php echo isset($job->ID) ? 'Änderungen speichern' : 'Neuen Job anlegen'; ?></button>
</form>
<div id="form-message"></div>
<script>
jQuery(document).ready(function($) {
    $('#edit-job-form').submit(function(e) {
        e.preventDefault(); // Verhindert das Standard-Formular-Verhalten
        
        var formData = $(this).serialize(); // Serialisiert die Formulardaten
        
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: formData + '&action=edit_job', // Fügt die Aktion hinzu
            success: function(response) {
                // Erfolgsfall: Weiterleitung oder Anzeige einer Erfolgsmeldung
                console.log(response);
                $('#form-message').html(response.data);
            },
            error: function(xhr, status, error) {
                // Fehlerfall: Anzeige einer Fehlermeldung
                console.error('Fehler beim Speichern der Jobdaten:', error);
            }
        });
    });
});
</script>
