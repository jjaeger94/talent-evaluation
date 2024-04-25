<div class="container" id="test-container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="test-start-container">
                <?php if (isset($application)) : ?>
                    <div class="alert alert-success" role="alert">
                        Willkommen, <?php echo esc_html($application->prename) . ' ' . esc_html($application->surname); ?>!
                    </div>
                <?php endif; ?>
                <form id="test-answers-form">
                    <input type="hidden" name="jid" value="<?php echo esc_attr($jid); ?>">
                    <input type="hidden" name="tid" value="<?php echo esc_attr($tid); ?>">
                    <input type="hidden" name="key" value="<?php echo esc_attr($key); ?>">
                    <?php if (isset($application)) : ?>
                        <input type="hidden" name="aid" value="<?php echo esc_attr($application->ID); ?>">
                    <?php endif; ?>
                    <?php if (!isset($application)) : ?>
                        <div class="mb-3">
                            <label for="prename" class="form-label">Vorname:</label>
                            <input type="text" class="form-control" id="prename" name="prename" required>
                        </div>
                        <div class="mb-3">
                            <label for="surname" class="form-label">Nachname:</label>
                            <input type="text" class="form-control" id="surname" name="surname" required>
                        </div>
                    <?php endif; ?>
                    <?php foreach ($questions as $question) : ?>
                        <div class="mb-3">
                            <label for="answer_<?php echo $question->ID; ?>" class="form-label"><?php echo $question->question_text; ?></label>
                            <textarea class="form-control" id="answer_<?php echo $question->ID; ?>" name="answers[<?php echo $question->ID; ?>]" rows="4" required></textarea>
                        </div>
                    <?php endforeach; ?>
                    <button type="submit" class="btn btn-primary">Test abschließen</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
jQuery(document).ready(function($) {
    $('#test-answers-form').submit(function(e) {
        e.preventDefault(); // Verhindert das Standardverhalten des Formulars (Neuladen der Seite)

        // Formulardaten direkt aus dem Formular extrahieren
        var formData = $(this).serialize();

        // AJAX-Anfrage senden
        $.ajax({
            type: 'POST',
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: formData + '&action=process_test_answers', // Übergeben Sie die direkt aus dem Formular extrahierten Daten
            success: function(response) {
                // Erfolgsfall: Verarbeite die Antwort
                console.log(response);
                if(response.success){
                    $('#test-container').html('Das wars schon, du kannst die Seite jetzt schließen.');
                }  
            },
            error: function(xhr, status, error) {
                // Fehlerfall: Behandele den Fehler
                console.error(error);
            }
        });
    });
});

</script>