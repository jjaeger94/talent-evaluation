<?php
if ($application->state == 'failed') {
    echo '<span class="w-100" style="font-size: 24px; color: red;"><i class="fas fa-times-circle"></i></span>';
} elseif ($application->state == 'passed') {
    echo '<span class="w-100" style="font-size: 24px; color: green;"><i class="fas fa-check-circle"></i></span>';
} elseif ($application->state == 'waiting') {
    echo '<span>In Wartestellung</span>';
} elseif ($application->state == 'in_progress') {
    echo '<span>In Bearbeitung</span>';
}
?>