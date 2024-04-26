<?php if ( $application ) : ?>
    <div class="application-details">
        <div class="row">
            <div class="col-md-6">
                <h2><?php
                    $salutation = '';
                    if ($application->salutation == 1) {
                        $salutation = 'Herr ';
                    } elseif ($application->salutation == 2) {
                        $salutation = 'Frau ';
                    }
                    echo esc_html($salutation . $application->prename . ' ' . $application->surname);
                ?></h2>
                <p><?php echo esc_html($application->email); ?></p>
            </div>
            <div class="col-md-6 d-flex justify-content-center align-items-center">
                <?php if ($application->state == 'new') : ?>
                    <button id="review-btn-start" class="btn btn-success" value="in_progress">Prüfung starten</button>
                <?php elseif ($application->state == 'in_progress') : ?>
                    <button class="btn btn-warning review-btn" data-comment="true" value="waiting">Prüfung pausieren</button>
                    <button class="btn btn-danger review-btn" data-comment="true" value="failed">Durchgefallen</button>
                    <button class="btn btn-success review-btn" value="passed">Bestanden</button>
                <?php elseif ($application->state == 'waiting') : ?>
                    <button class="btn btn-success review-btn" value="in_progress">Prüfung fortsetzen</button>
                <?php elseif ($application->state == 'passed' || $application->state == 'failed') : ?>
                    <button class="btn btn-success review-btn" data-comment="true" value="in_progress">Prüfung erneut starten</button>
                <?php endif; ?>
                <!-- Weitere Aktionen je nach Status hier einfügen -->
            </div>
        </div>
        <hr>
        <?php include 'blocks/job-info-template.php'; ?>
        <hr>
        <?php if ($application->review_id) : ?>
			<?php if ( show_all_features() ) : ?>
            <?php include 'blocks/consent-template.php'; ?>
            <hr>
            <?php include 'blocks/criteria-template.php'; ?>
            <hr>
            <?php include 'blocks/completeness-template.php'; ?>
            <hr>
            <?php include 'blocks/screening-template.php'; ?>
            <hr>
			<?php endif;?>
            <?php include 'blocks/commitment-template.php'; ?>
            <hr>
        <?php endif; ?>
		<?php if ( show_all_features() ) : ?>
        <p><strong>Hochgeladene Dateien:</strong></p>
        <?php include 'blocks/file-template.php'; ?>
        <button id="add-files-button" class="btn btn-primary">Dateien hinzufügen</button>
        <hr>
		<?php endif; ?>
        <p><strong>Backlog:</strong></p>
        <!-- Container für den Backlog-Inhalt -->
        <div id="backlog-container"></div>
        <!-- Button, der den Inhalt laden soll -->
        <button id="load-backlog-button" class="btn btn-primary">Backlog laden</button>
    </div>
<?php else : ?>
    <div class="alert alert-warning" role="alert">Es wurden keine Bewerbungsdetails gefunden.</div>
<?php endif; ?>
<script>
jQuery(document).ready(function($) {
    $('#add-files-button').click(function() {
				// Hier können Sie den gewünschten Code einfügen, um weitere Dateien hinzuzufügen
				// Z.B. öffnen Sie einen Dateiauswahldialog
				var fileInput = document.createElement('input');
				fileInput.type = 'file';
				fileInput.accept = 'application/pdf';
				fileInput.multiple = true; // Erlaubt das Hochladen mehrerer Dateien
				fileInput.click(); // Klicken Sie auf das Eingabefeld, um den Dateiauswahldialog zu öffnen
	
				// Event-Listener für den Dateiauswahldialog
				fileInput.addEventListener('change', function() {
					var files = fileInput.files;
					var formData = new FormData();
	
					// Fügen Sie die ausgewählten Dateien dem FormData-Objekt hinzu
					for (var i = 0; i < files.length; i++) {
						formData.append('files[]', files[i]);
					}
			
					// Fügen Sie die application_id dem FormData-Objekt hinzu
					formData.append('application_id', <?php echo $application_id ?>);
					formData.append('action', 'add_files');
					$.ajax({
						type: 'POST',
						url: '<?php echo admin_url('admin-ajax.php'); ?>',
						data: formData,
						processData: false, // Daten nicht verarbeiten (wichtig für FormData)
						contentType: false, // Inhaltstyp nicht festlegen (wichtig für FormData)
						success: function (response) {
							console.log(response); // Anzeigen der Antwortmeldung // Formular zurücksetzen
							location.reload();
						},
						error: function (xhr, status, error) {
							console.error(xhr.responseText);
						}
					});
				});
		});

        $('#review-btn-start').click(function() {

			var requestData = {
				action: 'start_review',
				application_id: <?php echo $application_id ?>,
				state: $(this).val()
			};
		
			$.ajax({
				type: 'POST',
				url: '<?php echo admin_url('admin-ajax.php'); ?>',
				data: requestData,
				dataType: 'json', // Hier können Sie den erwarteten Datenformat angeben
				success: function(response) {
					if (response.success) {
						// Hier können Sie weitere Aktionen ausführen, z.B. die Seite neu laden
						location.reload();
					} else {
						// Fehler bei der Aktualisierung
						alert('Fehler beim Starten der Bearbeitung');
					}
				},
				error: function(xhr, status, error) {
					// AJAX-Fehler
					console.error('AJAX-Fehler:', error);
				}
			});
		});	
});
</script>