<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($talents as $talent) : ?>
                <tr>
                    <td><a href="<?php echo esc_url(home_url('/talent-details/?id=' . $talent->ID)); ?>"><?php echo $talent->prename . ' ' . $talent->surname; ?></a></td>
                    <td>
                        <span class="matching-status">
                            <?php $preference = get_preference_for_ids($talent->ID, $job->ID); echo $preference ? get_preference_state($preference->value) : get_preference_state(null); ?>
                        </span>
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
