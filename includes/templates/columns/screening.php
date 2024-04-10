<?php if (!$application->review_id) : ?>
<!-- Keine Anzeige für -1 -->
<?php elseif ($application->review->screening == 0) : ?>
<div class="circle gray"></div>
<?php elseif ($application->review->screening == 1) : ?>
<div class="circle red"></div>
<?php elseif ($application->review->screening == 2) : ?>
<div class="circle yellow"></div>
<?php elseif ($application->review->screening == 3) : ?>
<div class="circle green"></div>
<?php endif; ?>