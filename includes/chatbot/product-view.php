<?php 
$products = get_products_for_game_id($game->ID);

if (!empty($products)) :
    // Filter products by type
    $own_products = array_filter($products, function($product) {
        return $product->type == 0;
    });
    $competitor_products = array_filter($products, function($product) {
        return $product->type == 1;
    });
?>


<?php if (!empty($own_products)) : ?>
<h5>Eigene Produkte:</h5>
<div class="row row-cols-1 row-cols-md-3 g-4">
    <?php foreach ($own_products as $product) : ?>
        <div class="col">
            <div class="card h-100">
                <img src="<?php echo $product->image_url; ?>" class="card-img-top">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $product->product_name; ?></h5>
                    <p class="card-text"><?php echo $product->product_description; ?></p>
                </div>
            </div>
        </div>
    <?php endforeach; ?> 
    </div>
<?php endif; ?>
<br>
<?php if (!empty($competitor_products)) : ?>
<h5>Konkurrenzprodukte:</h5>
<div class="row row-cols-1 row-cols-md-3 g-4">
    <?php foreach ($competitor_products as $product) : ?>
        <div class="col">
            <div class="card h-100">
                <img src="<?php echo $product->image_url; ?>" class="card-img-top">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $product->product_name; ?></h5>
                    <p class="card-text"><?php echo $product->product_description; ?></p>
                </div>
            </div>
        </div>
    <?php endforeach; ?> 
    </div>
<?php endif; ?>
<?php endif; ?>