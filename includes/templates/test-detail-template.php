<div class="container">
    <div class="row">
        <!-- Titel und Affiliate-Link -->
        <div class="col-md-9">
            <form method="post">
                <div class="mb-3">
                    <label for="test-title" class="form-label">Titel:</label>
                    <input type="text" class="form-control" id="test-title" name="test_title" value="<?php echo esc_attr($test->title); ?>">
                </div>
                <div class="mb-3">
                    <label for="book-title" class="form-label">Buchtitel:</label>
                    <input type="text" class="form-control" id="book-title" name="book_title" value="<?php echo esc_attr($test->book_title); ?>">
                </div>
                <div class="mb-3">
                    <label for="affiliate-link" class="form-label">Affiliate-Link:</label>
                    <input type="text" class="form-control" id="affiliate-link" name="affiliate_link" value="<?php echo esc_url($test->affiliate_link); ?>">
                </div>
                <div class="mb-3">
                    <label for="image-link" class="form-label">Cover-Bild:</label>
                    <input type="text" class="form-control" id="image-link" name="image_link" value="<?php echo esc_url($test->image_link); ?>">
                </div>
                <button id="save-test-details" type="submit" class="btn btn-primary">Änderungen speichern</button>
            </form>
        </div>
        <!-- Bild -->
        <div class="col-md-3 text-right">
            
            <?php if (!empty($test->image_link)) : ?>
                <img src="<?php echo esc_url($test->image_link); ?>" alt="Bild" style="max-height: 200px;">
            <?php endif; ?>
        </div>
    </div>
    <!-- Container für Fehler- oder Erfolgsmeldungen -->
<div id="form-message" class="mt-3"></div>
<hr>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Fragetext</th>
                <th>Bearbeiten</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($questions as $question) : ?>
                <tr>
                    <td><?php echo $question->ID; ?></td>
                    <td><?php echo $question->question_text; ?></td>
                    <td><a href="<?php echo esc_url(home_url('/frage-details/?tid=' . $test->ID . '&qid=' . $question->ID)); ?>" class="edit-question" data-question-id="<?php echo $question->ID; ?>">Bearbeiten</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="<?php echo esc_url(home_url('/frage-details/?tid=' . $test->ID)); ?>" id="add-question">Neue Frage hinzufügen</a>
</div>
<script>
    jQuery(document).ready(function($) {
        $('#save-test-details').click(function(e) {
            e.preventDefault(); // Verhindert das Standardverhalten des Formulars (Neuladen der Seite)

            // Formulardaten serialisieren
            var formData = {
                'test_id': <?php echo $test->ID; ?>,
                'test_title': $('#test-title').val(),
                'book_title': $('#book-title').val(),
                'affiliate_link': $('#affiliate-link').val(),
                'image_link': $('#image-link').val(),
                'action': 'save_test_details' // Die hier angegebene Aktion wird auf der Serverseite ausgeführt
            };

            // AJAX-Anfrage senden
            $.ajax({
                type: 'POST',
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                data: formData,
                dataType: 'json', // Hier können Sie den erwarteten Datenformat angeben
                success: function(response) {
                    // Erfolgsfall: Verarbeite die Antwort
                    if (response.success) {
                        location.reload();
                    } else {
                        $('#form-message').html('<div class="alert alert-danger" role="alert">Fehler beim Speichern der Änderungen.</div>');
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
