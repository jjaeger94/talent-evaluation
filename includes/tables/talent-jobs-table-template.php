<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Firmenname</th>
                <th>Talentdetails</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($jobs as $job) : ?>
                <tr>
                    <td><?php echo $job->ID; ?></td>
                    <td><?php echo $job->job_title; ?></td>
                    <td><a href="<?php echo esc_url(home_url('/compare-details/?talent_id=' . $talent->ID.'&job_id=' . $job->ID)); ?>">Details</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>