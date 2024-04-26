<div class="container">
    <div class="row">
        <!-- Titel und Affiliate-Link -->
        <div class="col-md-9">
            <h1><?php echo $test->title; ?></h1>
            <h5><?php echo $test->book_title; ?></h5>
            <p>Affiliate-Link: <a href="<?php echo esc_url($test->affiliate_link); ?>"><?php echo esc_url($test->affiliate_link); ?></a></p>
        </div>
        <!-- Bild -->
        <div class="col-md-3 text-right">
            <?php if (!empty($test->image_link)) : ?>
                <img src="<?php echo esc_url($test->image_link); ?>" alt="Bild" style="max-height: 200px;">
            <?php endif; ?>
        </div>
    </div>

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
