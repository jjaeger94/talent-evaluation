<?php if (!$application->review_id) : ?>
<!-- Keine Anzeige für -1 -->
<?php elseif ($application->review->criteria == 0) : ?>
<div class="circle gray"></div>
<?php elseif ($application->review->criteria == 1) : ?>
<div class="circle red"></div>
<?php elseif ($application->review->criteria == 2) : ?>
<div class="circle yellow"></div>
<?php elseif ($application->review->criteria == 3) : ?>
<div class="circle green"></div>
<?php endif; ?>