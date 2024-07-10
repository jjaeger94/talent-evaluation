<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Firmenname</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($customers as $customer) : ?>
                <tr>
                    <td><a href="<?php echo esc_url(home_url('/customer-details/?id=' . $customer->ID)); ?>"><?php echo $customer->company_name; ?></a></td>
                    <td><?php echo get_customer_state($customer->state); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>