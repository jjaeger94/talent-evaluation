<?php if (!$candidate->review_id) : ?>
<?php elseif ($candidate->review->commitment == -1) : ?>
In Prüfung
<?php else : ?>
<?php echo esc_html($candidate->review->commitment) . ' / 10'; ?>
<?php endif; ?>