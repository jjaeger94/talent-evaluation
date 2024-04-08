<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Stellenbezeichnung</th>
                <th>Erstelldatum</th>
                <th>Standort</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ( $jobs as $job ) : ?>
                <tr>
                    <td><?php echo $job->job_title; ?></td>
                    <td><?php echo $job->added; ?></td>
                    <td><?php echo $job->location; ?></td>
                    <td><?php echo $job->state == 'active' ? 'aktiv' : 'inaktiv'; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
