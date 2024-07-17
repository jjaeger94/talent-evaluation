<form id="edit-product-form" method="post">
<?php if(isset($product->ID)): ?>
    <input type="hidden" name="product_id" value="<?php echo $product->ID; ?>">
<?php endif; ?>
<input type="hidden" name="game_id" value="<?php echo $product->game_id; ?>">
<div class="form-group mb-3">
    <label for="product_name"><strong>Produktname</strong></label>
    <input type="text" class="form-control" id="product_name" name="product_name" value="<?php echo isset($product->product_name) ? esc_attr($product->product_name) : ''; ?>" required>
</div>
<div class="form-group mb-3">
    <label for="image_url"><strong>Bild URL</strong></label>
    <input type="text" class="form-control" id="image_url" name="image_url" value="<?php echo isset($product->image_url) ? esc_attr($product->image_url) : ''; ?>">
</div>
<div class="form-group mb-3">
    <label for="product_description"><strong>Produkt Beschreibung</strong></label>
    <textarea rows="3" class="form-control" id="product_description" name="product_description"><?php echo isset($product->product_description) ? esc_attr($product->product_description) : ''; ?></textarea>
</div>
<div class="form-group mb-3">
<label for="type"><strong>Typ</strong></label>
<select class="form-select" id="type" name="type">
    <?php for ($i = 0; $i <= 0; $i++) : ?>
        <option value="<?php echo $i; ?>" <?php echo (isset($product->type) && $product->type == $i) ? 'selected' : ''; ?>>
            <?php echo get_product_type($i); ?>
        </option>
    <?php endfor; ?>
</select>
</div>
    <button type="submit" class="btn btn-primary"><?php echo isset($product->ID) ? 'Änderungen speichern' : 'Neues Produkt anlegen'; ?></button>
</form>
<?php if(isset($product->ID)): ?>
<div class="row d-flex justify-content-end">
    <div class="col-auto">
        <button id="removeproduct" class="btn btn-danger">Produkt entfernen</button>
    </div>
</div>
<?php endif; ?>
<div id="form-message"></div>
<script>
jQuery(document).ready(function($) {
    $('#edit-product-form').submit(function(e) {
            e.preventDefault(); // Verhindert das Standard-Formular-Verhalten
            
            var formData = $(this).serialize(); // Serialisiert die Formulardaten
            
            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'POST',
                data: formData + '&action=edit_product', // Fügt die Aktion hinzu
                success: function(response) {
                    // Erfolgsfall: Weiterleitung oder Anzeige einer Erfolgsmeldung
                    console.log(response);
					$('#form-message').html(response.data);
                },
                error: function(xhr, status, error) {
                    // Fehlerfall: Anzeige einer Fehlermeldung
                    console.error('Fehler beim Speichern der Spieldaten:', error);
                }
            });
        });
        <?php if(isset($product->ID)): ?>
        $('#removeproduct').click(()=>{
            // AJAX-Anfrage senden
            if (confirm('Eintrag wirklich entfernen?')) {
                $.ajax({
                    type: 'POST',
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    data: 'product_id=<?php echo $product->ID; ?>&action=remove_product',
                    success: function(response) {
                        // Erfolgreiche Verarbeitung
                        console.log(response);
                        // Seite neu laden, um die aktualisierten Daten anzuzeigen
                        if(response.success){
                            window.location.href = '<?php echo home_url('/products/');?>';
                        }
                    },
                    error: function(xhr, status, error) {
                        // Fehlerbehandlung
                        console.error(error);
                    }
                });
            }
        });
        <?php endif; ?>
});
</script>
