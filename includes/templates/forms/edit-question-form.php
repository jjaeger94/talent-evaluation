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
</form>

<!-- Container für Fehler- oder Erfolgsmeldungen -->
<div id="form-message" class="mt-3"></div>