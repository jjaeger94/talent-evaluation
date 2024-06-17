<div class="card">
    <div class="card-body">
        <h5 class="card-title"><?php echo stripslashes_deep($apprenticeship->designation); ?></h5>
        <p class="card-text"><?php echo get_apprenticeship_field($apprenticeship->field); ?></p>
        <?php $object = $apprenticeship;?>
        <?php include 'render-date.php'; ?>
        <button href="#" class="btn btn-primary edit-apprenticeship" data-id="<?php echo $apprenticeship->ID; ?>" data-designation="<?php echo stripslashes_deep($apprenticeship->designation); ?>" data-field="<?php echo $apprenticeship->field; ?>" data-start-date="<?php echo $apprenticeship->start_date; ?>" data-end-date="<?php echo $apprenticeship->end_date; ?>">Bearbeiten</button>
    </div>
</div>