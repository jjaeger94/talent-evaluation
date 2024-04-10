<div class="row">
    <div class="col-md-4">
        <p><strong>Background screening:</strong></p>
        <?php if ($review->screening == 0) : ?>
            <p>In Prüfung</p>
        <?php elseif ($review->screening == 1) : ?>
            <p>Nicht erfüllt</p>
        <?php elseif ($review->screening == 2) : ?>
            <p>Keine Rückmeldung</p>
        <?php elseif ($review->screening == 3) : ?>
            <p>Erfüllt</p>
        <?php endif; ?>
    </div>
    <div class="col-md-8 d-flex justify-content-center align-items-center">
        <?php if ($review->screening == -1) : ?>
            <!-- Wenn die Kriterien noch nicht überprüft wurden -->
            <button class="btn btn-success set-review-btn" data-type="screening" value="0">Überprüfung starten</button>
        <?php elseif ($review->screening == 0) : ?>
            <!-- Wenn die Kriterien überprüft wurden, aber noch nicht bewertet wurden -->
            <button class="btn btn-danger set-review-btn" data-comment="true" data-type="screening" value="1">Nicht erfüllt</button>
            <button class="btn btn-warning set-review-btn" data-comment="true" data-type="screening" value="2">Keine Rückmeldung</button>
            <button class="btn btn-success set-review-btn" data-type="screening" value="3">Erfüllt</button>
        <?php elseif ($review->screening > 0) : ?>
            <button class="btn btn-success set-review-btn" data-comment="true" data-type="screening" value="0">Zurücksetzen</button>
        <?php endif; ?>
    </div>
</div>

