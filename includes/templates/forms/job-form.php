<form id="add-job-form" class="bootstrap-form" method="post">
    <div class="form-group mb-3">
        <label for="job-title"><strong class="mr-2">Stellenbezeichnung:</strong></label><?php echo info_button('job_form_title'); ?>
        <input type="text" class="form-control" id="job-title" name="job_title" required>
    </div>
    <div class="form-group mb-3">
        <label for="location"><strong class="mr-2">Standort:</strong></label><?php echo info_button('job_form_location'); ?>
        <input type="text" class="form-control" id="location" name="location">
    </div>
    <div class="form-group mb-3">
        <label for="criteria1"><strong class="mr-2">Kriterien zur Vorauswahl:</strong></label><?php echo info_button('job_form_criteria'); ?>
        <input type="text" class="form-control" id="criteria1" name="criteria1">
        <input type="text" class="form-control mt-2" id="criteria2" name="criteria2">
        <input type="text" class="form-control mt-2" id="criteria3" name="criteria3">
    </div>
    <div class="form-group mb-3">
        <label for="completeness1"><strong class="mr-2">Vollständigkeits Check</strong></label><?php echo info_button('job_form_completeness'); ?>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="completeness1" name="completeness1" value="0">
            <label class="form-check-label" for="completeness1">Zeugnisse auf Vollständigkeit prüfen</label>
        </div>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="completeness2" name="completeness2" value="0">
            <label class="form-check-label" for="completeness2">Arbeitszeugnisse auf Vollständigkeit prüfen</label>
        </div>
    </div>
    <div class="form-group mb-3">
        <label for="screening1"><strong class="mr-2">Screening</strong></label><?php echo info_button('job_form_screening'); ?>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="screening1" name="screening1" value="0">
            <label class="form-check-label" for="screening1">LinkedIn checken</label>
        </div>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="screening2" name="screening2" value="0">
            <label class="form-check-label" for="screening2">Höchstes Bildungszeugnis prüfen</label>
        </div>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="screening3" name="screening3" value="0">
            <label class="form-check-label" for="screening3">Arbeitszeugnis prüfen</label>
        </div>
    </div>
    <input type="submit" value="Stelle hinzufügen" class="btn btn-primary">
</form>

<!-- Container für Fehler- oder Erfolgsmeldungen -->
<div id="form-message" class="mt-3"></div>
<script>
jQuery(document).ready(function($) {
    $('#add-job-form').submit(function(e) {
			e.preventDefault();
	
			// Formulardaten serialisieren
			var formData = $(this).serialize();
	
			// Ajax-Anfrage senden
			$.ajax({
				type: 'POST',
				url: '<?php echo admin_url('admin-ajax.php'); ?>', // Verwende die global definierte ajaxurl
				data: formData + '&action=add_job', // Daten und Aktion hinzufügen
				success: function(response) {
					// Antwort verarbeiten
					$('#form-message').html(response);
					$('#add-job-form')[0].reset();
				},
				error: function(xhr, status, error) {
					console.error(error);
				}
			});
		});
});
</script>
