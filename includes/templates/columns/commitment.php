<?php if (!$application->review_id) : ?>
<?php elseif ($application->review->commitment == -1) : ?>
In Prüfung
<?php else : ?>
<?php echo esc_html($application->review->commitment) . '%'; ?>
<?php endif; ?>