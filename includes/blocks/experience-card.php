<div class="card">
    <div class="card-body">
        <h5 class="card-title"><?php echo $experience->position; ?></h5>
        <p class="card-text"><?php echo $experience->company; ?></p>
        <?php $object = $experience;?>
            <?php include 'render-date.php'; ?>
        <button class="btn btn-primary edit-experience" data-id="<?php echo $experience->ID; ?>" data-field="<?php echo $experience->field; ?>" data-position="<?php echo $experience->position; ?>" data-company="<?php echo $experience->company; ?>" data-start-date="<?php echo $experience->start_date; ?>" data-end-date="<?php echo $experience->end_date; ?>">Bearbeiten</button>
    </div>
</div>