<?php if (!$application->review_id) : ?>
    <?php // Hier können Sie etwas anzeigen, wenn keine Überprüfung vorliegt ?>
<?php elseif ($application->review->commitment == -1) : ?>
    In Prüfung
<?php else : ?>
    <?php
    $commitment_score = $application->review->commitment;
    $commitment_text = '';

    if ($commitment_score >= 0 && $commitment_score <= 3) {
        $commitment_text = 'Kein Commitment';
    } elseif ($commitment_score >= 4 && $commitment_score <= 8) {
        $commitment_text = 'Durchschnittliches Commitment';
    } elseif ($commitment_score >= 9 && $commitment_score <= 10) {
        $commitment_text = 'Hohes Commitment';
    }

    echo esc_html($commitment_score) . ' / 10 → ' . $commitment_text;
    ?>
<?php endif; ?>
