<div class="card">
    <div class="card-body">
        <h5 class="card-title"><?php echo get_study_degree($study->degree); ?></h5>
        <p class="card-text"> <?php echo stripslashes_deep($study->designation); ?></p>
        <?php $object = $study;?>
            <?php include 'render-date.php'; ?>
        <button class="btn btn-primary edit-study" data-id="<?php echo $study->ID; ?>" data-designation="<?php echo stripslashes_deep($study->designation); ?>" data-field="<?php echo $study->field; ?>" data-degree="<?php echo $study->degree; ?>" data-start-date="<?php echo $study->start_date; ?>" data-end-date="<?php echo $study->end_date; ?>">Bearbeiten</button>
    </div>
</div>