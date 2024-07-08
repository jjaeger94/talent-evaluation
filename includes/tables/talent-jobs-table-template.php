<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Stelle</th>
                <th>Unternehmen</th>
                <th>Status</th>
                <th>Talentdetails</th>
                <th>Aktionen</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($jobs as $job) : ?>
                <tr>
                    <td><?php echo $job->job_title; ?></td>
                    <td><?php echo isset($job->company) && $job->company != '' ? $job->company : $job->company_name; ?></td>
                    <td>
                        <span class="matching-status">
                            <?php $matching = get_matching_for_ids($talent->ID, $job->ID); echo $matching ? get_matching_state($matching->value) : 'Nicht gestartet'; ?>
                        </span>
                    </td>
                    <td><a href="<?php echo esc_url(home_url('/compare-details/?talent_id=' . $talent->ID . '&job_id=' . $job->ID)); ?>">Details</a></td>
                    <td>
                        <?php if (!$matching) : ?>
                            <form class="activateMatchingForm" method="post">
                                <input type="hidden" name="talent_id" value="<?php echo $talent->ID; ?>">
                                <input type="hidden" name="job_id" value="<?php echo $job->ID; ?>">
                                <button type="submit" class="btn btn-primary">Vormerken</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
jQuery(document).ready(function($) {
    $('.activateMatchingForm').submit(function(e) {
        e.preventDefault(); // Verhindert das Standardformulareinreichungsverhalten
        let $form = $(this);
        var formData = $form.serialize();
        // AJAX-Anfrage senden
        $.ajax({
            type: 'POST',
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: formData + '&action=activate_matching',
            success: function(response) {
                // Erfolgreiche Verarbeitung
                console.log(response);
                // Seite neu laden, um die aktualisierten Daten anzuzeigen
                if(response.success){
                    // Button ausblenden
                    $form.find('button[type="submit"]').hide();
                    // Matching-Status aktualisieren
                    $form.closest('tr').find('.matching-status').text('Vorgemerkt');
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
