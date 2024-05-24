<div class="card mb-3">
    <div class="card-body">
        <h5 class="card-title"><?php echo $talent->prename . ' ' . $talent->surname; ?></h5>
        <div class="row">
            <div class="col">
            <p><strong>Hinzugefügt:</strong> <?php echo date('d.m.Y', strtotime($talent->added)); ?></p>
            </div>
            <div class="col">
            <p><strong>Bearbeitet:</strong> <?php echo date('d.m.Y', strtotime($talent->edited)); ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col">
            <p><strong>Ref:</strong> <?php echo $talent->ref; ?></p>
            </div>
            <div class="col">
            <?php if ($talent->member_id) : ?>
                <p><strong>member_id:</strong> <?php echo $talent->member_id; ?></p>
            <?php endif; ?>
            </div>
        </div>
        <?php include 'actions.php'; ?>
    </div>
</div>