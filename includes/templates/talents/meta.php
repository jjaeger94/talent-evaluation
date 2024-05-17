<div class="card mb-3">
    <div class="card-body">
        <div class="row">
            <div class="col">
            <p><strong>Hinzugefügt:</strong> <?php echo date('d.m.Y', strtotime($talent->added)); ?></p>
            </div>
            <div class="col">
            <p><strong>Bearbeitet:</strong> <?php echo date('d.m.Y', strtotime($talent->edited)); ?></p>
            </div>
            <div class="col">
            <p><strong>Ref:</strong> <?php echo $talent->ref; ?></p>
            </div>
        </div>
    </div>
</div>