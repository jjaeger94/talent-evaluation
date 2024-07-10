<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Stelle</th>
                <th>Kandidaten</th>
                <th>Jobstatus</th>
                <th>Unternehmen</th>
                <th>Unternehmensstatus</th>
                <th>Bearbeitungsstatus</th>
                <th>bearbeitet</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($jobs as $job) : ?>
                <tr>
                    <td><a href="<?php echo esc_url(home_url('/job-details/?id=' . $job->ID)); ?>"><?php echo $job->job_title; ?></a></td>
                    <td><?php echo $job->positive_matching_count; ?></td>
                    <td><?php echo get_job_state($job->state); ?></td>
                    <td>
                        <a href="<?php echo esc_url(home_url('/customer-details/?id=' . $job->customer_id)); ?>">
                            <?php echo isset($job->company) && $job->company != '' ? $job->company : $job->company_name; ?>
                        </a>    
                    </td>
                    <td><?php echo isset($job->company_state) ? get_customer_state($job->company_state) : ''; ?></td> 
                    <td><?php echo $job->notes; ?></td>
                    <td><?php echo date('d.m.Y', strtotime($job->edited)); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>