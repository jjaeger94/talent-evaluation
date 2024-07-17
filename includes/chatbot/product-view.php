<?php 
$products = get_products_for_game_id($game->ID);
if (!empty($products)) :
?>
<h5>Produkte:</h5>
<div class="row row-cols-1 row-cols-md-3 g-4">
    <?php foreach ($products as $product) : ?>
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