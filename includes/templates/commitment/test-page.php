<div class="test-start-container">
    <?php if (isset($application)) : ?>
        <p>Willkommen, <?php echo esc_html($application->prename) . ' ' . esc_html($application->surname); ?>!</p>
    <?php endif; ?>
    <form id="test-answers-form">
        <?php if (!isset($application)) : ?>
            <div class="form-group">
                <label for="prename">Vorname:</label>
                <input type="text" class="form-control" id="prename" name="prename" required>
            </div>
            <div class="form-group">
                <label for="surname">Nachname:</label>
                <input type="text" class="form-control" id="surname" name="surname" required>
            </div>
        <?php endif; ?>
        <?php foreach ($questions as $question) : ?>
            <div class="form-group">
                <label for="answer_<?php echo $question->ID; ?>"><?php echo $question->question_text; ?></label>
                <input type="text" class="form-control" id="answer_<?php echo $question->ID; ?>" name="answers[]" required>
            </div>
        <?php endforeach; ?>
        <button type="submit" class="btn btn-primary">Test abschließen</button>
    </form>
</div>
