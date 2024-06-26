<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Vorname</th>
                <th>Nachname</th>
                <th>Matching</th>
                <th>Talentdetails</th>
                <th>Teilzeit erwünscht</th>
                <th>Aktionen</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($talents as $talent) : ?>
                <tr>
                    <td><?php echo $talent->prename; ?></td>
                    <td><?php echo $talent->surname; ?></td>
                    <td>
                        <span class="matching-status">
                            <?php $matching = get_matching_for_ids($talent->ID, $job->ID); echo $matching ? get_matching_state($matching->value) : 'Nicht gestartet'; ?>
                        </span>
                    </td>
                    <td><a href="<?php echo esc_url(home_url('/compare-details/?talent_id=' . $talent->ID . '&job_id=' . $job->ID)); ?>">Details</a></td>
                    <td><?php echo $talent->part_time ? 'Ja' : 'Nein'; ?></td>
                    <td>
                        <?php if (!$matching) : ?>
                            <form class="activateMatchingForm" method="post">
                                <input type="hidden" name="talent_id" value="<?php echo $talent->ID; ?>">
                                <input type="hidden" name="job_id" value="<?php echo $job->ID; ?>">
                                <button type="submit" class="btn btn-primary">Matching beginnen</button>
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
                    $form.closest('tr').find('.matching-status').text('Noch nicht bearbeitet');
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
