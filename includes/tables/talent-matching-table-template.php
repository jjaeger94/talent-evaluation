<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Stelle</th>
                <th>bearbeitet</th>
                <th>Bewerberstatus</th>
                <th>Bearbeitungsstatus</th>
                <th>Matchingdetails</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($matchings as $match) : ?>
                <tr>
                    <td>
                        <a href="<?php echo esc_url(home_url('/talent-details/?id=' . $match->talent_id)); ?>">
                            <?php echo esc_html($match->prename) .' '.esc_html($match->surname); ?>
                        </a>
                    </td>
                    <td><?php echo date('d.m.Y', strtotime($match->edited)); ?></td>
                    <td><?php echo esc_html(get_matching_state($match->value)); ?></td>
                    <td><?php echo esc_html($match->notes); ?></td>
                    <td><a href="<?php echo esc_url(home_url('/compare-details/?talent_id=' . $match->talent_id. '&job_id='. $match->job_id)); ?>">Details</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
