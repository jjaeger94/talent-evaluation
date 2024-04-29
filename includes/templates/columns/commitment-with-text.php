<?php if (!$application->review_id) : ?>
    <?php // Hier können Sie etwas anzeigen, wenn keine Überprüfung vorliegt ?>
<?php elseif ($application->review->commitment == -1) : ?>
    In Prüfung
<?php else : ?>
    <?php
    $commitment_score = $application->review->commitment;
    $commitment_text = '';

    if ($commitment_score >= 0 && $commitment_score <= 50) {
        $commitment_text = 'Kein Commitment';
    } elseif ($commitment_score >= 51 && $commitment_score <= 80) {
        $commitment_text = 'Durchschnittliches Commitment';
    } elseif ($commitment_score >= 81 && $commitment_score <= 100) {
        $commitment_text = 'Hohes Commitment';
    }

    echo esc_html($commitment_score) . ' % → ' . $commitment_text;
    ?>
<?php endif; ?>
