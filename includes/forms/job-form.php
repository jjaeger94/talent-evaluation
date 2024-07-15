<form id="edit-job-form" method="post">
    <?php if(isset($job->ID)): ?>
        <input type="hidden" name="job_id" value="<?php echo $job->ID; ?>">
    <?php endif; ?>
    <div class="form-group mb-3">
        <label for="customer_id"><strong>Kunde</strong></label>
        <?php 
        $selected_customer_id = isset($_GET['customer_id']) ? intval($_GET['customer_id']) : (isset($job->customer_id) ? $job->customer_id : '');
        ?>

        <select class="form-select" id="customer_id" name="customer_id" required>
            <option value="">Wählen Sie einen Kunden</option>
            <?php foreach($customers as $customer): ?>
                <option value="<?php echo $customer->ID; ?>" <?php echo ($selected_customer_id == $customer->ID) ? 'selected' : ''; ?>>
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
        <label for="company"><strong>Unternehmen</strong></label>
        <input type="text" class="form-control" id="company" name="company" value="<?php echo isset($job->company) ? esc_attr($job->company) : ''; ?>">
    </div>
    <div class="form-group mb-3">
        <label for="notes"><strong>Bearbeitungsstatus</strong></label>
        <input type="text" class="form-control" id="notes" name="notes" value="<?php echo isset($job->notes) ? esc_attr($job->notes) : ''; ?>">
    </div>
    <div class="form-group mb-3">
        <label for="job_url"><strong>Link</strong></label>
        <div class="input-group">
            <input type="text" class="form-control" id="job_url" name="job_url" value="<?php echo isset($job->link) ? esc_attr($job->link) : ''; ?>">
            <div class="input-group-append">
                <button class="btn btn-primary" id="open_link_button" type="button">Öffnen</button>
            </div>
        </div>
    </div>
    <div class="form-group mb-3">
        <label for="post_code"><strong>Postleitzahl</strong></label>
        <input type="text" class="form-control" id="post_code" name="post_code" value="<?php echo isset($job->post_code) ? esc_attr($job->post_code) : ''; ?>">
    </div>
    <div class="form-group mb-3">
        <label for="job_info"><strong>Job-Info</strong></label>
        <textarea rows="3" class="form-control" id="job_info" name="job_info" required><?php echo isset($job->job_info) ? esc_attr($job->job_info) : ''; ?></textarea>
    </div>
    <div class="form-group mb-3">
        <label for="school"><strong>benötigter Schulabschluss:</strong></label>
        <select class="form-select" id="school" name="school" required>
        <option value="0" <?php echo (isset($job->school) && $job->school == 0) ? 'selected' : ''; ?> ><?php echo get_school_degree(0); ?></option>
        <option value="1" <?php echo (isset($job->school) && $job->school == 1) ? 'selected' : ''; ?> ><?php echo get_school_degree(1); ?></option>
        <option value="2" <?php echo (isset($job->school) && $job->school == 2) ? 'selected' : ''; ?> ><?php echo get_school_degree(2); ?></option>
        <option value="3" <?php echo (isset($job->school) && $job->school == 3) ? 'selected' : ''; ?> ><?php echo get_school_degree(3); ?></option>
        <option value="4" <?php echo (isset($job->school) && $job->school == 4) ? 'selected' : ''; ?> ><?php echo get_school_degree(4); ?></option>
        </select>
    </div>
    <div class="form-check mb-1">
    <input class="form-check-input" type="checkbox" id="license" name="license" <?php echo isset($job->license) && $job->license ? 'checked' : ''; ?>>
        <label class="form-check-label" for="license">Führerschein (Klasse B)</label>
    </div>
    <div class="form-check mb-1">
        <input class="form-check-input" type="checkbox" id="home_office" name="home_office" <?php echo isset($job->home_office) && $job->home_office ? 'checked' : ''; ?>>
        <label class="form-check-label" for="home_office">Home Office möglich</label>
    </div>
    <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" id="part_time" name="part_time" <?php echo isset($job->part_time) && $job->part_time ? 'checked' : ''; ?>>
        <label class="form-check-label" for="part_time">Teilzeit möglich</label>
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
<div class="row d-flex justify-content-end">
<?php if(isset($job->ID) && $job->state == 1): ?>
            <div class="col-auto">
                <button id="deactivateJob" class="btn btn-danger">deaktivieren</button>
            </div>
        <?php elseif(isset($job->ID) && $job->state == 0): ?>
            <div class="col-auto">
                <button id="reactivateJob" class="btn btn-primary">reaktivieren</button>
                <button id="removeJob" class="btn btn-danger">Stelle entfernen</button>
            </div>
        <?php endif; ?>
    </div>
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
    $('#open_link_button').on('click', function() {
        var url = $('#job_url').val();
        if (url) {
            window.open(url, '_blank');
        } else {
            alert('Bitte geben Sie einen Link ein.');
        }
    });
    <?php if(isset($job->ID)): ?>
    $('#reactivateJob').click(()=>{
        // AJAX-Anfrage senden
        $.ajax({
            type: 'POST',
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: 'job_id=<?php echo $job->ID; ?>&action=reactivate_job',
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
    $('#deactivateJob').click(()=>{
        // AJAX-Anfrage senden
        $.ajax({
            type: 'POST',
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: 'job_id=<?php echo $job->ID; ?>&action=deactivate_job',
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
    $('#removeJob').click(()=>{
        // AJAX-Anfrage senden
        if (confirm('Eintrag wirklich entfernen?')) {
            $.ajax({
                type: 'POST',
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                data: 'job_id=<?php echo $job->ID; ?>&action=remove_job',
                success: function(response) {
                    // Erfolgreiche Verarbeitung
                    console.log(response);
                    // Seite neu laden, um die aktualisierten Daten anzuzeigen
                    if(response.success){
                        window.location.href = '<?php echo home_url('/jobs/');?>';
                    }
                },
                error: function(xhr, status, error) {
                    // Fehlerbehandlung
                    console.error(error);
                }
            });
}
    });
    <?php endif; ?>
});
</script>
