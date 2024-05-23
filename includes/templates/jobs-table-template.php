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
                    <td><a href="<?php echo esc_url(home_url('/job-details/?id=' . $job->ID)); ?>">Details</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<td><a href="<?php echo esc_url(home_url('/job-details/?add=true')); ?>">Stelle hinzufügen</a></td>