<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titel</th>
                <th>Zuletzt hinzugefügt</th>
                <th>Bearbeiten</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($tests as $test) : ?>
                <tr>
                    <td><?php echo $test->ID; ?></td>
                    <td><?php echo esc_html($test->title); ?></td>
                    <td><?php echo date('Y-m-d', strtotime($test->added)); ?></td>
                    <td><a href="<?php echo esc_url( home_url( '/test-details/?id=' . $test->ID ) ); ?>">Bearbeiten</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>