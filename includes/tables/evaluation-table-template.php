<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Talent</th>
                <th>bearbeitet</th>
                <th>Bewertung</th>
                <th>Kommentar</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($evaluations as $eva) : ?>
                <tr>
                    <td>
                        <a href="<?php echo esc_url(home_url('/talent-details/?id=' . $eva->talent_id)); ?>">
                            <?php echo esc_html($eva->prename) .' '.esc_html($eva->surname); ?>
                        </a>
                    </td>
                    <td><?php echo date('d.m.Y', strtotime($eva->edited)); ?></td>
                    <td><?php echo esc_html($eva->rating); ?></td>
                    <td><?php echo esc_html($eva->comment); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>