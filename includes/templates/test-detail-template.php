<h1><?php echo $test->title; ?></h1>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Fragetext</th>
            <th>Bearbeiten</th>
            <th>Löschen</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($questions as $question) : ?>
            <tr>
                <td><?php echo $question->ID; ?></td>
                <td><?php echo $question->question_text; ?></td>
                <td><a href="#" class="edit-question" data-question-id="<?php echo $question->ID; ?>">Bearbeiten</a></td>
                <td><a href="#" class="delete-question" data-question-id="<?php echo $question->ID; ?>">Löschen</a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<button id="add-question">Neue Frage hinzufügen</button>