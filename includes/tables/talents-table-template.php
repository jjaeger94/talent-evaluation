<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>E-Mail</th>
                <th>Telefonnummer</th>
                <th>PLZ</th>
                <th>Ref</th>
                <th>Status</th>
                <th>Talentdetails</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($talents as $talent) : ?>
                <tr>
                    <td><?php echo $talent->email; ?></td>
                    <td><?php echo $talent->mobile; ?></td>
                    <td><?php echo $talent->post_code; ?></td>
                    <td><?php echo $talent->ref; ?></td>
                    <td><?php echo get_talent_state($talent); ?></td>
                    <td><a href="<?php echo esc_url(home_url('/talent-details/?id=' . $talent->ID)); ?>">Details</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>