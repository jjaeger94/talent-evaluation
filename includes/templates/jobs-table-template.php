<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Stellenbezeichnung</th>
                <th>Anzahl Kandidaten</th>
                <th>Prüfung läuft</th>
                <th>Prüfung beendet</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ( $jobs as $job ) : ?>
                <tr>
                    <td class="align-middle"> <!-- "align-middle" für vertikale Zentrierung und "text-center" für horizontale Zentrierung -->
                        <strong><?php echo $job->job_title; ?></strong><br>
                        <?php echo $job->location; ?><br>
                        Erstellt am: <?php echo date('d.m.Y', strtotime($job->added)); ?> <!-- Nur das Datum anzeigen -->
                    </td>
                    <td class="align-middle"><?php echo get_application_count_for_job($job->ID); ?></td>
                    <td class="align-middle"><?php echo get_ongoing_application_count_for_job($job->ID); ?></td>
                    <td class="align-middle"><?php echo get_finished_application_count_for_job($job->ID); ?></td>
                    <td class="align-middle"><?php echo $job->state == 'active' ? 'aktiv' : 'inaktiv'; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
