<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Stelle</th>
                <th>Matching</th>
                <th>Talentdetails</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($jobs as $job) : ?>
                <tr>
                    <td><?php echo $job->job_title; ?></td>
                    <td><?php $matching = get_matching_for_ids($talent->ID, $job->ID); echo $matching ? get_matching_state($matching->value) : 'Nicht gestartet'; ?></td>
                    <td><a href="<?php echo esc_url(home_url('/compare-details/?talent_id=' . $talent->ID.'&job_id=' . $job->ID)); ?>">Details</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>