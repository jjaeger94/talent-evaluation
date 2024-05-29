<div class="row">
<?php if (isset($object->start_date)) : ?>
    <div class="col">
        <p class="card-text"><?php echo date("d.m.Y", strtotime($object->start_date)); ?></p>
    </div>
    <?php if ($object->end_date != '9999-12-31') : ?>
        <div class="col text-center"> - </div>
        <div class="col">
        <p class="card-text"><?php echo date("d.m.Y", strtotime($object->end_date)); ?></p>
        </div>
    <?php endif; ?>
<?php endif; ?>
</div>