<!-- select-job.php -->

<form method="get">
    <div class="form-group row">
        <div class="col-md-8">
            <select class="form-select" id="jid" name="jid">
                <?php foreach ( $jobs as $job ) : ?>
                    <option value="<?php echo esc_attr( $job->ID ); ?>" <?php selected( $jid, $job->ID ); ?>><?php echo esc_html( $job->job_title ); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4 align-self-end">
            <button type="submit" class="btn btn-primary">Stelle bestätigen</button>
        </div>
    </div>
    <!-- Versteckte Felder für User-ID und Key -->
    <input type="hidden" name="uid" value="<?php echo esc_attr( $uid ); ?>">
    <input type="hidden" name="key" value="<?php echo esc_attr( $key ); ?>">
    <?php if (isset ( $aid )) : ?>
    <input type="hidden" name="aid" value="<?php echo esc_attr( $aid ); ?>">
    <?php endif;?>
</form>
