<div class="row">
    <div class="col-md-4">
        <p><strong>Commitment Test:</strong></p>
        <?php if ($review->commitment == 0) : ?>
            <p>In Prüfung</p>
        <?php elseif ($review->commitment == -1) : ?>
            <p>Noch nicht überprüft</p>
        <?php else : ?>
            <p><?php echo $review->commitment; ?>%</p>
        <?php endif; ?>
    </div>
    <div class="col-md-8 d-flex justify-content-center align-items-center">
        <?php if ($review->commitment == -1) : ?>
            <!-- Wenn die Prüfung noch nicht gestartet wurde -->
            <button class="btn btn-success set-review-btn" data-type="commitment" value="0">Prüfung starten</button>
        <?php elseif ($review->commitment == 0) : ?>
            <!-- Wenn die Prüfung gestartet, aber noch nicht bewertet wurde -->
            <input type="number" class="form-control" id="review-commitment" name="review_commitment" min="0" max="100">
        <?php elseif ($review->commitment > 0) : ?>
            <!-- Wenn die Prüfung bewertet wurde -->
            <button class="btn btn-success set-review-btn" data-comment="true" data-type="commitment" value="0">Zurücksetzen</button>
        <?php endif; ?>
    </div>
</div>
