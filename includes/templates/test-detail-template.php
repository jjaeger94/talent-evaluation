<h1><?php echo $test->title; ?></h1>

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
                <td><a href="<?php echo esc_url( home_url( '/frage-details/?tid=' . $test->ID . '&qid=' . $question->ID ) ); ?>" class="edit-question" data-question-id="<?php echo $question->ID; ?>">Bearbeiten</a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<a href="<?php echo esc_url( home_url( '/frage-details/?tid=' . $test->ID ) ); ?>" id="add-question">Neue Frage hinzufügen</a>
