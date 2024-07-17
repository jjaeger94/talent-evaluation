<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Produkt</th>
                <th>Beschreibung</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product) : ?>
                <tr>
                    <td><a href="<?php echo esc_url(home_url('/product-details/?id=' . $product->ID)); ?>"><?php echo $product->product_name .' ('. get_product_type($product->type) . ')'; ?></a></td>
                    <td><?php echo $product->product_description; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>