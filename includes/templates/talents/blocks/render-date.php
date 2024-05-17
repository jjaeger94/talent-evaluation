<div class="row">
<?php if (isset($object->start_date)) : ?>
    <div class="col">
        <p class="card-text"><?php echo date("d.m.Y", strtotime($object->start_date)); ?></p>
    </div>
    <div class="col">
    <?php if ($object->end_date != '9999-12-31') : ?>
        <p class="card-text"> - <?php echo date("d.m.Y", strtotime($object->end_date)); ?></p>
    <?php endif; ?>
    </div>
<?php endif; ?>
</div>