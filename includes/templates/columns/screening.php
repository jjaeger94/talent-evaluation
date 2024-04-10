<?php if (!$candidate->review_id) : ?>
<!-- Keine Anzeige für -1 -->
<?php elseif ($candidate->review->screening == 0) : ?>
<div class="circle gray"></div>
<?php elseif ($candidate->review->screening == 1) : ?>
<div class="circle red"></div>
<?php elseif ($candidate->review->screening == 2) : ?>
<div class="circle yellow"></div>
<?php elseif ($candidate->review->screening == 3) : ?>
<div class="circle green"></div>
<?php endif; ?>