<?php if ( $job ) : ?>
    <div class="application-details">
        <div class="row">
            <div class="col-md-6">
                <h2><?php echo esc_html( $job->job_title ); ?></h2>
                <p><?php echo $job->location; ?><br>
                Erstellt am: <?php echo date('d.m.Y', strtotime($job->added)); ?></p>
                <p><a href="<?php echo esc_url(home_url('/kandidaten/?job_id=' . $job->ID)); ?>">Kandidaten anzeigen</a></p>
            </div>
            <div class="col-md-6 d-flex justify-content-center align-items-center">
                <?php if ($job->state == 'active') : ?>
                    <button class="btn btn-danger job-state-btn" value="inactive">Stelle deaktivieren</button>
                <?php elseif ($job->state == 'inactive') : ?>
                    <button class="btn btn-success job-state-btn" value="active">Stelle aktivieren</button>
                <?php endif; ?>
                <!-- Weitere Aktionen je nach Status hier einfügen -->
            </div>
        </div>
        <hr>
        <?php include 'blocks/job-info-template.php'; ?>
    </div>
<?php else : ?>
    <div class="alert alert-warning" role="alert">Es wurden keine Stellendetails gefunden.</div>
<?php endif; ?>
<script>
jQuery(document).ready(function($) {
    $('.job-state-btn').click(function() {
			// Extrahieren Sie die application_id aus dem URL-Parameter
			var requestData = {
				action: 'change_state',
				job_id: <?php echo $job->ID ?>,
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
