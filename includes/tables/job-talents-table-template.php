<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Vorname</th>
                <th>Nachname</th>
                <th>Status</th>
                <th>Talentdetails</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($talents as $talent) : ?>
                <tr>
                    <td><?php echo $talent->prename; ?></td>
                    <td><?php echo $talent->surname; ?></td>
                    <td><?php $matching = get_matching_for_ids($talent->ID, $job->ID); echo $matching ? get_matching_state($matching->value) : 'Nicht gestartet'; ?></td>
                    <td><a href="<?php echo esc_url(home_url('/compare-details/?talent_id=' . $talent->ID.'&job_id=' . $job->ID)); ?>">Details</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>