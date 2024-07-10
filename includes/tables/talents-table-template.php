<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>E-Mail</th>
                <th>Telefonnummer</th>
                <th>PLZ</th>
                <th>Ref</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($talents as $talent) : ?>
                <tr>
                    <td><a href="<?php echo esc_url(home_url('/talent-details/?id=' . $talent->ID)); ?>"><?php echo $talent->prename . ' ' . $talent->surname; ?></a></td>
                    <td><?php echo esc_html($talent->email); ?></td>
                    <td><?php echo esc_html($talent->mobile); ?></td>
                    <td><?php echo esc_html($talent->post_code); ?></td>
                    <td><?php echo esc_html($talent->ref); ?></td>
                    <td><?php echo esc_html(get_talent_state($talent)); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
