<div class="row">
    <div class="col-md-4">
        <p><strong>Vollständigkeit:</strong></p>
        <?php if ($review->completeness == 0) : ?>
            <p>In Prüfung</p>
        <?php elseif ($review->completeness == 1) : ?>
            <p>Nicht erfüllt</p>
        <?php elseif ($review->completeness == 3) : ?>
            <p>Erfüllt</p>
        <?php endif; ?>
    </div>
    <div class="col-md-8 d-flex justify-content-center align-items-center">
        <?php if ($review->completeness == -1) : ?>
            <!-- Wenn die Kriterien noch nicht überprüft wurden -->
            <button class="btn btn-success completeness-btn" value="0">Überprüfung starten</button>
        <?php elseif ($review->completeness == 0) : ?>
            <!-- Wenn die Kriterien überprüft wurden, aber noch nicht bewertet wurden -->
            <button class="btn btn-danger completeness-btn" value="1">Nicht erfüllt</button>
            <button class="btn btn-success completeness-btn" value="3">Erfüllt</button>
        <?php elseif ($review->completeness > 0) : ?>
            <button class="btn btn-success completeness-btn" value="0">Zurücksetzen</button>
        <?php endif; ?>
    </div>
</div>
