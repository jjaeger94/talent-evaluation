<?php
if(!isset($_GET['add']) || $_GET['add'] == false):
?>
<button class="btn btn-primary mb-3" data-bs-toggle="collapse" data-bs-target="#editGameCollapse" aria-expanded="true" aria-controls="editGameCollapse">
    Spiel bearbeiten
</button>
<button class="btn btn-primary mb-3" data-bs-toggle="collapse" data-bs-target="#productsTableCollapse" aria-expanded="false" aria-controls="productsTableCollapse">
    Produkte anzeigen
</button>
<div class="collapse" id="productsTableCollapse">
    <div class="card card-body">
        <?php include TE_DIR.'tables/products-table-template.php';?>   
    </div>
    <a href="<?php echo home_url("/product-details/?add=true&game_id=".$id); ?>" class="btn btn-primary">Produkt hinzufügen</a>
</div>
<div class="collapse show" id="editGameCollapse">
    <div class="card card-body">
        <?php include TE_DIR.'forms/game-form.php';?>
    </div>
</div>
<?php else : ?>
    <?php include TE_DIR.'forms/game-form.php'; ?>
<?php endif; ?>