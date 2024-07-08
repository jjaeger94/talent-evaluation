<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Firmenname</th>
                <th>Status</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($customers as $customer) : ?>
                <tr>
                    <td><?php echo $customer->ID; ?></td>
                    <td><?php echo $customer->company_name; ?></td>
                    <td><?php echo get_customer_state($customer->state); ?></td>
                    <td><a href="<?php echo esc_url(home_url('/customer-details/?id=' . $customer->ID)); ?>">Details</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>