<div class="card mb-3">
<div class="card-body">
<form id="talentDetailForm" method="post">
<input type="hidden" name="talent_id" value="<?php echo $talent->ID; ?>">
<p class="card-title"><strong>Persönliche Daten:</strong></p>
    <div class="form-group mb-3">
        <label for="prename">Vorname:</label>
        <input type="text" class="form-control" id="prename" name="prename" value="<?php echo $talent->prename; ?>">
    </div>
    <div class="form-group mb-3">
        <label for="surname">Nachname:</label>
        <input type="text" class="form-control" id="surname" name="surname" value="<?php echo $talent->surname; ?>">
    </div>
    <div class="form-group mb-3">
        <label for="email">E-Mail:</label>
        <input type="email" class="form-control" id="email" name="email" value="<?php echo $talent->email; ?>">
    </div>
    <div class="form-group mb-3">
        <label for="mobile">Telefonnummer:</label>
        <input type="text" class="form-control" id="mobile" name="mobile" value="<?php echo $talent->mobile; ?>">
    </div>
    <div class="form-group mb-1">
        <label for="post_code">PLZ:</label>
        <input type="text" class="form-control" id="post_code" name="post_code" value="<?php echo $talent->post_code; ?>">
    </div>
    <div class="form-group mb-3">
        <label for="field">Verfügbarkeit:</label><?php echo info_button('personal_data_availability'); ?>
        <select class="form-select" id="availability" name="availability" required>
        <?php for ($i = 0; $i <= 7; $i++) : ?>
            <?php $selectedAvailability= ($talent->availability == $i) ? 'selected' : ''; ?>
            <option value="<?php echo $i; ?>" <?php echo $selectedAvailability; ?>><?php echo get_availability_string($i); ?></option>
        <?php endfor; ?>
        </select>
    </div>
    <?php include 'mobility.php'; ?>
    <?php include 'school.php'; ?>
</form>