<div class="row">
    <div class="col-md-4">
        <p><strong>Commitment Test:</strong></p>
        <?php if ($review->commitment == 0) : ?>
            <p>In Prüfung</p>
        <?php elseif ($review->commitment == -1) : ?>
            <p>Noch nicht überprüft</p>
        <?php else : ?>
            <p><?php echo $review->commitment; ?>/10</p>
        <?php endif; ?>
    </div>
    <div class="col-md-8 d-flex justify-content-center align-items-center">
        <?php if ($review->commitment == -1) : ?>
            <!-- Wenn die Prüfung noch nicht gestartet wurde -->
            <button class="btn btn-success commitment-btn" value="0">Prüfung starten</button>
        <?php elseif ($review->commitment == 0) : ?>
            <!-- Wenn die Prüfung gestartet, aber noch nicht bewertet wurde -->
            <?php for ($i = 1; $i <= 10; $i++) : ?>
                <button class="btn btn-primary commitment-btn" value="<?php echo $i; ?>"><?php echo $i; ?></button>
            <?php endfor; ?>
        <?php elseif ($review->commitment > 0) : ?>
            <!-- Wenn die Prüfung bewertet wurde -->
            <button class="btn btn-success commitment-btn" value="0">Zurücksetzen</button>
        <?php endif; ?>
    </div>
</div>
