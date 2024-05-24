<form method="get">
    <div class="form-group row">
        <div class="col-md-5">
            <select class="form-select" id="job_id" name="job_id">
                <option value="">Alle Stellen</option>
                <?php foreach ( $jobs as $job ) : ?>
                    <option value="<?php echo esc_attr( $job->ID ); ?>" <?php selected( $selected_job, $job->ID ); ?>><?php echo esc_html( $job->job_title ); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-5">
            <select class="form-select" id="filter_tasks" name="filter_tasks">
                <option value="" <?php echo ($selected_tasks == '') ? 'selected' : ''; ?>>Alle Status</option>
                <option value="new" <?php echo ($selected_tasks == 'new') ? 'selected' : ''; ?>>Neu</option>
                <option value="waiting" <?php echo ($selected_tasks == 'waiting') ? 'selected' : ''; ?>>In Wartestellung</option>
                <option value="in_progress" <?php echo ($selected_tasks == 'in_progress') ? 'selected' : ''; ?>>In Bearbeitung</option>
                <option value="failed" <?php echo ($selected_tasks == 'failed') ? 'selected' : ''; ?>>Durchgefallen</option>
                <option value="passed" <?php echo ($selected_tasks == 'passed') ? 'selected' : ''; ?>>Bestanden</option>
            </select>
        </div>
        <div class="col-md-2 align-self-end">
            <button type="submit" class="btn btn-primary">Filtern</button>
        </div>
    </div>
</form>