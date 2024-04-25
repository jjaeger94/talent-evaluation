<form id="edit-question-form">
    <div class="mb-3">
        <label for="question-text" class="form-label">Fragetext:</label>
        <input type="text" class="form-control" id="question-text" name="question_text" value="<?php echo isset($question) ? $question->question_text : ''; ?>" required>
    </div>
    <div class="mb-3">
        <label for="answer-text" class="form-label">Antworttext:</label>
        <input type="text" class="form-control" id="answer-text" name="answer_text" value="<?php echo isset($question) ? $question->answer_text : ''; ?>" required>
    </div>
    <button type="submit" class="btn btn-primary"><?php echo isset($question) ? 'Frage aktualisieren' : 'Neue Frage hinzufügen'; ?></button>
    <?php if (isset($question)) : ?>
        <button type="button" class="btn btn-danger" id="delete-question">Frage löschen</button>
    <?php endif; ?>
</form>

<!-- Container für Fehler- oder Erfolgsmeldungen -->
<div id="form-message" class="mt-3"></div>
<script>
jQuery(document).ready(function($) {
    $('#delete-question').on('click', function() {
        if (confirm('Möchten Sie diese Frage wirklich löschen?')) {
            // Frage-ID aus dem Datenattribut abrufen
        
            <?php if (isset($question_id)) : ?>
                $.ajax({
                    type: 'POST',
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    data: {
                        action: 'delete_question',
                        question_id: <?php echo $question_id ?>
                    },
                    success: function(response) {
                        // Erfolgsfall: Verarbeite die Antwort
                        console.log(response);
                        $('#form-message').html(response);
                        // Aktualisiere die Seite oder führe weitere Aktionen aus
                    },
                    error: function(xhr, status, error) {
                        // Fehlerfall: Behandele den Fehler
                        console.error(error);
                    }
                });
            <?php else : ?>
                console.error('id nicht gefunden')
            <?php endif; ?>
        }
    });
    $('#edit-question-form').on('submit', function(e) {
			e.preventDefault(); // Verhindert das Standardverhalten des Formulars (Neuladen der Seite)
			var formData = $(this).serialize(); // Manuelle Serialisierung des Formulars
			
			// Hinzufügen von zusätzlichen Daten zum FormData
			formData += '&action=edit_question';
			formData += '&test_id=' + <?php echo $test_id ?>;
            <?php if (isset($question_id)) : ?>
			formData += '&question_id=' + <?php echo $question_id ?>;
			<?php endif; ?>
			// AJAX-Anfrage senden
			$.ajax({
				type: 'POST', // Verwendung von POST-Methode
				url: '<?php echo admin_url('admin-ajax.php'); ?>', // Ziel-URL für die AJAX-Anfrage
				data: formData, // Seriatisierung der Formulardaten
				success: function(response) {
					// Erfolgsfall: Verarbeite die Antwort
					console.log(response);
					$('#form-message').html(response);
				},
				error: function(xhr, status, error) {
					// Fehlerfall: Behandele den Fehler
					console.error(error);
				}
			});
		});
});
</script>
