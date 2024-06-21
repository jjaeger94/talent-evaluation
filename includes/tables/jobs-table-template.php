<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Stelle</th>
                <th>Jobstatus</th>
                <th>Bearbeitungsstatus</th>
                <th>bearbeitet</th>
                <th>Stellendetails</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($jobs as $job) : ?>
                <tr>
                    <td><?php echo $job->job_title; ?></td>
                    <td><?php echo get_job_state($job->state); ?></td>
                    <td><?php echo $job->notes; ?></td>
                    <td><?php echo date('d.m.Y', strtotime($job->edited)); ?></td>
                    <td><a href="<?php echo esc_url(home_url('/job-details/?id=' . $job->ID)); ?>">Details</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>