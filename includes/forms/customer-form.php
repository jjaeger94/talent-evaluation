<form id="edit-customer-form" method="post">
<?php if(isset($customer->ID)): ?>
<input type="hidden" name="customer_id" value="<?php echo $customer->ID; ?>">
<?php endif; ?>
<div class="form-group mb-3">
    <label for="company"><strong>Firmenname</strong></label>
    <input type="text" class="form-control" id="company_name" name="company_name" value="<?php echo isset($customer->company_name) ? esc_attr($customer->company_name) : ''; ?>" required>
</div>
<div class="form-group mb-3">
    <label for="prename"><strong>Vorname</strong></label>
    <input type="text" class="form-control" id="prename" name="prename" value="<?php echo isset($customer->prename) ? esc_attr($customer->prename) : ''; ?>">
</div>
<div class="form-group mb-3">
    <label for="surname"><strong>Nachname</strong></label>
    <input type="text" class="form-control" id="surname" name="surname" value="<?php echo isset($customer->surname) ? esc_attr($customer->surname) : ''; ?>">
</div>
<div class="form-group mb-3">
    <label for="email"><strong>Email</strong></label>
    <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($customer->email) ? esc_attr($customer->email) : ''; ?>">
</div>
<div class="form-group mb-3">
    <label for="mobile" class="form-label"><strong>Telefonnummer:</strong></label>
    <input type="tel" class="form-control" id="mobile" name="mobile" placeholder="+4917123456789" value="<?php echo isset($customer->mobile) ? esc_attr($customer->mobile) : ''; ?>">
</div>
<div class="form-group mb-3">
    <label for="position"><strong>Position</strong></label>
    <input type="text" class="form-control" id="position" name="position" value="<?php echo isset($customer->position) ? esc_attr($customer->position) : ''; ?>">
</div>
<div class="form-group mb-3">
<label for="state"><strong>Status</strong></label>
<select class="form-select" id="state" name="state">
    <?php for ($i = 0; $i <= 4; $i++) : ?>
        <option value="<?php echo $i; ?>" <?php echo (isset($customer->state) && $customer->state == $i) ? 'selected' : ''; ?>>
            <?php echo get_customer_state($i); ?>
        </option>
    <?php endfor; ?>
</select>
</div>
    <button type="submit" class="btn btn-primary"><?php echo isset($customer->ID) ? 'Änderungen speichern' : 'Neuen Kunden anlegen'; ?></button>
</form>
<div id="form-message"></div>
<script>
jQuery(document).ready(function($) {
    $('#edit-customer-form').submit(function(e) {
            e.preventDefault(); // Verhindert das Standard-Formular-Verhalten
            
            var formData = $(this).serialize(); // Serialisiert die Formulardaten
            
            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'POST',
                data: formData + '&action=edit_customer', // Fügt die Aktion hinzu
                success: function(response) {
                    // Erfolgsfall: Weiterleitung oder Anzeige einer Erfolgsmeldung
                    console.log(response);
					$('#form-message').html(response.data);
                },
                error: function(xhr, status, error) {
                    // Fehlerfall: Anzeige einer Fehlermeldung
                    console.error('Fehler beim Speichern der Benutzerdaten:', error);
                }
            });
        });
});
</script>
