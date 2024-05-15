<?php include 'filters/post-code-filter.php'; ?>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Vorname</th>
                <th>Nachname</th>
                <th>E-Mail</th>
                <th>Telefonnummer</th>
                <th>PLZ</th>
                <th>Ref</th>
                <th>Talentdetails</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($talents as $talent) : ?>
                <tr>
                    <td><?php echo $talent->ID; ?></td>
                    <td><?php echo $talent->prename; ?></td>
                    <td><?php echo $talent->surname; ?></td>
                    <td><?php echo $talent->email; ?></td>
                    <td><?php echo $talent->mobile; ?></td>
                    <td><?php echo $talent->post_code; ?></td>
                    <td><?php echo $talent->ref; ?></td>
                    <td><a href="<?php echo esc_url(home_url('/talent-details/?id=' . $talent->ID)); ?>">Details</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>